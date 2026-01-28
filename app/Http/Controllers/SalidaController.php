<?php

namespace App\Http\Controllers;

use App\Models\Salida;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
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
}
