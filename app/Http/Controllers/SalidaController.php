<?php

namespace App\Http\Controllers;

use App\Models\Salida;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SalidaController extends Controller
{
    /**
     * Generar y mostrar PDF de factura (stream)
     */
    public function facturaPdf(Salida $salida)
    {
        // Cargar todas las relaciones necesarias
        $salida->load([
            'detalles.producto',
            'cliente',
            'user.negocio'
        ]);

        $pdf = Pdf::loadView('facturas.salida', [
            'salida' => $salida,
        ])->setPaper('letter');

        return $pdf->stream('factura-' . ($salida->numero_factura ?? $salida->id) . '.pdf');
    }

    /**
     * Descargar PDF de factura
     */
    public function facturaDownload(Salida $salida)
    {
        // Cargar todas las relaciones necesarias
        $salida->load([
            'detalles.producto',
            'cliente',
            'user.negocio'
        ]);

        $pdf = Pdf::loadView('facturas.salida', [
            'salida' => $salida,
        ])->setPaper('letter');

        return $pdf->download('factura-' . ($salida->numero_factura ?? $salida->id) . '.pdf');
    }
    public function destroy($id)
    {
        $salida = Salida::with('detalles.producto')->findOrFail($id);

        // Revertir stock de los productos
        foreach ($salida->detalles as $detalle) {
            $detalle->producto->increment('stock_actual', $detalle->cantidad);
        }

        // Eliminar detalles de la factura
        $salida->detalles()->delete();

        // Eliminar la factura
        $salida->delete();

        return redirect()->route('movimientos.index')
            ->with('success', 'Factura eliminada correctamente.');
    }
}

