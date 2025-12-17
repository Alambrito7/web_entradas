@extends('layouts.app')

@section('title', 'Danzas Eliminadas')

@section('styles')
<style>
    .danza-card-deleted {
        filter: grayscale(100%);
        opacity: 0.8;
        transition: all 0.3s;
        border: 2px solid #dc3545;
    }
    .danza-card-deleted:hover {
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
        background: rgba(220, 53, 69, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="mb-1"><i class="bi bi-trash-fill text-danger"></i> Danzas Eliminadas</h2>
        <p class="text-muted">Danzas que han sido eliminadas y pueden ser restauradas</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('danzas.index') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Volver a Danzas Activas
        </a>
    </div>
</div>

@if($danzas->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
            <h4 class="mt-3 text-muted">No hay danzas eliminadas</h4>
            <p class="text-muted">Las danzas eliminadas aparecerán aquí y podrán ser restauradas.</p>
            <a href="{{ route('danzas.index') }}" class="btn btn-primary mt-3">
                <i class="bi bi-arrow-left"></i> Volver a Danzas
            </a>
        </div>
    </div>
@else
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i>
        <strong>Importante:</strong> Estas danzas están marcadas como eliminadas. Puedes restaurarlas o eliminarlas permanentemente.
    </div>

    <div class="row g-4">
        @foreach($danzas as $danza)
            <div class="col-md-4">
                <div class="card danza-card-deleted h-100">
                    <div class="position-relative">
                        @php
                            $primeraFoto = $danza->multimedia->where('tipo', 'Foto')->first();
                        @endphp
                        
                        @if($primeraFoto && $primeraFoto->archivo)
                            <img src="{{ asset('storage/' . $primeraFoto->archivo) }}" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;"
                                 alt="{{ $danza->nombre }}">
                        @elseif($primeraFoto && $primeraFoto->url)
                            <img src="{{ $primeraFoto->url }}" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;"
                                 alt="{{ $danza->nombre }}">
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" 
                                 style="height: 200px;">
                                <i class="bi bi-music-note-beamed" style="font-size: 4rem; color: #ccc;"></i>
                            </div>
                        @endif
                        
                        <div class="deleted-overlay">
                            <span class="badge bg-danger" style="font-size: 1.2rem; padding: 10px 20px;">
                                <i class="bi bi-trash"></i> ELIMINADA
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $danza->nombre }}</h5>
                        
                        <div class="mb-2">
                            <span class="badge bg-secondary">{{ $danza->categoria }}</span>
                            <span class="badge bg-info">{{ $danza->departamento_principal }}</span>
                        </div>
                        
                        <p class="card-text small text-muted">
                            <i class="bi bi-calendar-x"></i> 
                            Eliminada: {{ $danza->deleted_at->format('d/m/Y H:i') }}
                        </p>
                        
                        @if($danza->descripcion_corta)
                            <p class="card-text small">
                                {{ Str::limit($danza->descripcion_corta, 100) }}
                            </p>
                        @endif
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <div class="d-grid gap-2">
                            <button type="button" 
                                    class="btn btn-success btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#restoreModal{{ $danza->id }}">
                                <i class="bi bi-arrow-counterclockwise"></i> Restaurar Danza
                            </button>
                            <button type="button" 
                                    class="btn btn-danger btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#forceDeleteModal{{ $danza->id }}">
                                <i class="bi bi-trash-fill"></i> Eliminar Permanentemente
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Modal de Restaurar -->
                <div class="modal fade" id="restoreModal{{ $danza->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title">
                                    <i class="bi bi-arrow-counterclockwise"></i> Restaurar Danza
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Está seguro que desea restaurar esta danza?</p>
                                <div class="alert alert-info">
                                    <strong>{{ $danza->nombre }}</strong><br>
                                    {{ $danza->categoria }} - {{ $danza->departamento_principal }}
                                </div>
                                <p class="mb-0">
                                    <i class="bi bi-info-circle"></i> 
                                    La danza volverá a estar activa en el sistema con toda su información.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancelar
                                </button>
                                <form action="{{ route('danzas.restore', $danza->id) }}" method="POST" class="d-inline">
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
                <div class="modal fade" id="forceDeleteModal{{ $danza->id }}" tabindex="-1">
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
                                <p>¿Está seguro que desea eliminar <strong>permanentemente</strong> esta danza?</p>
                                <div class="alert alert-warning">
                                    <strong>{{ $danza->nombre }}</strong><br>
                                    {{ $danza->categoria }} - {{ $danza->departamento_principal }}
                                </div>
                                <p class="text-danger mb-0">
                                    <i class="bi bi-exclamation-circle"></i> 
                                    Se eliminarán todos los datos asociados: personajes, vestimentas, multimedia y documentos. Esta acción no se puede deshacer.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancelar
                                </button>
                                <form action="{{ route('danzas.force-delete', $danza->id) }}" method="POST" class="d-inline">
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
        {{ $danzas->links('pagination::bootstrap-5') }}
    </div>

    <div class="mt-3">
        <p class="text-muted small">
            <i class="bi bi-info-circle"></i> 
            Mostrando {{ $danzas->count() }} de {{ $danzas->total() }} danzas eliminadas
        </p>
    </div>
@endif
@endsection