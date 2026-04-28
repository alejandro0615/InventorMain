<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    protected $fillable = [
        'nombre',
        'razon_social',
        'direccion',
        'telefono',
        'email',
        'nit',
        'local_id',
    ];//con esto hacemos proteccion apra evitar asignacion masiva
    protected $guarded = [];//con esto indicamos que campos se pueden llenar de forma masiva  
    
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'movimientos')
            ->withPivot('tipo_movimiento', 'fecha', 'cantidad')->withTimestamps();
        //los pivot son los atributos de seguimiento en una tabla muchos a muchos
    }
    public function local()
    {
        return $this->belongsTo(Local::class);
    }
}
