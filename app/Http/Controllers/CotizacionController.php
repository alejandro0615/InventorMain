<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Proveedor;
use App\Models\Producto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controlador para gestionar las cotizaciones de productos a clientes.
 */
class CotizacionController extends Controller
{
    /**
     * Muestra el panel de cotizaciones con productos y clientes disponibles.
     */
    public function index(Request $request)
    {
        $localId = Auth::user()->local_id;
        $productos = Producto::where('local_id', $localId)->get();
        $proveedores = Proveedor::where('local_id', $localId)
            ->orderBy('nombre')
            ->get();
            
        // Obtenemos las últimas 5 cotizaciones para poder descargarlas
        $ultimasCotizaciones = Cotizacion::where('local_id', $localId)
            ->with('cliente')
            ->latest()
            ->take(5)
            ->get();

        if ($request->ajax()) {
            return view('cotizaciones._content', compact('productos', 'proveedores', 'ultimasCotizaciones'));
        }

        return view('cotizaciones.index', compact('productos', 'proveedores', 'ultimasCotizaciones'));
    }

    /**
     * Crea y almacena una nueva cotización.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id'    => ['required', 'exists:proveedores,id'],
            'fecha'         => ['required', 'date'],
            'producto_id'   => ['required', 'array'],
            'producto_id.*' => ['required', 'exists:productos,id'],
            'cantidad'      => ['required', 'array'],
            'cantidad.*'    => ['required', 'integer', 'min:1'],
        ], [
            'cliente_id.required'  => 'Debes seleccionar un cliente.',
            'producto_id.required' => 'Debes agregar al menos un producto.',
        ]);

        $cotizacionId = null;

        DB::transaction(function () use ($data, &$cotizacionId) {
            $subtotalGeneral = 0;
            $localId = Auth::user()->local_id;

            $cliente = Proveedor::where('local_id', $localId)->findOrFail($data['cliente_id']);

            $cotizacion = Cotizacion::create([
                'cliente_id' => $cliente->id,
                'fecha'      => $data['fecha'],
                'subtotal'   => 0,
                'impuestos'  => 0,
                'total'      => 0,
                'local_id'   => $localId,
            ]);

            $cotizacionId = $cotizacion->id;

            foreach ($data['producto_id'] as $i => $productoId) {
                $cantidad = (int) $data['cantidad'][$i];
                $producto = Producto::where('local_id', $localId)->findOrFail($productoId);

                $precioVenta = $producto->precio_venta;
                $subtotalLinea = $cantidad * $precioVenta;
                $subtotalGeneral += $subtotalLinea;

                CotizacionDetalle::create([
                    'cotizacion_id'   => $cotizacion->id,
                    'producto_id'     => $producto->id,
                    'cantidad'        => $cantidad,
                    'precio_unitario' => $precioVenta,
                    'subtotal'        => $subtotalLinea,
                ]);
            }

            $cotizacion->update([
                'subtotal'  => $subtotalGeneral,
                'total'     => $subtotalGeneral,
            ]);
        });

        return redirect()
            ->route('cotizaciones.index')
            ->with('success', 'Cotización #' . $cotizacionId . ' creada correctamente.')
            ->with('open_pdf', route('cotizaciones.pdf', $cotizacionId));
    }

    /**
     * Genera el PDF de la cotización.
     */
    public function descargarPdf(Cotizacion $cotizacion)
    {
        if ($cotizacion->local_id !== Auth::user()->local_id) {
            abort(403);
        }

        $cotizacion->load(['detalles.producto', 'cliente']);
        $user = Auth::user();

        $pdf = Pdf::loadView('cotizaciones.pdf', compact('cotizacion', 'user'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream('cotizacion-' . $cotizacion->id . '.pdf');
    }

    /**
     * Elimina una cotización.
     */
    public function destroy(Cotizacion $cotizacion)
    {
        if ($cotizacion->local_id !== Auth::user()->local_id) {
            abort(403);
        }

        $cotizacion->detalles()->delete();
        $cotizacion->delete();

        return redirect()
            ->route('cotizaciones.index')
            ->with('success', 'Cotización eliminada correctamente.');
    }
}
