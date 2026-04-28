<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table = 'movimientos';

    protected $fillable = [
        'local_id',
        'producto_id',
        'proveedor_id',
        'tipo_movimiento',
        'fecha',
        'cantidad',
        'salida_id',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function salida()
    {
        return $this->belongsTo(Salida::class);
    }
}
