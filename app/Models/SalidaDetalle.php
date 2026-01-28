<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalidaDetalle extends Model
{
    protected $table = 'salida_detalles'; // o el nombre que uses

    protected $fillable = [
        'salida_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'iva_porcentaje',
        'subtotal',
    ];

    public function salida()
    {
        return $this->belongsTo(Salida::class, 'salida_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}

