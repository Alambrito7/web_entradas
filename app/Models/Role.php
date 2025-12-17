<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'descripcion',
        'permisos',
        'activo',
    ];

    protected $casts = [
        'permisos' => 'array',
        'activo' => 'boolean',
    ];

    /**
     * Relación con usuarios
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Verificar si el rol tiene un permiso específico
     */
    public function hasPermission($module, $action)
    {
        if (!$this->permisos || !isset($this->permisos[$module])) {
            return false;
        }

        return in_array($action, $this->permisos[$module]);
    }

    /**
     * Verificar si es Superadmin
     */
    public function isSuperadmin()
    {
        return $this->nombre === 'Superadmin';
    }

    /**
     * Verificar si es Administrador o superior
     */
    public function isAdminOrAbove()
    {
        return in_array($this->nombre, ['Superadmin', 'Administrador']);
    }

    /**
     * Obtener badge HTML del rol
     */
    public function getBadgeAttribute()
    {
        $badges = [
            'Superadmin' => '<span class="badge bg-danger">Superadmin</span>',
            'Administrador' => '<span class="badge bg-primary">Administrador</span>',
            'Encargado' => '<span class="badge bg-warning">Encargado</span>',
            'Usuario' => '<span class="badge bg-secondary">Usuario</span>',
        ];

        return $badges[$this->nombre] ?? '<span class="badge bg-dark">Sin Rol</span>';
    }

    /**
     * Obtener todos los módulos disponibles
     */
    public static function getModulosDisponibles()
    {
        return [
            'users' => 'Usuarios',
            'roles' => 'Roles',
            'danzas' => 'Danzas',
            'entradas' => 'Entradas',
            'fraternidades' => 'Fraternidades',
            'recorridos' => 'Recorridos',
        ];
    }

    /**
     * Obtener todas las acciones disponibles
     */
    public static function getAccionesDisponibles()
    {
        return [
            'create' => 'Crear',
            'read' => 'Ver',
            'update' => 'Editar',
            'delete' => 'Eliminar',
            'restore' => 'Restaurar',
            'force_delete' => 'Eliminar Permanentemente',
        ];
    }

    /**
     * Obtener color según el rol
     */
    public function getColorAttribute()
    {
        $colors = [
            'Superadmin' => '#dc3545',
            'Administrador' => '#0d6efd',
            'Encargado' => '#ffc107',
            'Usuario' => '#6c757d',
        ];

        return $colors[$this->nombre] ?? '#6c757d';
    }
}