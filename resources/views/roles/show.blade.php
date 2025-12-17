@extends('layouts.app')

@section('title', 'Ver Rol')

@section('styles')
<style>
    .role-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
    }

    .role-header.superadmin {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }

    .role-header.administrador {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    }

    .role-header.encargado {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    }

    .role-header.usuario {
        background: linear-gradient(135deg, #6c757d 0%, #5c636a 100%);
    }

    .info-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .info-card-header {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-bottom: 2px solid #e9ecef;
        font-weight: 600;
    }

    .info-card-body {
        padding: 1.5rem;
    }

    .permission-module {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .permission-module-title {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .permission-badge {
        display: inline-block;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin: 0.25rem;
    }

    .permission-badge.create {
        background: #d1fae5;
        color: #065f46;
    }

    .permission-badge.read {
        background: #dbeafe;
        color: #1e40af;
    }

    .permission-badge.update {
        background: #fef3c7;
        color: #92400e;
    }

    .permission-badge.delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .permission-badge.restore {
        background: #e0e7ff;
        color: #3730a3;
    }

    .permission-badge.force_delete {
        background: #fce7f3;
        color: #831843;
    }

    .sticky-actions {
        position: sticky;
        top: 20px;
    }

    .action-btn {
        width: 100%;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border-radius: 10px;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    @php
        $roleClass = strtolower(str_replace(' ', '', $role->nombre));
    @endphp

    <!-- Header -->
    <div class="role-header {{ $roleClass }}">
        <div class="d-flex align-items-center gap-3">
            <div style="font-size: 3rem;">
                @switch($role->nombre)
                    @case('Superadmin')
                        <i class="bi bi-star-fill"></i>
                        @break
                    @case('Administrador')
                        <i class="bi bi-shield-check"></i>
                        @break
                    @case('Encargado')
                        <i class="bi bi-person-badge"></i>
                        @break
                    @case('Usuario')
                        <i class="bi bi-person"></i>
                        @break
                    @default
                        <i class="bi bi-shield"></i>
                @endswitch
            </div>
            <div class="flex-grow-1">
                <h1 class="mb-1">{{ $role->nombre }}</h1>
                @if($role->descripcion)
                    <p class="mb-0 opacity-90">{{ $role->descripcion }}</p>
                @endif
            </div>
            <div>
                @if($role->activo)
                    <span class="badge bg-success bg-opacity-25" style="font-size: 1rem; padding: 0.5rem 1rem;">
                        <i class="bi bi-check-circle"></i> Activo
                    </span>
                @else
                    <span class="badge bg-secondary bg-opacity-25" style="font-size: 1rem; padding: 0.5rem 1rem;">
                        <i class="bi bi-dash-circle"></i> Inactivo
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Contenido Principal -->
        <div class="col-lg-8">
            <!-- Permisos del Rol -->
            <div class="info-card">
                <div class="info-card-header">
                    <i class="bi bi-key"></i> Permisos del Rol
                </div>
                <div class="info-card-body">
                    @if($role->permisos && count($role->permisos) > 0)
                        @foreach($role->permisos as $modulo => $acciones)
                            @if(is_array($acciones) && count($acciones) > 0)
                                <div class="permission-module">
                                    <div class="permission-module-title">
                                        <i class="bi bi-folder"></i>
                                        {{ \App\Models\Role::getModulosDisponibles()[$modulo] ?? ucfirst($modulo) }}
                                    </div>
                                    <div>
                                        @foreach($acciones as $accion)
                                            <span class="permission-badge {{ $accion }}">
                                                @switch($accion)
                                                    @case('create')
                                                        <i class="bi bi-plus-circle"></i>
                                                        @break
                                                    @case('read')
                                                        <i class="bi bi-eye"></i>
                                                        @break
                                                    @case('update')
                                                        <i class="bi bi-pencil"></i>
                                                        @break
                                                    @case('delete')
                                                        <i class="bi bi-trash"></i>
                                                        @break
                                                    @case('restore')
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                        @break
                                                    @case('force_delete')
                                                        <i class="bi bi-trash-fill"></i>
                                                        @break
                                                @endswitch
                                                {{ \App\Models\Role::getAccionesDisponibles()[$accion] ?? ucfirst($accion) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-shield-slash" style="font-size: 3rem;"></i>
                            <p class="mt-2 mb-0">Este rol no tiene permisos asignados</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Usuarios con este rol -->
            <div class="info-card">
                <div class="info-card-header">
                    <i class="bi bi-people"></i> Usuarios con este Rol ({{ $role->users_count }})
                </div>
                <div class="info-card-body">
                    @if($role->users_count > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Departamento</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($role->users()->limit(10)->get() as $user)
                                        <tr>
                                            <td>{{ $user->nombre }} {{ $user->apellido }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->departamento }}</td>
                                            <td>
                                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($role->users_count > 10)
                            <div class="text-center mt-3">
                                <a href="{{ route('users.index') }}?role={{ $role->id }}" class="btn btn-sm btn-outline-primary">
                                    Ver todos los usuarios <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-person-x" style="font-size: 3rem;"></i>
                            <p class="mt-2 mb-0">No hay usuarios con este rol</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar con Acciones -->
        <div class="col-lg-4">
            <div class="sticky-actions">
                <h5 class="text-primary mb-3"><i class="bi bi-gear"></i> Acciones</h5>
                
                @if(auth()->user()->isSuperadmin())
                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning action-btn">
                        <i class="bi bi-pencil"></i> Editar Rol
                    </a>
                    
                    @if($role->nombre !== 'Superadmin')
                        <button type="button" class="btn btn-danger action-btn" 
                                data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i> Eliminar Rol
                        </button>
                    @endif
                @endif
                
                <hr>
                
                <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary action-btn">
                    <i class="bi bi-arrow-left"></i> Volver al Listado
                </a>

                <hr>
                
                <div class="info-card">
                    <div class="info-card-body">
                        <h6 class="text-primary mb-3"><i class="bi bi-info-circle"></i> Información</h6>
                        
                        <p class="small mb-2">
                            <strong>Creado:</strong><br>
                            <span class="text-muted">{{ $role->created_at->format('d/m/Y H:i') }}</span>
                        </p>
                        
                        <p class="small mb-2">
                            <strong>Última actualización:</strong><br>
                            <span class="text-muted">{{ $role->updated_at->format('d/m/Y H:i') }}</span>
                        </p>

                        <p class="small mb-0">
                            <strong>Usuarios asignados:</strong><br>
                            <span class="text-muted">{{ $role->users_count }} {{ $role->users_count == 1 ? 'usuario' : 'usuarios' }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
@if(auth()->user()->isSuperadmin() && $role->nombre !== 'Superadmin')
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle"></i> Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro que desea eliminar el rol <strong>{{ $role->nombre }}</strong>?</p>
                    @if($role->users_count > 0)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            Este rol tiene <strong>{{ $role->users_count }}</strong> 
                            {{ $role->users_count == 1 ? 'usuario asignado' : 'usuarios asignados' }}.
                            No se puede eliminar.
                        </div>
                    @else
                        <p class="text-muted mb-0">
                            <small>Esta acción se puede revertir desde la papelera.</small>
                        </p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    @if($role->users_count == 0)
                        <form action="{{ route('roles.destroy', $role) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
@endsection