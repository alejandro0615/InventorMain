<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
     protected $table = 'salidas';
    protected $fillable = [
        'salida_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'iva_porcentaje',
        'subtotal',
    ];

    public function detalles()
    {
        return $this->hasMany(SalidaDetalle::class, 'salida_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Proveedor::class, 'cliente_id');
    }
} 
