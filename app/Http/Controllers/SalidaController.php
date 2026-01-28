<?php

namespace App\Http\Controllers;

use App\Models\Salida;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SalidaController extends Controller
{
    public function facturaPdf(Salida $salida)
{
    $salida->load('detalles.producto'); // detalles con producto e IVA

    $pdf = Pdf::loadView('facturas.salida', [
        'salida' => $salida,
    ])->setPaper('letter'); // o 'a4'

    return $pdf->stream('factura-salida-'.$salida->id.'.pdf');
    // o ->download(...)
}
}
