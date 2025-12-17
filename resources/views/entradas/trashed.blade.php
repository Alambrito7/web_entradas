@extends('layouts.app')

@section('title', 'Entradas Eliminadas')

@section('styles')
<style>
    .entrada-card-deleted {
        filter: grayscale(100%);
        opacity: 0.8;
        transition: all 0.3s;
        border: 2px solid #dc3545;
    }
    
    .entrada-card-deleted:hover {
        filter: grayscale(0%);
        opacity: 1;
        transform: scale(1.02);
    }
    
    .deleted-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(220, 53, 69, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
    }
    
    .entrada-img {
        height: 200px;
        object-fit: cover;
    }
    
    .entrada-img-placeholder {
        height: 200px;
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="mb-1"><i class="bi bi-trash-fill text-danger"></i> Entradas Eliminadas</h2>
        <p class="text-muted">Entradas que han sido eliminadas y pueden ser restauradas</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('entradas.index') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Volver a Entradas Activas
        </a>
    </div>
</div>

@if($entradas->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
            <h4 class="mt-3 text-muted">No hay entradas eliminadas</h4>
            <p class="text-muted">Las entradas eliminadas aparecerán aquí y podrán ser restauradas.</p>
            <a href="{{ route('entradas.index') }}" class="btn btn-primary mt-3">
                <i class="bi bi-arrow-left"></i> Volver a Entradas
            </a>
        </div>
    </div>
@else
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i>
        <strong>Importante:</strong> Estas entradas están marcadas como eliminadas. Puedes restaurarlas o eliminarlas permanentemente.
    </div>

    <div class="row g-4">
        @foreach($entradas as $entrada)
            <div class="col-md-4">
                <div class="card entrada-card-deleted h-100">
                    <div class="position-relative">
                        @if($entrada->imagen)
                            <img src="{{ asset('storage/' . $entrada->imagen) }}" 
                                 class="card-img-top entrada-img" 
                                 alt="{{ $entrada->nombre }}">
                        @else
                            <div class="entrada-img-placeholder">
                                <i class="bi bi-calendar-event text-white" style="font-size: 4rem; opacity: 0.3;"></i>
                            </div>
                        @endif
                        
                        <div class="deleted-overlay">
                            <span class="badge bg-danger" style="font-size: 1.2rem; padding: 10px 20px;">
                                <i class="bi bi-trash"></i> ELIMINADA
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $entrada->nombre }}</h5>
                        
                        <div class="mb-2">
                            <span class="badge bg-secondary">{{ $entrada->departamento }}</span>
                            <span class="badge bg-info">
                                {{ $entrada->fecha_evento->format('d/m/Y') }}
                            </span>
                        </div>
                        
                        @if($entrada->santo)
                            <p class="card-text small">
                                <i class="bi bi-star"></i> {{ $entrada->santo }}
                            </p>
                        @endif
                        
                        <p class="card-text small text-muted">
                            <i class="bi bi-calendar-x"></i> 
                            Eliminada: {{ $entrada->deleted_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <div class="d-grid gap-2">
                            <button type="button" 
                                    class="btn btn-success btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#restoreModal{{ $entrada->id }}">
                                <i class="bi bi-arrow-counterclockwise"></i> Restaurar Entrada
                            </button>
                            <button type="button" 
                                    class="btn btn-danger btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#forceDeleteModal{{ $entrada->id }}">
                                <i class="bi bi-trash-fill"></i> Eliminar Permanentemente
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Modal de Restaurar -->
                <div class="modal fade" id="restoreModal{{ $entrada->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title">
                                    <i class="bi bi-arrow-counterclockwise"></i> Restaurar Entrada
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Está seguro que desea restaurar esta entrada?</p>
                                <div class="alert alert-info">
                                    <strong>{{ $entrada->nombre }}</strong><br>
                                    {{ $entrada->departamento }} - {{ $entrada->fecha_evento->format('d/m/Y') }}
                                </div>
                                <p class="mb-0">
                                    <i class="bi bi-info-circle"></i> 
                                    La entrada volverá a estar activa en el sistema con toda su información.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancelar
                                </button>
                                <form action="{{ route('entradas.restore', $entrada->id) }}" method="POST" class="d-inline">
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
                <div class="modal fade" id="forceDeleteModal{{ $entrada->id }}" tabindex="-1">
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
                                <p>¿Está seguro que desea eliminar <strong>permanentemente</strong> esta entrada?</p>
                                <div class="alert alert-warning">
                                    <strong>{{ $entrada->nombre }}</strong><br>
                                    {{ $entrada->departamento }} - {{ $entrada->fecha_evento->format('d/m/Y') }}
                                </div>
                                <p class="text-danger mb-0">
                                    <i class="bi bi-exclamation-circle"></i> 
                                    Se eliminarán todos los datos incluyendo la imagen. Esta acción no se puede deshacer.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancelar
                                </button>
                                <form action="{{ route('entradas.force-delete', $entrada->id) }}" method="POST" class="d-inline">
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
            </div>
        @endforeach
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4">
        {{ $entradas->links('pagination::bootstrap-5') }}
    </div>

    <div class="mt-3">
        <p class="text-muted small">
            <i class="bi bi-info-circle"></i> 
            Mostrando {{ $entradas->count() }} de {{ $entradas->total() }} entradas eliminadas
        </p>
    </div>
@endif
@endsection