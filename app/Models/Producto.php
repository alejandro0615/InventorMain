<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Modelo que representa un Producto en el inventario.
 */
class Producto extends Model
{
    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'productos';

    /**
     * Atributos asignables de forma masiva.
     */
    protected $fillable = [
        'codigo',
        'nombre',
        'stock_actual',
        'precio_compra',
        'precio_venta',
        'local_id',
    ];

    /**
     * Relación con los proveedores a través de los movimientos.
     */
    public function proveedores()
    {
        return $this->belongsToMany(Proveedor::class, 'movimientos')
            ->withPivot('tipo_movimiento', 'fecha', 'cantidad', 'salida_id', 'local_id')
            ->withTimestamps();
    }

    /**
     * Relación con el local al que pertenece el producto.
     */
    public function local()
    {
        return $this->belongsTo(Local::class);
    }

    /**
     * Scope para filtrar productos por local.
     */
    public function scopeDelLocal(Builder $query, int $localId): Builder
    {
        return $query->where('local_id', $localId);
    }

    /**
     * Scope para obtener productos con stock bajo (menor o igual a 5).
     */
    public function scopeStockBajo(Builder $query): Builder
    {
        return $query->where('stock_actual', '<=', 5);
    }

    /**
     * Scope para buscar por nombre o código.
     */
    public function scopeBuscar(Builder $query, ?string $termino): Builder
    {
        if (empty($termino)) {
            return $query;
        }

        return $query->where(function ($q) use ($termino) {
            $q->where('codigo', 'like', "%{$termino}%")
              ->orWhere('nombre', 'like', "%{$termino}%");
        });
    }
}
