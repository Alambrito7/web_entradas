<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'email',
        'password',
        'telefono',
        'ci',
        'departamento',
        'role_id', // IMPORTANTE: debe estar aquí
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relación con Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Verificar si el usuario tiene un permiso específico
     */
    public function hasPermission($module, $action)
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->hasPermission($module, $action);
    }

    /**
     * Verificar si es Superadmin
     */
    public function isSuperadmin()
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->isSuperadmin();
    }

    /**
     * Verificar si es Administrador o superior
     */
    public function isAdminOrAbove()
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->isAdminOrAbove();
    }

    /**
     * Verificar si puede realizar una acción
     */
    public function can($action, $model = null)
    {
        // Superadmin puede todo
        if ($this->isSuperadmin()) {
            return true;
        }

        // Si no tiene rol, no puede hacer nada
        if (!$this->role) {
            return false;
        }

        // Aquí puedes agregar lógica más compleja si es necesario
        return false;
    }

    /**
     * Obtener nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return trim($this->nombre . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno);
    }

    /**
     * Obtener lista de departamentos de Bolivia
     */
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
            'Pando',
        ];
    }
}