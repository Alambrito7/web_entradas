@extends('layouts.app')

@section('title', 'Roles')

@section('styles')
<style>
    .role-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        transition: all 0.3s ease;
        height: 100%;
        border-left: 4px solid;
    }

    .role-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .role-card.superadmin {
        border-left-color: #dc3545;
    }

    .role-card.administrador {
        border-left-color: #0d6efd;
    }

    .role-card.encargado {
        border-left-color: #ffc107;
    }

    .role-card.usuario {
        border-left-color: #6c757d;
    }

    .role-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 1rem;
    }

    .role-icon.superadmin {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .role-icon.administrador {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: white;
    }

    .role-icon.encargado {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: white;
    }

    .role-icon.usuario {
        background: linear-gradient(135deg, #6c757d 0%, #5c636a 100%);
        color: white;
    }

    .permission-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin: 0.25rem;
    }

    .stats-small {
        font-size: 0.85rem;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-shield-lock"></i> Gestión de Roles</h2>
            <p class="text-muted">Administra los roles y permisos del sistema</p>
        </div>
        <div class="col-md-4 text-end">
            @if(auth()->user()->isSuperadmin())
                <a href="{{ route('roles.trashed') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-trash"></i> Roles Eliminados
                </a>
                <a href="{{ route('roles.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nuevo Rol
                </a>
            @endif
        </div>
    </div>

    @if($roles->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-shield-lock" style="font-size: 5rem; color: #ccc;"></i>
            <h3 class="mt-3 text-muted">No hay roles registrados</h3>
            <p class="text-muted">Comienza creando el primer rol del sistema</p>
            @if(auth()->user()->isSuperadmin())
                <a href="{{ route('roles.create') }}" class="btn btn-primary btn-lg mt-3">
                    <i class="bi bi-plus-circle"></i> Crear Primer Rol
                </a>
            @endif
        </div>
    @else
        <div class="row g-4">
            @foreach($roles as $role)
                @php
                    $roleClass = strtolower(str_replace(' ', '', $role->nombre));
                @endphp
                <div class="col-lg-3 col-md-6">
                    <div class="role-card {{ $roleClass }}">
                        <div class="role-icon {{ $roleClass }}">
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

                        <h5 class="mb-2">{{ $role->nombre }}</h5>
                        
                        @if($role->descripcion)
                            <p class="text-muted small mb-3">{{ Str::limit($role->descripcion, 80) }}</p>
                        @endif

                        <div class="mb-3">
                            <span class="stats-small">
                                <i class="bi bi-people"></i> 
                                <strong>{{ $role->users_count }}</strong> 
                                {{ $role->users_count == 1 ? 'usuario' : 'usuarios' }}
                            </span>
                        </div>

                        <div class="mb-3">
                            @if($role->activo)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Activo
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-dash-circle"></i> Inactivo
                                </span>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('roles.show', $role) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Ver Detalles
                            </a>
                            
                            @if(auth()->user()->isSuperadmin())
                                <div class="btn-group" role="group">
                                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    
                                    @if($role->nombre !== 'Superadmin')
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $role->id }}">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Modal de Confirmación de Eliminación -->
                <div class="modal fade" id="deleteModal{{ $role->id }}" tabindex="-1">
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
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $roles->links('pagination::bootstrap-5') }}
        </div>
    @endif

    <!-- Información sobre permisos -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información sobre Roles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary"><i class="bi bi-star-fill text-danger"></i> Superadmin</h6>
                            <p class="small text-muted">Acceso total al sistema. Puede gestionar roles, usuarios y todo el contenido.</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary"><i class="bi bi-shield-check"></i> Administrador</h6>
                            <p class="small text-muted">Puede gestionar usuarios y contenido, pero no modificar roles del sistema.</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary"><i class="bi bi-person-badge text-warning"></i> Encargado</h6>
                            <p class="small text-muted">Puede crear y editar contenido, pero no eliminar ni gestionar usuarios.</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary"><i class="bi bi-person text-secondary"></i> Usuario</h6>
                            <p class="small text-muted">Solo puede visualizar el contenido del sistema sin realizar modificaciones.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection