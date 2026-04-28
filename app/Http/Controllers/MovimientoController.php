<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Salida;
use App\Models\SalidaDetalle;
use App\Services\FactusService;
use App\Services\MovementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador para gestionar los movimientos de inventario y facturación.
 */
class MovimientoController extends Controller
{
    protected FactusService $factusService;
    protected MovementService $movementService;

    public function __construct(FactusService $factusService, MovementService $movementService)
    {
        $this->factusService = $factusService;
        $this->movementService = $movementService;
    }

    /**
     * Muestra el historial de movimientos (Facturas de salida).
     */
    public function index(Request $request)
    {
        $localId = Auth::user()->local_id;
        $proveedorId = $request->input('proveedor_id');
        $fecha = $request->input('fecha');

        // Proveedores disponibles para el local actual
        $proveedores = Proveedor::where('local_id', $localId)
            ->orderBy('nombre')
            ->get();
            
        // Consulta de movimientos (Entradas y Salidas)
        $movimientos = \App\Models\Movimiento::with(['producto', 'proveedor', 'salida.detalles'])
            ->whereHas('producto', function ($q) use ($localId) {
                $q->where('local_id', $localId);
            })
            ->when($proveedorId, fn($q) => $q->where('proveedor_id', $proveedorId))
            ->when($fecha, fn($q) => $q->whereDate('fecha', $fecha))
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('movimientos.index', compact('movimientos', 'proveedores', 'proveedorId', 'fecha'));
    }

    /**
     * Muestra el formulario para registrar una Entrada o Salida.
     */
    public function create()
    {
        $localId = Auth::user()->local_id;
        $productos = Producto::where('local_id', $localId)->get();
        $proveedores = Proveedor::where('local_id', $localId)->get();

        return view('movimientos.create', compact('productos', 'proveedores'));
    }

    /**
     * Procesa y almacena un movimiento de inventario.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'proveedor_id'    => ['required', 'exists:proveedores,id'],
            'fecha'           => ['required', 'date'],
            'tipo_movimiento' => ['required', 'in:entrada,salida'],
            'producto_id'     => ['required', 'array'],
            'producto_id.*'   => ['required', 'exists:productos,id'],
            'cantidad'        => ['required', 'array'],
            'cantidad.*'      => ['required', 'integer', 'min:1'],
            'precio'          => ['required', 'array'],
            'precio.*'        => ['required', 'numeric', 'min:0'],
            'iva_porcentaje'  => ['nullable', 'array'],
            'iva_porcentaje.*'=> ['nullable', 'numeric', 'min:0', 'max:100'],
            'notas'           => ['nullable', 'string'],
        ]);

        // Registrar movimiento mediante el servicio
        $salida = $this->movementService->register($data);

        // Si fue una salida, intentamos enviar a Factus
        if ($data['tipo_movimiento'] === 'salida' && $salida) {
            $respuestaFactus = $this->factusService->procesarFactura($salida);

            return $this->handleFactusResponse($respuestaFactus, $salida);
        }

        return redirect()
            ->route('movimientos.index')
            ->with('success', 'Movimiento registrado exitosamente y stock actualizado.');
    }

    /**
     * Anula un movimiento y restaura el stock.
     */
    public function destroy($id)
    {
        $this->movementService->cancelMovement($id);

        return redirect()
            ->route('movimientos.index')
            ->with('success', 'Movimiento anulado y stock actualizado correctamente.');
    }

    /**
     * Muestra el historial consolidado de ventas.
     */
    public function historial(Request $request)
    {
        $localId = Auth::user()->local_id;
        $fecha = $request->input('fecha');

        $query = SalidaDetalle::query()
            ->join('salidas', 'salida_detalles.salida_id', '=', 'salidas.id')
            ->join('productos', 'salida_detalles.producto_id', '=', 'productos.id')
            ->where('productos.local_id', $localId);

        if ($fecha) {
            $query->whereDate('salidas.fecha', $fecha);
        }

        $historial = $query
            ->selectRaw('
                productos.id AS producto_id,
                productos.nombre AS producto,
                SUM(salida_detalles.cantidad) AS total_cantidad,
                SUM(salida_detalles.subtotal) AS total_vendido
            ')
            ->groupBy('productos.id', 'productos.nombre')
            ->orderBy('producto')
            ->get();

        $totalDia = $historial->sum('total_vendido');

        return view('historial.index', compact('historial', 'fecha', 'totalDia'));
    }

    /**
     * Gestiona la respuesta del API de Factus para el usuario final.
     */
    protected function handleFactusResponse(array $respuesta, Salida $salida)
    {
        if (($respuesta['status'] ?? null) === 'skipped') {
            return redirect()
                ->route('movimientos.index')
                ->with('warning', $respuesta['message'] ?? 'La factura ya fue procesada.');
        }

        if (isset($respuesta['error'])) {
            return redirect()
                ->route('movimientos.index')
                ->with('error', $respuesta['message'] ?? 'Error al conectar con Factus.');
        }

        if (!($respuesta['data']['is_validated'] ?? false)) {
            return redirect()
                ->route('movimientos.index')
                ->with('warning', 'Factura enviada. Queda pendiente de validación por la DIAN.');
        }

        return redirect()
            ->route('movimientos.index')
            ->with('success', 'Factura enviada correctamente.')
            ->with('open_pdf', route('salidas.factura', $salida->id));
    }
}
