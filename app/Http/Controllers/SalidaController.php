<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Salida;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalidaController extends Controller
{
    public function facturaPdf(Salida $salida)
    {
        $salida->load([
            'detalles.producto',
            'cliente',
            'user.negocio'
        ]);

        $pdf = Pdf::loadView('facturas.salida', ['salida' => $salida])
            ->setPaper('letter');

        return $pdf->stream('factura-' . ($salida->numero_factura ?? $salida->id) . '.pdf');
    }

    public function facturaDownload(Salida $salida)
    {
        $salida->load([
            'detalles.producto',
            'cliente',
            'user.negocio'
        ]);

        $pdf = Pdf::loadView('facturas.salida', ['salida' => $salida])
            ->setPaper('letter');

        return $pdf->download('factura-' . ($salida->numero_factura ?? $salida->id) . '.pdf');
    }

    public function destroy($id)
    {
        $salida = Salida::with('detalles.producto')->findOrFail($id);

        // 1️⃣ Revertir stock de los productos
        foreach ($salida->detalles as $detalle) {
            $detalle->producto->increment('stock_actual', $detalle->cantidad);
        }

        // 2️⃣ Eliminar movimientos relacionados en la tabla pivot
        DB::table('movimientos')->where('salida_id', $salida->id)->delete();

        // 3️⃣ Eliminar detalles de la factura
        $salida->detalles()->delete();

        // 4️⃣ Eliminar la factura
        $salida->delete();

        return redirect()->route('movimientos.index')
            ->with('success', 'Factura y movimientos eliminados correctamente.');
    }
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => ['nullable', 'exists:proveedores,id'],
            'fecha' => ['required', 'date'],
            'notas' => ['nullable', 'string'],
            'numero_factura' => ['nullable', 'string', 'max:50'],

            // ⚠️ ya NO confiamos en estos
            // 'total' => ['required', 'numeric', 'min:0'],
            // 'iva_porcentaje' => ['required', 'numeric', 'min:0'],

            'productos' => ['required', 'array'],
            'productos.*.producto_id' => ['required', 'exists:productos,id'],
            'productos.*.cantidad' => ['required', 'integer', 'min:1'],
            'productos.*.precio_unitario' => ['required', 'numeric', 'min:0'],
            'productos.*.iva_porcentaje' => ['required', 'numeric', 'min:0'],
        ]);

        DB::beginTransaction();

        try {

            // 🔢 1) Calcular totales reales
            $subtotalGeneral = 0;
            $ivaGeneral = 0;

            foreach ($request->productos as $item) {
                $subtotal = $item['cantidad'] * $item['precio_unitario'];
                $iva = $subtotal * ($item['iva_porcentaje'] / 100);

                $subtotalGeneral += $subtotal;
                $ivaGeneral += $iva;
            }

            // 🧾 2) Crear la salida con valores reales
            $salida = Salida::create([
                'cliente_id' => $request->cliente_id,
                'user_id' => Auth::id(),
                'numero_factura' => $request->numero_factura,
                'fecha' => $request->fecha,
                'notas' => $request->notas,
                'total' => $subtotalGeneral + $ivaGeneral,
                'iva_porcentaje' => null, // ya no se usa a nivel global
            ]);

            // 📦 3) Crear detalles, descontar stock y registrar movimientos
            foreach ($request->productos as $item) {

                $producto = Producto::findOrFail($item['producto_id']);

                $salida->detalles()->create([
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'iva_porcentaje' => $item['iva_porcentaje'],
                ]);

                // 🔻 Descontar stock
                $producto->decrement('stock_actual', $item['cantidad']);

                // 🧾 Registrar movimiento
                DB::table('movimientos')->insert([
                    'producto_id' => $producto->id,
                    'salida_id' => $salida->id,
                    'tipo' => 'salida',
                    'cantidad' => $item['cantidad'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('movimientos.index')
                ->with('success', 'Movimiento creado correctamente.');

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->withErrors(
                'Error al crear el movimiento: ' . $e->getMessage()
            );
        }
    }
}
