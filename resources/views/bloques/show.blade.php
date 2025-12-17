@extends('layouts.app')

@section('title', 'Ver Bloque')

@section('styles')
<style>
    .bloque-header {
        position: relative;
        height: 300px;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .bloque-header-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .bloque-header-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        padding: 2rem;
        color: white;
    }

    .info-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .info-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 600;
    }

    .info-card-body {
        padding: 1.5rem;
    }

    .responsable-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        border-left: 4px solid #667eea;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        text-align: center;
        height: 100%;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.8rem;
    }

    .stat-icon.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .stat-icon.success {
        background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        color: white;
    }

    .stat-icon.warning {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .action-btn {
        width: 100%;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border-radius: 10px;
        font-weight: 600;
    }

    .no-image-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 300px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 5rem;
        border-radius: 15px;
    }

    .counter-controls {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-top: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header con imagen -->
    <div class="bloque-header">
        @if($bloque->foto_principal)
            <img src="{{ Storage::url($bloque->foto_principal) }}" alt="{{ $bloque->nombre }}" class="bloque-header-image">
        @else
            <div class="no-image-placeholder">
                <i class="bi bi-image"></i>
            </div>
        @endif
        <div class="bloque-header-overlay">
            <h1 class="mb-2">{{ $bloque->nombre }}</h1>
            @if($bloque->lema)
                <p class="mb-0 fst-italic">"{{ $bloque->lema }}"</p>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Columna Principal -->
        <div class="col-lg-8">
            <!-- Estadísticas Principales -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon primary">
                            <i class="bi bi-people"></i>
                        </div>
                        <h3 class="mb-1">{{ $bloque->integrantes_aproximados }}</h3>
                        <p class="text-muted mb-0">Integrantes</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon success">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h3 class="mb-1">{{ $bloque->responsables->count() }}</h3>
                        <p class="text-muted mb-0">Responsables</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon warning">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <h3 class="mb-1">
                            @if($bloque->fecha_fundacion)
                                {{ $bloque->fecha_fundacion->year }}
                            @else
                                --
                            @endif
                        </h3>
                        <p class="text-muted mb-0">Año de Fundación</p>
                    </div>
                </div>
            </div>

            <!-- Información General -->
            <div class="info-card">
                <div class="info-card-header">
                    <i class="bi bi-info-circle"></i> Información General
                </div>
                <div class="info-card-body">
                    <div class="row">
                        @if($bloque->fraternidad)
                            <div class="col-md-6 mb-3">
                                <strong><i class="bi bi-people-fill text-primary"></i> Fraternidad:</strong><br>
                                <a href="{{ route('fraternidades.show', $bloque->fraternidad) }}">
                                    {{ $bloque->fraternidad->nombre }}
                                </a>
                            </div>
                        @endif

                        <div class="col-md-6 mb-3">
                            <strong><i class="bi bi-calendar-check text-success"></i> Estado:</strong><br>
                            {!! $bloque->estado_badge !!}
                        </div>

                        @if($bloque->fecha_fundacion)
                            <div class="col-md-6 mb-3">
                                <strong><i class="bi bi-calendar-event text-info"></i> Fecha de Fundación:</strong><br>
                                {{ $bloque->fecha_fundacion->format('d/m/Y') }}
                            </div>
                        @endif

                        <div class="col-md-6 mb-3">
                            <strong><i class="bi bi-clock text-warning"></i> Registrado:</strong><br>
                            {{ $bloque->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historia -->
            @if($bloque->historia)
                <div class="info-card">
                    <div class="info-card-header">
                        <i class="bi bi-book"></i> Historia / Reseña
                    </div>
                    <div class="info-card-body">
                        <p class="mb-0" style="white-space: pre-line;">{{ $bloque->historia }}</p>
                    </div>
                </div>
            @endif

            <!-- Responsables -->
            @if($bloque->responsables->count() > 0)
                <div class="info-card">
                    <div class="info-card-header">
                        <i class="bi bi-person-badge"></i> Responsables del Bloque
                    </div>
                    <div class="info-card-body">
                        @foreach($bloque->responsables as $responsable)
                            <div class="responsable-card">
                                <h6 class="mb-2">
                                    <i class="bi bi-person-circle"></i> {{ $responsable->nombre }}
                                </h6>
                                <div class="row">
                                    @if($responsable->telefono)
                                        <div class="col-md-6">
                                            <small class="text-muted">
                                                <i class="bi bi-telephone"></i> {{ $responsable->telefono }}
                                            </small>
                                        </div>
                                    @endif
                                    @if($responsable->email)
                                        <div class="col-md-6">
                                            <small class="text-muted">
                                                <i class="bi bi-envelope"></i> {{ $responsable->email }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Redes Sociales -->
            @if($bloque->facebook || $bloque->youtube)
                <div class="info-card">
                    <div class="info-card-header">
                        <i class="bi bi-share"></i> Redes Sociales
                    </div>
                    <div class="info-card-body">
                        <div class="row">
                            @if($bloque->facebook)
                                <div class="col-md-6 mb-3">
                                    <a href="{{ $bloque->facebook }}" target="_blank" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-facebook"></i> Facebook
                                    </a>
                                </div>
                            @endif
                            @if($bloque->youtube)
                                <div class="col-md-6 mb-3">
                                    <a href="{{ $bloque->youtube }}" target="_blank" class="btn btn-outline-danger w-100">
                                        <i class="bi bi-youtube"></i> YouTube
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar de Acciones -->
        <div class="col-lg-4">
            <div class="info-card sticky-top" style="top: 20px;">
                <div class="info-card-header">
                    <i class="bi bi-gear"></i> Acciones
                </div>
                <div class="info-card-body">
                    <a href="{{ route('bloques.edit', $bloque) }}" class="btn btn-warning action-btn">
                        <i class="bi bi-pencil"></i> Editar Bloque
                    </a>

                    <button type="button" class="btn btn-danger action-btn" 
                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash"></i> Eliminar Bloque
                    </button>

                    <hr>

                    <a href="{{ route('bloques.index') }}" class="btn btn-outline-secondary action-btn">
                        <i class="bi bi-arrow-left"></i> Volver al Listado
                    </a>

                    <!-- Control de Integrantes -->
                    <div class="counter-controls">
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-people"></i> Control de Integrantes
                        </h6>
                        
                        <form action="{{ route('bloques.incrementar-integrantes', $bloque) }}" method="POST" class="mb-2">
                            @csrf
                            <div class="input-group">
                                <input type="number" name="cantidad" class="form-control" value="1" min="1">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-plus-circle"></i> Agregar
                                </button>
                            </div>
                        </form>

                        <form action="{{ route('bloques.decrementar-integrantes', $bloque) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="number" name="cantidad" class="form-control" value="1" min="1">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-dash-circle"></i> Restar
                                </button>
                            </div>
                        </form>
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
                <p>¿Está seguro que desea eliminar el bloque <strong>{{ $bloque->nombre }}</strong>?</p>
                <p class="text-muted mb-0">
                    <small>Esta acción se puede revertir desde la papelera.</small>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('bloques.destroy', $bloque) }}" method="POST">
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
@endsection