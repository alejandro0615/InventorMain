<?php

namespace App\Services;

use App\Models\Salida;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Servicio para la integración con el API de Factus (Facturación Electrónica).
 */
class FactusService
{
    protected string $baseUrl;
    protected string $apiVersion;

    public function __construct()
    {
        $this->baseUrl = config('services.factus.url');
        $this->apiVersion = trim((string) config('services.factus.api_version', 'v1'), '/');
    }

    /**
     * Obtiene el token de acceso OAuth2.
     */
    public function obtenerToken(): ?string
    {
        return Cache::remember('factus_token', 3600, function () {
            $response = Http::asForm()->post($this->baseUrl . '/oauth/token', [
                'grant_type' => 'password',
                'client_id' => config('services.factus.client_id'),
                'client_secret' => config('services.factus.client_secret'),
                'username' => config('services.factus.username'),
                'password' => config('services.factus.password'),
            ]);

            if ($response->failed()) {
                Log::error('Factus: Error al obtener token', ['response' => $response->json()]);
                return null;
            }

            return $response->json('access_token');
        });
    }

    /**
     * Prepara y envía una factura a Factus.
     */
    public function procesarFactura(Salida $salida): array
    {
        if ($this->yaProcesada($salida)) {
            return [
                'status' => 'skipped',
                'message' => 'La factura ya fue enviada o está finalizada en Factus.',
            ];
        }

        $data = $this->prepararDatosFactura($salida);
        $respuesta = $this->enviarFactura($data);
        $this->actualizarEstadoSalida($salida, $respuesta);

        return $respuesta;
    }

    protected function yaProcesada(Salida $salida): bool
    {
        return in_array($salida->estado_dian, ['validada'], true) 
            || $salida->factus_id 
            || $salida->cufe 
            || $salida->url_factus;
    }

    /**
     * Envía los datos preparados al API de Factus.
     */
    public function enviarFactura(array $data): array
    {
        $token = $this->obtenerToken();

        if (!$token) {
            return ['error' => true, 'message' => 'No se pudo obtener el token de Factus.'];
        }

        Log::info('Factus: Enviando datos a validación', ['payload' => $data]);

        $response = Http::acceptJson()
            ->withToken($token)
            ->post($this->baseUrl . '/' . $this->apiVersion . '/bills/validate', $data);

        $json = $response->json();

        Log::info('Factus: Respuesta recibida', [
            'status' => $response->status(),
            'response' => $json
        ]);

        if (!$response->successful() || (isset($json['errors']) && !isset($json['data']))) {
            return [
                'error' => true,
                'status' => $response->status(),
                'response' => $json,
                'message' => $this->extraerMensajeError($json),
            ];
        }

        return $json;
    }

    /**
     * Construye el array de datos según la estructura exitosa probada en Postman.
     */
    protected function prepararDatosFactura(Salida $salida): array
    {
        $factusReferences = config('services.factus.reference_ids', []);
        $cliente = $salida->cliente;

        $legalOrganizationId = $cliente->tipo == 'empresa'
            ? data_get($factusReferences, 'customer_legal_organization_id_company', '1')
            : data_get($factusReferences, 'customer_legal_organization_id_person', '2');

        $data = [
            'numbering_range_id' => config('services.factus.numbering_range_id'),
            'reference_code' => (string) ($salida->numero_factura ?? $salida->id),
            'observation' => (string) ($salida->notas ?? 'Venta de productos'),
            'payment_method_code' => '10', // Efectivo por defecto
            'customer' => [
                'identification' => (string) $cliente->nit,
                'names' => (string) ($cliente->razon_social ?? $cliente->nombre),
                'address' => (string) ($cliente->direccion ?? 'N/A'),
                'email' => (string) ($cliente->email ?? 'test@test.com'),
                'phone' => (string) ($cliente->telefono ?? '000000000'),
                'legal_organization_id' => (string) $legalOrganizationId,
                'tribute_id' => (string) data_get($factusReferences, 'customer_tribute_id', '21'),
                'identification_document_id' => (string) data_get($factusReferences, 'customer_identification_document_id', '6'),
                'municipality_id' => (string) data_get($factusReferences, 'customer_municipality_id', '980'),
            ],
            'items' => [],
        ];

        foreach ($salida->detalles as $detalle) {
            $data['items'][] = [
                'code_reference' => (string) ($detalle->producto->codigo ?? 'P-' . $detalle->producto->id),
                'name' => (string) $detalle->producto->nombre,
                'quantity' => (float) $detalle->cantidad,
                'discount' => 0,
                'discount_rate' => 0,
                'price' => (float) $detalle->precio_unitario,
                'tax_rate' => number_format((float) ($detalle->iva_porcentaje ?? 0), 2, '.', ''),
                'unit_measure_id' => (int) data_get($factusReferences, 'item_unit_measure_id', 70),
                'standard_code_id' => (int) data_get($factusReferences, 'item_standard_code_id', 1),
                'is_excluded' => (int) (($detalle->iva_porcentaje > 0) ? 0 : 1),
                'tribute_id' => (int) data_get($factusReferences, 'item_tribute_id', 1),
                'withholding_taxes' => [],
            ];
        }

        return $data;
    }

    /**
     * Actualiza el modelo Salida con la respuesta del API.
     */
    protected function actualizarEstadoSalida(Salida $salida, array $respuesta): void
    {
        if (!isset($respuesta['error']) && isset($respuesta['data'])) {
            $bill = $respuesta['data']['bill'] ?? $respuesta['data'];

            $salida->update([
                'factus_id' => $bill['id'] ?? $bill['reference_code'] ?? null,
                'estado_dian' => 'validada',
                'numero_factura' => $bill['number'] ?? $salida->numero_factura,
                'cufe' => $bill['cufe'] ?? null,
                'url_factus' => data_get($bill, 'public_url') ?? data_get($respuesta, 'data.bill.public_url'),
            ]);
        } else {
            $status = $respuesta['status'] ?? null;
            $salida->update([
                'estado_dian' => $status === 409 ? 'pendiente' : 'error_envio',
            ]);
        }
    }

    /**
     * Extrae un mensaje de error legible de la respuesta del API.
     */
    protected function extraerMensajeError(array $respuesta): string
    {
        return $respuesta['message'] ?? 'Error desconocido en Factus.';
    }
}
