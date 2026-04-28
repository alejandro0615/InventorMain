<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa un Local o Sucursal del negocio.
 */
class Local extends Model
{
    /**
     * Tabla asociada al modelo en plural.
     */
    protected $table = 'locales';

    /**
     * Atributos asignables masivamente.
     */
    protected $fillable = ['nombre'];

    /**
     * Relación con los usuarios que pertenecen a este local.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relación con los productos del inventario de este local.
     */
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
