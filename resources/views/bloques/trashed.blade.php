@extends('layouts.app')

@section('title', 'Bloques Eliminados')

@section('styles')
<style>
    .trashed-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        opacity: 0.8;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .trashed-card:hover {
        opacity: 1;
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .bloque-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #6c757d;
        filter: grayscale(100%);
    }

    .bloque-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .bloque-body {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .deleted-info {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .no-image-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 200px;
        background: #6c757d;
        color: white;
        font-size: 4rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-trash"></i> Bloques Eliminados</h2>
            <p class="text-muted">Gestiona los bloques que han sido eliminados del sistema</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('bloques.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver a Bloques
            </a>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i>
        <strong>Información:</strong> Los bloques eliminados se pueden restaurar o eliminar permanentemente.
    </div>

    @if($bloques->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 5rem; color: #ccc;"></i>
            <h3 class="mt-3 text-muted">No hay bloques eliminados</h3>
            <p class="text-muted">La papelera de bloques está vacía</p>
            <a href="{{ route('bloques.index') }}" class="btn btn-primary btn-lg mt-3">
                <i class="bi bi-arrow-left"></i> Volver a Bloques
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach($bloques as $bloque)
                <div class="col-lg-4 col-md-6">
                    <div class="trashed-card">
                        <!-- Imagen -->
                        <div class="bloque-image">
                            @if($bloque->foto_principal)
                                <img src="{{ Storage::url($bloque->foto_principal) }}" alt="{{ $bloque->nombre }}">
                            @else
                                <div class="no-image-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Contenido -->
                        <div class="bloque-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="mb-0">{{ $bloque->nombre }}</h5>
                                <span class="badge bg-danger">
                                    <i class="bi bi-trash"></i> Eliminado
                                </span>
                            </div>

                            @if($bloque->fraternidad)
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-people-fill"></i>
                                    {{ $bloque->fraternidad->nombre }}
                                </p>
                            @endif

                            <div class="deleted-info">
                                <small>
                                    <strong><i class="bi bi-calendar-x"></i> Eliminado:</strong><br>
                                    {{ $bloque->deleted_at->format('d/m/Y H:i') }}<br>
                                    <span class="text-muted">{{ $bloque->deleted_at->diffForHumans() }}</span>
                                </small>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-people"></i>
                                    <strong>{{ $bloque->integrantes_aproximados }}</strong> integrantes
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="bi bi-person-badge"></i>
                                    <strong>{{ $bloque->responsables_count }}</strong> responsables
                                </small>
                            </div>

                            <!-- Acciones -->
                            <div class="mt-auto">
                                <div class="d-grid gap-2">
                                    <button type="button" 
                                            class="btn btn-sm btn-success" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#restoreModal{{ $bloque->id }}">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                                    </button>
                                    
                                    <button type="button" 
                                            class="btn btn-sm btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#forceDeleteModal{{ $bloque->id }}">
                                        <i class="bi bi-trash-fill"></i> Eliminar Permanentemente
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de Restauración -->
                <div class="modal fade" id="restoreModal{{ $bloque->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title">
                                    <i class="bi bi-arrow-counterclockwise"></i> Restaurar Bloque
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Está seguro que desea restaurar el bloque <strong>{{ $bloque->nombre }}</strong>?</p>
                                <p class="text-muted mb-0">
                                    <small>El bloque volverá a estar disponible en el sistema.</small>
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <form action="{{ route('bloques.restore', $bloque->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de Eliminación Permanente -->
                <div class="modal fade" id="forceDeleteModal{{ $bloque->id }}" tabindex="-1">
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
                                    <strong>¡ADVERTENCIA!</strong> Esta acción NO se puede deshacer.
                                </div>
                                
                                <p>¿Está seguro que desea eliminar permanentemente el bloque <strong>{{ $bloque->nombre }}</strong>?</p>
                                
                                <p class="text-danger mb-0">
                                    <small>Todos los datos del bloque, incluida su foto, serán eliminados permanentemente.</small>
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <form action="{{ route('bloques.force-delete', $bloque->id) }}" method="POST">
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
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $bloques->links('pagination::bootstrap-5') }}
        </div>
    @endif

    <!-- Estadísticas de la Papelera -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Estadísticas de la Papelera</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="p-3">
                                <h3 class="text-secondary">{{ $bloques->total() }}</h3>
                                <p class="text-muted mb-0">Bloques Eliminados</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <h3 class="text-warning">{{ $bloques->sum('integrantes_aproximados') }}</h3>
                                <p class="text-muted mb-0">Integrantes Afectados</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <h3 class="text-info">{{ \App\Models\Bloque::count() }}</h3>
                                <p class="text-muted mb-0">Bloques Activos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection