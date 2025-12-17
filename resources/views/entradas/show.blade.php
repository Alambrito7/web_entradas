@extends('layouts.app')

@section('title', $entrada->nombre)

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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

    .status-planificada {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .status-en_curso {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .status-finalizada {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }

    #mapShow {
        height: 400px;
        width: 100%;
        border-radius: 10px;
        border: 2px solid #dee2e6;
    }

    .entrada-imagen {
        width: 100%;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
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
                <span class="badge badge-custom status-{{ $entrada->status }}">
                    @if($entrada->status === 'planificada')
                        <i class="bi bi-calendar-check"></i> Planificada
                    @elseif($entrada->status === 'en_curso')
                        <i class="bi bi-broadcast"></i> En Curso
                    @else
                        <i class="bi bi-check-circle"></i> Finalizada
                    @endif
                </span>
                <span class="badge badge-custom" style="background: rgba(255,255,255,0.2);">
                    <i class="bi bi-geo-alt"></i> {{ $entrada->departamento }}
                </span>
            </div>
            <a href="{{ route('entradas.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
        <h1 class="display-4 fw-bold mb-3">{{ $entrada->nombre }}</h1>
        
        @if($entrada->santo)
            <p class="lead mb-2">
                <i class="bi bi-star-fill"></i> {{ $entrada->santo }}
            </p>
        @endif
        
        <p class="mb-0">
            <i class="bi bi-calendar-event"></i> 
            Fecha del Evento: {{ $entrada->fecha_evento->format('d/m/Y') }}
        </p>
        
        @if($entrada->fecha_fundacion)
            <p class="mb-0">
                <i class="bi bi-clock-history"></i> 
                Fundada: {{ $entrada->fecha_fundacion->format('d/m/Y') }}
            </p>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Imagen -->
        @if($entrada->imagen)
        <div class="info-card">
            <div class="info-card-body">
                <img src="{{ asset('storage/' . $entrada->imagen) }}" 
                     class="entrada-imagen" 
                     alt="{{ $entrada->nombre }}">
            </div>
        </div>
        @endif

        <!-- Descripción -->
        @if($entrada->descripcion)
        <div class="info-card">
            <div class="info-card-header">
                <i class="bi bi-quote"></i> Descripción
            </div>
            <div class="info-card-body">
                <p class="mb-0">{{ $entrada->descripcion }}</p>
            </div>
        </div>
        @endif

        <!-- Historia -->
        @if($entrada->historia)
        <div class="info-card">
            <div class="info-card-header">
                <i class="bi bi-book"></i> Historia
            </div>
            <div class="info-card-body">
                <p class="mb-0" style="white-space: pre-line;">{{ $entrada->historia }}</p>
            </div>
        </div>
        @endif

        <!-- Información Detallada -->
        <div class="info-card">
            <div class="info-card-header">
                <i class="bi bi-info-circle"></i> Información Detallada
            </div>
            <div class="info-card-body">
                <div class="info-row">
                    <div class="info-label">
                        <i class="bi bi-geo-alt"></i> Departamento
                    </div>
                    <div class="info-value">
                        <span class="badge bg-primary">{{ $entrada->departamento }}</span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">
                        <i class="bi bi-calendar3"></i> Fecha del Evento
                    </div>
                    <div class="info-value">
                        {{ $entrada->fecha_evento->translatedFormat('l, d \\d\\e F \\d\\e Y') }}
                    </div>
                </div>

                @if($entrada->fecha_fundacion)
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-clock-history"></i> Fecha de Fundación
                        </div>
                        <div class="info-value">
                            {{ $entrada->fecha_fundacion->format('d/m/Y') }}
                            <small class="text-muted">({{ $entrada->fecha_fundacion->age }} años)</small>
                        </div>
                    </div>
                @endif

                @if($entrada->santo)
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-star"></i> Santo/Virgen
                        </div>
                        <div class="info-value">{{ $entrada->santo }}</div>
                    </div>
                @endif

                <div class="info-row">
                    <div class="info-label">
                        <i class="bi bi-flag"></i> Estado
                    </div>
                    <div class="info-value">
                        <span class="badge status-{{ $entrada->status }}">
                            @if($entrada->status === 'planificada')
                                Planificada
                            @elseif($entrada->status === 'en_curso')
                                En Curso
                            @else
                                Finalizada
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mapa de Ubicación -->
        @if($entrada->tiene_ubicacion)
        <div class="info-card">
            <div class="info-card-header">
                <i class="bi bi-map"></i> Ubicación del Evento
            </div>
            <div class="info-card-body">
                <div id="mapShow"></div>
                <div class="mt-3">
                    <p class="mb-1"><strong>Coordenadas:</strong></p>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-geo"></i> 
                        Latitud: {{ $entrada->latitud }} | Longitud: {{ $entrada->longitud }}
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar con acciones -->
    <div class="col-lg-4">
        <div class="sticky-actions">
            <h5 class="text-primary mb-3"><i class="bi bi-gear"></i> Acciones</h5>
            
            <a href="{{ route('entradas.edit', $entrada) }}" class="btn btn-warning action-btn">
                <i class="bi bi-pencil"></i> Editar Entrada
            </a>
            
            <button type="button" class="btn btn-danger action-btn" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="bi bi-trash"></i> Eliminar Entrada
            </button>
            
            <hr>
            
            <a href="{{ route('entradas.index') }}" class="btn btn-outline-secondary action-btn">
                <i class="bi bi-arrow-left"></i> Volver al Listado
            </a>

            <hr>
            
            <h6 class="text-primary mb-2"><i class="bi bi-info-circle"></i> Información</h6>
            <p class="small text-muted mb-2">
                <strong>Creado:</strong><br>
                {{ $entrada->created_at->format('d/m/Y H:i') }}
            </p>
            <p class="small text-muted mb-0">
                <strong>Actualizado:</strong><br>
                {{ $entrada->updated_at->format('d/m/Y H:i') }}
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
                <p>¿Está seguro que desea eliminar esta entrada folclórica?</p>
                <div class="alert alert-warning">
                    <strong>{{ $entrada->nombre }}</strong><br>
                    {{ $entrada->departamento }} - {{ $entrada->fecha_evento->format('d/m/Y') }}
                </div>
                <p class="text-muted small mb-0">
                    <i class="bi bi-info-circle"></i> 
                    La entrada se moverá a la papelera y podrá ser restaurada posteriormente.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <form action="{{ route('entradas.destroy', $entrada) }}" method="POST" class="d-inline">
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

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@if($entrada->tiene_ubicacion)
<script>
    // Inicializar mapa en modo vista
    const mapShow = L.map('mapShow').setView([{{ $entrada->latitud }}, {{ $entrada->longitud }}], 14);
    
    // Agregar capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(mapShow);
    
    // Agregar marcador
    const marker = L.marker([{{ $entrada->latitud }}, {{ $entrada->longitud }}]).addTo(mapShow);
    
    // Agregar popup con información
    marker.bindPopup(`
        <strong>{{ $entrada->nombre }}</strong><br>
        {{ $entrada->departamento }}<br>
        {{ $entrada->fecha_evento->format('d/m/Y') }}
    `).openPopup();
</script>
@endif
@endsection