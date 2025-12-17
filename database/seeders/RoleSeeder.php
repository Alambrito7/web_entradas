<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'nombre' => 'Superadmin',
                'descripcion' => 'Acceso total al sistema. Puede gestionar todo incluyendo roles y usuarios.',
                'permisos' => json_encode([
                    'users' => ['create', 'read', 'update', 'delete', 'restore', 'force_delete'],
                    'roles' => ['create', 'read', 'update', 'delete'],
                    'danzas' => ['create', 'read', 'update', 'delete', 'restore', 'force_delete'],
                    'entradas' => ['create', 'read', 'update', 'delete', 'restore', 'force_delete'],
                    'fraternidades' => ['create', 'read', 'update', 'delete', 'restore', 'force_delete'],
                    'recorridos' => ['create', 'read', 'update', 'delete', 'restore', 'force_delete'],
                ]),
                'activo' => true,
            ],
            [
                'nombre' => 'Administrador',
                'descripcion' => 'Puede gestionar contenido y usuarios, pero no roles del sistema.',
                'permisos' => json_encode([
                    'users' => ['create', 'read', 'update', 'delete'],
                    'roles' => ['read'],
                    'danzas' => ['create', 'read', 'update', 'delete', 'restore'],
                    'entradas' => ['create', 'read', 'update', 'delete', 'restore'],
                    'fraternidades' => ['create', 'read', 'update', 'delete', 'restore'],
                    'recorridos' => ['create', 'read', 'update', 'delete', 'restore'],
                ]),
                'activo' => true,
            ],
            [
                'nombre' => 'Encargado',
                'descripcion' => 'Puede crear y editar contenido, pero no eliminar ni gestionar usuarios.',
                'permisos' => json_encode([
                    'users' => ['read'],
                    'roles' => ['read'],
                    'danzas' => ['create', 'read', 'update'],
                    'entradas' => ['create', 'read', 'update'],
                    'fraternidades' => ['create', 'read', 'update'],
                    'recorridos' => ['create', 'read', 'update'],
                ]),
                'activo' => true,
            ],
            [
                'nombre' => 'Usuario',
                'descripcion' => 'Solo puede visualizar el contenido del sistema.',
                'permisos' => json_encode([
                    'users' => ['read'],
                    'roles' => [],
                    'danzas' => ['read'],
                    'entradas' => ['read'],
                    'fraternidades' => ['read'],
                    'recorridos' => ['read'],
                ]),
                'activo' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            Role::create($roleData);
        }
    }
}