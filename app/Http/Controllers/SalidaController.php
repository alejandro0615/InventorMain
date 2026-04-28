<?php

namespace App\Http\Controllers;

use App\Models\Salida;
use App\Services\FactusService;
use App\Services\MovementService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

/**
 * Controlador para gestionar la visualización de facturas de salida y reenvío a Factus.
 */
class SalidaController extends Controller
{
    protected FactusService $factusService;
    protected MovementService $movementService;

    public function __construct(FactusService $factusService, MovementService $movementService)
    {
        $this->factusService = $factusService;
        $this->movementService = $movementService;
    }

    /**
     * Genera y muestra el PDF de la factura (Formato Tirilla 80mm).
     */
    public function facturaPdf(Salida $salida)
    {
        $salida->load(['detalles.producto', 'cliente', 'user.negocio']);

        // Tamaño de papel personalizado para tirilla 80mm (aprox 226.77 pt)
        $pdf = Pdf::loadView('facturas.salida', ['salida' => $salida])
            ->setPaper([0, 0, 226.77, 841.89], 'portrait');

        return $pdf->stream('factura-' . ($salida->numero_factura ?? $salida->id) . '.pdf');
    }

    /**
     * Descarga el PDF de la factura (Formato Tirilla 80mm).
     */
    public function facturaDownload(Salida $salida)
    {
        $salida->load(['detalles.producto', 'cliente', 'user.negocio']);

        // Tamaño de papel personalizado para tirilla 80mm
        $pdf = Pdf::loadView('facturas.salida', ['salida' => $salida])
            ->setPaper([0, 0, 226.77, 841.89], 'portrait');

        return $pdf->download('factura-' . ($salida->numero_factura ?? $salida->id) . '.pdf');
    }

    /**
     * Elimina una factura (anulación) y restaura el stock.
     */
    public function destroy($id)
    {
        $this->movementService->cancelSalida($id);

        return redirect()
            ->route('movimientos.index')
            ->with('success', 'Factura anulada y movimientos revertidos correctamente.');
    }

    /**
     * Intenta enviar o reenviar una factura al API de Factus.
     */
    public function reenviarAFactus(Salida $salida)
    {
        $respuesta = $this->factusService->procesarFactura($salida);

        if (($respuesta['status'] ?? null) === 'skipped') {
            return redirect()
                ->route('movimientos.index')
                ->with('info', $respuesta['message'] ?? 'La factura ya fue procesada.');
        }

        if (isset($respuesta['error'])) {
            return redirect()
                ->route('movimientos.index')
                ->with('error', $respuesta['message'] ?? 'Error al conectar con Factus.');
        }

        return redirect()
            ->route('movimientos.index')
            ->with('success', 'Factura procesada correctamente.');
    }
}
