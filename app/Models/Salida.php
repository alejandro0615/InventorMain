<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    protected $table = 'salidas';
    protected $fillable = [
        'cliente_id',
        'user_id',
        'numero_factura',
        'fecha',
        'notas',
        'total',
        'iva_porcentaje',
    ];

    public function detalles()
    {
        return $this->hasMany(SalidaDetalle::class, 'salida_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Proveedor::class, 'cliente_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Accessor para obtener el total formateado
    public function getTotalFormateadoAttribute()
    {
        return number_format($this->total, 2, ',', '.');
    }
}
