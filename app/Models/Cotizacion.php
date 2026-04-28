<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa una Cotización enviada a un cliente.
 */
class Cotizacion extends Model
{
    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'cotizaciones';

    /**
     * Atributos asignables de forma masiva.
     */
    protected $fillable = [
        'cliente_id',
        'fecha',
        'subtotal',
        'impuestos',
        'total',
        'local_id',
    ];

    /**
     * Relación con los productos detallados en la cotización.
     */
    public function detalles()
    {
        return $this->hasMany(CotizacionDetalle::class, 'cotizacion_id');
    }

    /**
     * Relación con el cliente (Proveedor) al que se le cotiza.
     */
    public function cliente()
    {
        return $this->belongsTo(Proveedor::class, 'cliente_id');
    }

    /**
     * Relación con el local donde se genera la cotización.
     */
    public function local()
    {
        return $this->belongsTo(Local::class, 'local_id');
    }
}
