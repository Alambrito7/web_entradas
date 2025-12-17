@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h2><i class="bi bi-people-fill"></i> Gestión de Usuarios</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('users.trashed') }}" class="btn btn-secondary me-2">
            <i class="bi bi-trash"></i> Usuarios Eliminados
        </a>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Usuario
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre Completo</th>
                        <th>CI</th>
                        <th>Departamento</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <strong>{{ $user->nombre_completo }}</strong>
                            </td>
                            <td>{{ $user->ci }}</td>
                            <td>
                                <span class="badge bg-info">{{ $user->departamento }}</span>
                            </td>
                            <td>
                                <i class="bi bi-telephone"></i> {{ $user->telefono }}
                            </td>
                            <td>
                                <i class="bi bi-envelope"></i> {{ $user->email }}
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('users.show', $user) }}" 
                                       class="btn btn-sm btn-info btn-action" 
                                       title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}" 
                                       class="btn btn-sm btn-warning btn-action" 
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger btn-action" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $user->id }}"
                                            title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Modal de Confirmación -->
                                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-exclamation-triangle"></i> Confirmar Eliminación
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>¿Está seguro que desea eliminar al usuario?</p>
                                                <p class="mb-0"><strong>{{ $user->nombre_completo }}</strong></p>
                                                <p class="text-muted small">CI: {{ $user->ci }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Cancelar
                                                </button>
                                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="bi bi-trash"></i> Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-2">No hay usuarios registrados</p>
                                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Crear el primer usuario
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-3">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<div class="mt-3">
    <p class="text-muted small">
        <i class="bi bi-info-circle"></i> 
        Mostrando {{ $users->count() }} de {{ $users->total() }} usuarios
    </p>
</div>
@endsection