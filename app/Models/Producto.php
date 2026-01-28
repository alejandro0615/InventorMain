<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    public function proveedores()
    {
        return $this->belongsToMany(Proveedor::class, 'movimientos')
            ->withPivot('tipo_movimiento', 'fecha', 'cantidad', 'salida_id', 'local_id')
            ->withTimestamps();
    }
    protected $fillable = [
        'codigo',
        'nombre',
        'stock_actual',
        'precio_compra',
        'precio_venta',
        'local_id',

    ];

    public function local()
    {
        return $this->belongsTo(Local::class);
    }
}
