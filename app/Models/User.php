<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo que representa a un Usuario del sistema.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atributos asignables de forma masiva.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'razon_social',
        'nit',
        'rut',
        'telefono',
        'local_id',
        'estado',
    ];

    /**
     * Atributos ocultos para la serialización.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts de atributos.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'estado'            => 'string',
        ];
    }

    /**
     * Relación con el Local al que pertenece el usuario.
     */
    public function negocio()
    {
        return $this->belongsTo(Local::class, 'local_id');
    }

    /**
     * Scope para filtrar solo usuarios activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Accessor para obtener el nombre completo o la razón social.
     */
    public function getNombreCompletoAttribute()
    {
        return $this->razon_social ?: $this->name;
    }

    /**
     * Verifica si el usuario tiene estado activo.
     */
    public function getIsActivoAttribute()
    {
        return $this->estado === 'activo';
    }
}
