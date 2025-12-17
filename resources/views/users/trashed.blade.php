@extends('layouts.app')

@section('title', 'Usuarios Eliminados')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h2><i class="bi bi-trash-fill"></i> Usuarios Eliminados</h2>
        <p class="text-muted">Usuarios que han sido eliminados y pueden ser restaurados</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('users.index') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Volver a Usuarios Activos
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($users->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                <h4 class="mt-3 text-muted">No hay usuarios eliminados</h4>
                <p class="text-muted">Los usuarios eliminados aparecerán aquí y podrán ser restaurados.</p>
                <a href="{{ route('users.index') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-arrow-left"></i> Volver a Usuarios
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-danger">
                        <tr>
                            <th>#</th>
                            <th>Nombre Completo</th>
                            <th>CI</th>
                            <th>Departamento</th>
                            <th>Email</th>
                            <th>Fecha de Eliminación</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <strong>{{ $user->nombre_completo }}</strong>
                                    <br>
                                    <small class="text-danger">
                                        <i class="bi bi-exclamation-circle"></i> Eliminado
                                    </small>
                                </td>
                                <td>{{ $user->ci }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $user->departamento }}</span>
                                </td>
                                <td>
                                    <i class="bi bi-envelope"></i> {{ $user->email }}
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $user->deleted_at->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                                class="btn btn-sm btn-success btn-action" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#restoreModal{{ $user->id }}"
                                                title="Restaurar">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger btn-action" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#forceDeleteModal{{ $user->id }}"
                                                title="Eliminar Permanentemente">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>

                                    <!-- Modal de Restaurar -->
                                    <div class="modal fade" id="restoreModal{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-arrow-counterclockwise"></i> Restaurar Usuario
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Está seguro que desea restaurar este usuario?</p>
                                                    <div class="alert alert-info">
                                                        <strong>{{ $user->nombre_completo }}</strong><br>
                                                        CI: {{ $user->ci }}<br>
                                                        Email: {{ $user->email }}
                                                    </div>
                                                    <p class="mb-0">
                                                        <i class="bi bi-info-circle"></i> 
                                                        El usuario volverá a estar activo en el sistema.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Cancelar
                                                    </button>
                                                    <form action="{{ route('users.restore', $user->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal de Eliminar Permanentemente -->
                                    <div class="modal fade" id="forceDeleteModal{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-exclamation-triangle"></i> Eliminar Permanentemente
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="alert alert-danger">
                                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                                        <strong>¡ADVERTENCIA!</strong> Esta acción es irreversible.
                                                    </div>
                                                    <p>¿Está seguro que desea eliminar <strong>permanentemente</strong> este usuario?</p>
                                                    <div class="alert alert-warning">
                                                        <strong>{{ $user->nombre_completo }}</strong><br>
                                                        CI: {{ $user->ci }}<br>
                                                        Email: {{ $user->email }}
                                                    </div>
                                                    <p class="text-danger mb-0">
                                                        <i class="bi bi-exclamation-circle"></i> 
                                                        Los datos se eliminarán completamente de la base de datos y no se podrán recuperar.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Cancelar
                                                    </button>
                                                    <form action="{{ route('users.force-delete', $user->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="bi bi-trash-fill"></i> Eliminar Permanentemente
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-3">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

@if(!$users->isEmpty())
<div class="mt-3">
    <p class="text-muted small">
        <i class="bi bi-info-circle"></i> 
        Mostrando {{ $users->count() }} de {{ $users->total() }} usuarios eliminados
    </p>
</div>

<div class="alert alert-info mt-3">
    <h5><i class="bi bi-lightbulb"></i> Información</h5>
    <ul class="mb-0">
        <li><strong>Restaurar:</strong> El usuario volverá a estar activo en el sistema con todos sus datos.</li>
        <li><strong>Eliminar Permanentemente:</strong> Los datos se borrarán completamente de la base de datos y no se podrán recuperar.</li>
    </ul>
</div>
@endif
@endsection