<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa una Salida (Factura de Venta)
 */
class Salida extends Model
{
    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'salidas';

    /**
     * Atributos asignables de forma masiva.
     */
    protected $fillable = [
        'cliente_id',
        'user_id',
        'numero_factura',
        'fecha',
        'notas',
        'total',
        'iva_porcentaje',
        'factus_id',
        'estado_dian', // Nombre real de la columna en la BD
        'cufe',
        'url_factus',
    ];

    /**
     * Relación con los detalles de la salida (productos vendidos).
     */
    public function detalles()
    {
        return $this->hasMany(SalidaDetalle::class, 'salida_id');
    }

    /**
     * Relación con el cliente (Proveedor).
     */
    public function cliente()
    {
        return $this->belongsTo(Proveedor::class, 'cliente_id');
    }

    /**
     * Relación con el usuario que creó la salida.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Accessor para obtener el total formateado como moneda local.
     */
    public function getTotalFormateadoAttribute()
    {
        return number_format($this->total, 2, ',', '.');
    }

    /**
     * Accessor de compatibilidad para estado_factus (mapea a estado_dian).
     */
    public function getEstadoFactusAttribute()
    {
        return $this->estado_dian;
    }
}
