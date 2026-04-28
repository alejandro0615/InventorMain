<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa el detalle de una Cotización.
 */
class CotizacionDetalle extends Model
{
    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'cotizacion_detalles';

    /**
     * Atributos asignables de forma masiva.
     */
    protected $fillable = [
        'cotizacion_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    /**
     * Relación con la cotización (encabezado).
     */
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'cotizacion_id');
    }

    /**
     * Relación con el producto cotizado.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}