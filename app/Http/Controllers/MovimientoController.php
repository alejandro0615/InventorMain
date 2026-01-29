<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Salida;
use App\Models\SalidaDetalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovimientoController extends Controller
{
    public function index(Request $request)
    {
        $localId = Auth::user()->local_id;
        $proveedorId = $request->input('proveedor_id');
        $fecha = $request->input('fecha');

        $proveedores = Proveedor::where('local_id', $localId)
            ->orderBy('nombre')
            ->get();

        // Volvemos a Salida (solo salidas, no entradas)
        $movimientos = Salida::with(['detalles.producto', 'cliente'])
            ->whereHas('cliente', function ($q) use ($localId) {
                $q->where('local_id', $localId);
            })
            ->when($proveedorId, fn($q) => $q->where('cliente_id', $proveedorId))
            ->when($fecha, fn($q) => $q->whereDate('fecha', $fecha))
            ->orderBy('fecha', 'desc')
            ->get();

        return view('movimientos.index', compact('movimientos', 'proveedores', 'proveedorId', 'fecha'));
    }

    public function create()
    {
        $localId = Auth::user()->local_id;
        $productos = Producto::where('local_id', $localId)->get();
        $proveedores = Proveedor::where('local_id', $localId)->get();

        return view('movimientos.create', compact('productos', 'proveedores'));
    }

    public function store(Request $request)
    {
        $tipo_movimiento = ['entrada', 'salida'];

        $data = $request->validate(
            [
                'proveedor_id' => ['required', 'exists:proveedores,id'],
                'fecha' => ['required', 'date'],
                'tipo_movimiento' => ['required', 'in:' . implode(',', $tipo_movimiento)],
                'producto_id' => ['required', 'array'],
                'producto_id.*' => ['required', 'exists:productos,id'],
                'cantidad' => ['required', 'array'],
                'cantidad.*' => ['required', 'integer', 'min:1'],
                'precio' => ['required', 'array'],
                'precio.*' => ['required', 'numeric', 'min:0'],
                'iva_porcentaje' => ['nullable', 'array'],
                'iva_porcentaje.*' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'notas' => ['nullable', 'string'],
            ]
        );

        $salida = null;

        DB::transaction(function () use ($data, $request, &$salida) {
            $localId = Auth::user()->local_id;

            if ($data['tipo_movimiento'] === 'salida') {
                $salida = Salida::create([
                    'cliente_id' => $data['proveedor_id'],
                    'user_id' => Auth::id(),
                    'fecha' => $data['fecha'],
                    'notas' => $request->input('notas'),
                    'iva_porcentaje' => 0,
                    'total' => 0,
                ]);
            }

            $subtotalGeneral = 0;
            $ivaGeneral = 0;

            foreach ($data['producto_id'] as $index => $productoId) {
                $cantidad = (int) $data['cantidad'][$index];

                $producto = Producto::where('local_id', $localId)->findOrFail($productoId);

                if ($data['tipo_movimiento'] === 'salida' && $producto->stock_actual < $cantidad) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'cantidad.' . $index => 'No hay suficiente stock para ' .
                            $producto->nombre . '. Stock actual: ' . $producto->stock_actual,
                    ]);
                }

                $precioUnitario = (float) $data['precio'][$index];
                $ivaPorcentaje = isset($data['iva_porcentaje'][$index])
                    ? (float) $data['iva_porcentaje'][$index]
                    : 0;

                if ($data['tipo_movimiento'] === 'entrada') {
                    $producto->increment('stock_actual', $cantidad);
                } else {
                    $producto->decrement('stock_actual', $cantidad);

                    if ($salida) {
                        $subtotalLinea = $cantidad * $precioUnitario;
                        $ivaLinea = $subtotalLinea * ($ivaPorcentaje / 100);

                        $subtotalGeneral += $subtotalLinea;
                        $ivaGeneral += $ivaLinea;

                        SalidaDetalle::create([
                            'salida_id' => $salida->id,
                            'producto_id' => $productoId,
                            'cantidad' => $cantidad,
                            'precio_unitario' => $precioUnitario,
                            'iva_porcentaje' => $ivaPorcentaje,
                            'subtotal' => $subtotalLinea,
                        ]);
                    }
                }
            }

            if ($salida) {
                $salida->total = $subtotalGeneral + $ivaGeneral;
                $salida->numero_factura = 'F' . str_pad($salida->id, 6, '0', STR_PAD_LEFT);
                $salida->save();
            }
        });

        if ($data['tipo_movimiento'] === 'salida' && $salida) {
            return redirect()
                ->route('salidas.factura', $salida)
                ->with('success', 'Movimiento de salida registrado y factura generada correctamente.');
        }

        return redirect()
            ->route('movimientos.index')
            ->with('success', 'Movimiento registrado exitosamente y stock actualizado.');
    }

    public function update()
    {
        abort(403, 'No se pueden editar movimientos.');
    }

    public function destroy($id)
    {
        // Aquí seguimos borrando la Salida (factura) y sus detalles,
        // como en tu versión inicial
        $salida = Salida::with('detalles.producto')
            ->whereHas(
                'cliente',
                fn($q) =>
                $q->where('local_id', Auth::user()->local_id)
            )
            ->findOrFail($id);

        DB::transaction(function () use ($salida) {
            foreach ($salida->detalles as $detalle) {
                // Devolver stock
                $detalle->producto->increment('stock_actual', $detalle->cantidad);
                $detalle->delete();
            }

            $salida->delete();
        });

        return redirect()
            ->route('movimientos.index')
            ->with('success', 'Factura anulada y stock restaurado correctamente.');
    }
    public function historial(Request $request)
    {
        $localId = Auth::user()->local_id;
        $fecha = $request->input('fecha');

        $query = SalidaDetalle::query()
            ->join('salidas', 'salida_detalles.salida_id', '=', 'salidas.id')
            ->join('productos', 'salida_detalles.producto_id', '=', 'productos.id')
            ->where('salidas.local_id', $localId);

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

        return view('movimientos.historial', compact(
            'historial',
            'fecha',
            'totalDia'
        ));
    }
}
