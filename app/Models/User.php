<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'ci',
        'departamento',
        'telefono',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Accessor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return ucfirst($this->nombre) . ' ' . 
               ucfirst($this->apellido_paterno) . ' ' . 
               ucfirst($this->apellido_materno);
    }

    // Mutator para capitalizar nombre
    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = ucfirst(strtolower($value));
    }

    public function setApellidoPaternoAttribute($value)
    {
        $this->attributes['apellido_paterno'] = ucfirst(strtolower($value));
    }

    public function setApellidoMaternoAttribute($value)
    {
        $this->attributes['apellido_materno'] = ucfirst(strtolower($value));
    }

    // Constantes para departamentos
    public static function getDepartamentos()
    {
        return [
            'La Paz',
            'Cochabamba',
            'Santa Cruz',
            'Oruro',
            'Potosí',
            'Chuquisaca',
            'Tarija',
            'Beni',
            'Pando'
        ];
    }
}