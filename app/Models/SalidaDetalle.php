<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa el detalle de una Salida (cada producto vendido).
 */
class SalidaDetalle extends Model
{
    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'salida_detalles';

    /**
     * Atributos asignables de forma masiva.
     */
    protected $fillable = [
        'salida_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'iva_porcentaje',
        'subtotal',
    ];

    /**
     * Relación con la salida (encabezado de factura).
     */
    public function salida()
    {
        return $this->belongsTo(Salida::class, 'salida_id');
    }

    /**
     * Relación con el producto asociado a este detalle.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
