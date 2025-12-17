@extends('layouts.app')

@section('title', $fraternidade->nombre)

@section('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 40px;
        margin-bottom: 30px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,101.3C1248,85,1344,75,1392,69.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') bottom;
        background-size: cover;
        opacity: 0.3;
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .info-card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 20px;
        border-radius: 15px;
    }

    .info-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 15px 15px 0 0;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .info-card-body {
        padding: 25px;
    }

    .info-row {
        display: flex;
        padding: 15px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #667eea;
        min-width: 200px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-value {
        flex: 1;
        color: #495057;
    }

    .badge-custom {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .pasante-card {
        background: #f8f9fa;
        border-left: 4px solid #667eea;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        transition: all 0.3s;
    }

    .pasante-card:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .pasante-number {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 10px;
    }

    .entrada-badge {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 10px 15px;
        border-radius: 10px;
        margin-bottom: 10px;
        display: inline-block;
    }

    .sticky-actions {
        position: sticky;
        top: 20px;
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .action-btn {
        width: 100%;
        margin-bottom: 10px;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-content">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                @if($fraternidade->danza)
                    <span class="badge badge-custom" style="background: rgba(255,255,255,0.2);">
                        <i class="bi bi-music-note"></i> {{ $fraternidade->danza->nombre }}
                    </span>
                @endif
            </div>
            <a href="{{ route('fraternidades.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
        <h1 class="display-4 fw-bold mb-3">{{ $fraternidade->nombre }}</h1>
        
        @if($fraternidade->lema)
            <p class="lead fst-italic mb-2">
                <i class="bi bi-quote"></i> {{ $fraternidade->lema }}
            </p>
        @endif
        
        @if($fraternidade->fecha_fundacion)
            <p class="mb-0">
                <i class="bi bi-calendar-event"></i> 
                Fundada: {{ $fraternidade->fecha_fundacion->format('d/m/Y') }}
                ({{ $fraternidade->anios_fundacion }} años de historia)
            </p>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Descripción -->
        @if($fraternidade->descripcion)
        <div class="info-card">
            <div class="info-card-header">
                <i class="bi bi-book"></i> Descripción
            </div>
            <div class="info-card-body">
                <p class="mb-0" style="white-space: pre-line;">{{ $fraternidade->descripcion }}</p>
            </div>
        </div>
        @endif

        <!-- Información Detallada -->
        <div class="info-card">
            <div class="info-card-header">
                <i class="bi bi-info-circle"></i> Información Detallada
            </div>
            <div class="info-card-body">
                @if($fraternidade->danza)
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-music-note-beamed"></i> Danza
                        </div>
                        <div class="info-value">
                            <a href="{{ route('danzas.show', $fraternidade->danza) }}" class="text-decoration-none">
                                <span class="badge bg-primary">{{ $fraternidade->danza->nombre }}</span>
                            </a>
                        </div>
                    </div>
                @endif

                @if($fraternidade->fecha_fundacion)
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-calendar3"></i> Fecha de Fundación
                        </div>
                        <div class="info-value">
                            {{ $fraternidade->fecha_fundacion->format('d/m/Y') }}
                            <small class="text-muted">({{ $fraternidade->anios_fundacion }} años)</small>
                        </div>
                    </div>
                @endif

                @if($fraternidade->telefono)
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-telephone"></i> Teléfono
                        </div>
                        <div class="info-value">
                            <a href="tel:{{ $fraternidade->telefono }}" class="text-decoration-none">
                                {{ $fraternidade->telefono }}
                            </a>
                        </div>
                    </div>
                @endif

                @if($fraternidade->correo_electronico)
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-envelope"></i> Correo Electrónico
                        </div>
                        <div class="info-value">
                            <a href="mailto:{{ $fraternidade->correo_electronico }}" class="text-decoration-none">
                                {{ $fraternidade->correo_electronico }}
                            </a>
                        </div>
                    </div>
                @endif

                @if($fraternidade->lema)
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-award"></i> Lema
                        </div>
                        <div class="info-value fst-italic">
                            "{{ $fraternidade->lema }}"
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pasantes Actuales -->
        @if($fraternidade->pasantes->count() > 0)
        <div class="info-card">
            <div class="info-card-header">
                <i class="bi bi-person-badge"></i> Pasantes Actuales 
                <span class="badge bg-light text-dark ms-2">{{ $fraternidade->pasantes->count() }}</span>
            </div>
            <div class="info-card-body">
                @foreach($fraternidade->pasantes as $pasante)
                    <div class="pasante-card">
                        <span class="pasante-number">{{ $loop->iteration }}</span>
                        <strong>{{ $pasante->nombre }}</strong>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Entradas Folclóricas -->
        @if($fraternidade->entradas->count() > 0)
        <div class="info-card">
            <div class="info-card-header">
                <i class="bi bi-calendar-event"></i> Entradas Folclóricas 
                <span class="badge bg-light text-dark ms-2">{{ $fraternidade->entradas->count() }}</span>
            </div>
            <div class="info-card-body">
                <p class="text-muted small mb-3">
                    Esta fraternidad participa o ha participado en las siguientes entradas folclóricas:
                </p>
                <div class="row">
                    @foreach($fraternidade->entradas as $entrada)
                        <div class="col-md-6 mb-2">
                            <a href="{{ route('entradas.show', $entrada) }}" class="text-decoration-none">
                                <div class="entrada-badge w-100">
                                    <div><strong>{{ $entrada->nombre }}</strong></div>
                                    <small>
                                        <i class="bi bi-geo-alt"></i> {{ $entrada->departamento }} | 
                                        <i class="bi bi-calendar3"></i> {{ $entrada->fecha_evento->format('d/m/Y') }}
                                    </small>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar con acciones -->
    <div class="col-lg-4">
        <div class="sticky-actions">
            <h5 class="text-primary mb-3"><i class="bi bi-gear"></i> Acciones</h5>
            
            <a href="{{ route('fraternidades.edit', $fraternidade) }}" class="btn btn-warning action-btn">
                <i class="bi bi-pencil"></i> Editar Fraternidad
            </a>
            
            <button type="button" class="btn btn-danger action-btn" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="bi bi-trash"></i> Eliminar Fraternidad
            </button>
            
            <hr>
            
            <a href="{{ route('fraternidades.index') }}" class="btn btn-outline-secondary action-btn">
                <i class="bi bi-arrow-left"></i> Volver al Listado
            </a>

            <hr>
            
            <h6 class="text-primary mb-2"><i class="bi bi-info-circle"></i> Resumen</h6>
            
            <div class="card mb-2">
                <div class="card-body text-center py-2">
                    <h4 class="mb-0 text-primary">{{ $fraternidade->pasantes->count() }}</h4>
                    <small class="text-muted">Pasantes</small>
                </div>
            </div>
            
            <div class="card mb-2">
                <div class="card-body text-center py-2">
                    <h4 class="mb-0 text-success">{{ $fraternidade->entradas->count() }}</h4>
                    <small class="text-muted">Entradas</small>
                </div>
            </div>

            <hr>
            
            <h6 class="text-primary mb-2"><i class="bi bi-clock-history"></i> Información</h6>
            <p class="small text-muted mb-2">
                <strong>Creado:</strong><br>
                {{ $fraternidade->created_at->format('d/m/Y H:i') }}
            </p>
            <p class="small text-muted mb-0">
                <strong>Actualizado:</strong><br>
                {{ $fraternidade->updated_at->format('d/m/Y H:i') }}
            </p>
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
                <p>¿Está seguro que desea eliminar esta fraternidad?</p>
                <div class="alert alert-warning">
                    <strong>{{ $fraternidade->nombre }}</strong>
                    @if($fraternidade->danza)
                        <br><small>Danza: {{ $fraternidade->danza->nombre }}</small>
                    @endif
                </div>
                <p class="text-muted small mb-0">
                    <i class="bi bi-info-circle"></i> 
                    La fraternidad se moverá a la papelera y podrá ser restaurada posteriormente.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <form action="{{ route('fraternidades.destroy', $fraternidade) }}" method="POST" class="d-inline">
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