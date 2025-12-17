@extends('layouts.app')

@section('title', 'Detalle del Usuario')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="bi bi-person-badge"></i> Detalle del Usuario
                </h4>
                <div>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 text-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 120px; height: 120px; font-size: 48px;">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <h3 class="mt-3 mb-0">{{ $user->nombre_completo }}</h3>
                        <p class="text-muted">Usuario del Sistema</p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">
                                    <i class="bi bi-person"></i> Nombre
                                </h6>
                                <p class="mb-0 fs-5"><strong>{{ $user->nombre }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">
                                    <i class="bi bi-person"></i> Apellido Paterno
                                </h6>
                                <p class="mb-0 fs-5"><strong>{{ $user->apellido_paterno }}</strong></p>
                            </div>
                        </div>
                    </div>

                    @if($user->apellido_materno)
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">
                                    <i class="bi bi-person"></i> Apellido Materno
                                </h6>
                                <p class="mb-0 fs-5"><strong>{{ $user->apellido_materno }}</strong></p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">
                                    <i class="bi bi-card-text"></i> Carnet de Identidad
                                </h6>
                                <p class="mb-0 fs-5"><strong>{{ $user->ci }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">
                                    <i class="bi bi-geo-alt"></i> Departamento
                                </h6>
                                <p class="mb-0">
                                    <span class="badge bg-info fs-6">{{ $user->departamento }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">
                                    <i class="bi bi-telephone"></i> Teléfono
                                </h6>
                                <p class="mb-0 fs-5">
                                    <a href="tel:{{ $user->telefono }}" class="text-decoration-none">
                                        {{ $user->telefono }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">
                                    <i class="bi bi-envelope"></i> Correo Electrónico
                                </h6>
                                <p class="mb-0 fs-5">
                                    <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                        {{ $user->email }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">
                            <i class="bi bi-calendar-plus"></i> 
                            <strong>Fecha de Registro:</strong>
                        </p>
                        <p>{{ $user->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">
                            <i class="bi bi-calendar-check"></i> 
                            <strong>Última Actualización:</strong>
                        </p>
                        <p>{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Listado
                    </a>
                    <div>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <button type="button" 
                                class="btn btn-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
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
                <p>¿Está seguro que desea eliminar este usuario?</p>
                <div class="alert alert-warning">
                    <strong>{{ $user->nombre_completo }}</strong><br>
                    CI: {{ $user->ci }}<br>
                    Email: {{ $user->email }}
                </div>
                <p class="text-danger mb-0">
                    <i class="bi bi-exclamation-circle"></i> 
                    Esta acción no se puede deshacer.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Sí, Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection