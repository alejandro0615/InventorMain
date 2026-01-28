<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Relación con el LOCAL/Negocio
     */
    public function negocio()
    {
        return $this->belongsTo(Local::class, 'local_id');
    }

    /**
     * Campos permitidos para MASS ASSIGNMENT (registro/controlador)
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
     * Campos ocultos en respuestas JSON/API
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts automáticos de tipos
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'estado' => 'string', // enum como string
        ];
    }

    /**
     * Scope para usuarios activos solamente
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Accesor para mostrar razón social o nombre
     */
    public function getNombreCompletoAttribute()
    {
        return $this->razon_social ?: $this->name;
    }

    /**
     * Verificar si usuario está activo
     */
    public function getIsActivoAttribute()
    {
        return $this->estado === 'activo';
    }
}
