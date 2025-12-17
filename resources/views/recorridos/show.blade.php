@extends('layouts.app')

@section('title', 'Ver Recorrido')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .recorrido-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px 15px 0 0;
        margin-bottom: 2rem;
    }

    .recorrido-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .entrada-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 25px;
        display: inline-block;
        margin-bottom: 1rem;
    }

    .info-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
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

    #map {
        height: 500px;
        width: 100%;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .punto-item {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-left: 4px solid #667eea;
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .punto-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .punto-number {
        background: #667eea;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .punto-content h6 {
        color: #667eea;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .coordenadas-badge {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.85rem;
        display: inline-block;
        margin-right: 0.5rem;
    }

    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 15px;
        text-align: center;
        margin-bottom: 1rem;
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        display: block;
    }

    .stats-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .sticky-actions {
        position: sticky;
        top: 20px;
    }

    .action-btn {
        width: 100%;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .timeline-connector {
        border-left: 3px dashed #667eea;
        height: 30px;
        margin-left: 18px;
        margin-top: -0.5rem;
        margin-bottom: -0.5rem;
    }

    .custom-marker {
        background: transparent;
        border: none;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="recorrido-header">
        <span class="entrada-badge">
            <i class="bi bi-calendar-event"></i> {{ $recorrido->entrada->nombre }}
        </span>
        <h1 class="recorrido-title">
            {{ $recorrido->nombre ?: 'Recorrido de ' . $recorrido->entrada->nombre }}
        </h1>
        @if($recorrido->descripcion)
            <p class="mb-0 opacity-90">{{ $recorrido->descripcion }}</p>
        @endif
    </div>

    <div class="row">
        <!-- Contenido Principal -->
        <div class="col-lg-8">
            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="stats-card">
                        <span class="stats-number">{{ $recorrido->puntos->count() }}</span>
                        <span class="stats-label">
                            <i class="bi bi-geo-alt-fill"></i> Puntos del Recorrido
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stats-card">
                        <span class="stats-number">{{ number_format($recorrido->distancia_aproximada ?? 0, 2) }}</span>
                        <span class="stats-label">
                            <i class="bi bi-rulers"></i> Kilómetros Aproximados
                        </span>
                    </div>
                </div>
            </div>

            <!-- Mapa del Recorrido -->
            <div class="info-card">
                <div class="info-card-header">
                    <i class="bi bi-map-fill"></i> Mapa del Recorrido
                </div>
                <div class="info-card-body p-0">
                    <div id="map"></div>
                </div>
            </div>

            <!-- Puntos del Recorrido -->
            <div class="info-card">
                <div class="info-card-header">
                    <i class="bi bi-list-ol"></i> Puntos del Recorrido ({{ $recorrido->puntos->count() }})
                </div>
                <div class="info-card-body">
                    @foreach($recorrido->puntos as $punto)
                        <div class="d-flex align-items-start punto-item">
                            <div class="punto-number">{{ $punto->orden }}</div>
                            <div class="punto-content flex-grow-1">
                                <h6 class="mb-1">{{ $punto->nombre }}</h6>
                                
                                @if($punto->descripcion)
                                    <p class="text-muted mb-2 small">{{ $punto->descripcion }}</p>
                                @endif
                                
                                <div>
                                    <span class="coordenadas-badge">
                                        <i class="bi bi-geo-alt"></i> {{ $punto->latitud }}, {{ $punto->longitud }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        @if(!$loop->last)
                            <div class="timeline-connector"></div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Información de la Entrada -->
            <div class="info-card">
                <div class="info-card-header">
                    <i class="bi bi-calendar-event"></i> Información de la Entrada
                </div>
                <div class="info-card-body">
                    <h5 class="text-primary mb-3">
                        <a href="{{ route('entradas.show', $recorrido->entrada) }}" class="text-decoration-none">
                            {{ $recorrido->entrada->nombre }}
                        </a>
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <strong><i class="bi bi-geo-alt"></i> Departamento:</strong><br>
                            <span class="text-muted">{{ $recorrido->entrada->departamento }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="bi bi-calendar3"></i> Fecha del Evento:</strong><br>
                            <span class="text-muted">{{ $recorrido->entrada->fecha_evento->format('d/m/Y') }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong><i class="bi bi-info-circle"></i> Estado:</strong><br>
                            {!! $recorrido->entrada->estado_badge !!}
                        </div>
                        @if($recorrido->entrada->santo)
                            <div class="col-md-6 mb-2">
                                <strong><i class="bi bi-star"></i> Santo Patrono:</strong><br>
                                <span class="text-muted">{{ $recorrido->entrada->santo }}</span>
                            </div>
                        @endif
                    </div>
                    
                    @if($recorrido->entrada->descripcion)
                        <div class="mt-3">
                            <strong>Descripción:</strong>
                            <p class="text-muted mb-0">{{ $recorrido->entrada->descripcion }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar con Acciones -->
        <div class="col-lg-4">
            <div class="sticky-actions">
                <h5 class="text-primary mb-3"><i class="bi bi-gear"></i> Acciones</h5>
                
                <a href="{{ route('recorridos.edit', $recorrido) }}" class="btn btn-warning action-btn">
                    <i class="bi bi-pencil"></i> Editar Recorrido
                </a>
                
                <button type="button" class="btn btn-danger action-btn" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-trash"></i> Eliminar Recorrido
                </button>
                
                <hr>
                
                <a href="{{ route('recorridos.index') }}" class="btn btn-outline-secondary action-btn">
                    <i class="bi bi-arrow-left"></i> Volver al Listado
                </a>
                
                <a href="{{ route('entradas.show', $recorrido->entrada) }}" class="btn btn-outline-primary action-btn">
                    <i class="bi bi-calendar-event"></i> Ver Entrada
                </a>

                <hr>
                
                <div class="info-card">
                    <div class="info-card-body">
                        <h6 class="text-primary mb-3"><i class="bi bi-info-circle"></i> Información</h6>
                        
                        <p class="small mb-2">
                            <strong>Creado:</strong><br>
                            <span class="text-muted">{{ $recorrido->created_at->format('d/m/Y H:i') }}</span>
                        </p>
                        
                        <p class="small mb-0">
                            <strong>Última actualización:</strong><br>
                            <span class="text-muted">{{ $recorrido->updated_at->format('d/m/Y H:i') }}</span>
                        </p>
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
                <p>¿Está seguro que desea eliminar este recorrido?</p>
                <p class="text-muted mb-0">
                    <small>Esta acción se puede revertir desde la papelera.</small>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('recorridos.destroy', $recorrido) }}" method="POST" class="d-inline">
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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Inicializar el mapa
    const map = L.map('map');

    // Añadir capa de tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);

    // Datos de los puntos del recorrido
    const puntos = @json($recorrido->puntos);
    
    if (puntos.length > 0) {
        const markers = [];
        const latlngs = [];

        // Crear marcadores para cada punto
        puntos.forEach((punto, index) => {
            const lat = parseFloat(punto.latitud);
            const lng = parseFloat(punto.longitud);
            latlngs.push([lat, lng]);

            // Crear marcador personalizado
            const marker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: `<div style="background: #667eea; color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 3px solid white; box-shadow: 0 3px 8px rgba(0,0,0,0.3); font-size: 1.1rem;">${punto.orden}</div>`,
                    iconSize: [35, 35],
                    iconAnchor: [17.5, 17.5]
                })
            }).addTo(map);

            // Popup con información del punto
            const popupContent = `
                <div style="min-width: 200px;">
                    <h6 style="color: #667eea; margin-bottom: 0.5rem;"><strong>Punto ${punto.orden}</strong></h6>
                    <p style="margin-bottom: 0.5rem;"><strong>${punto.nombre}</strong></p>
                    ${punto.descripcion ? `<p style="font-size: 0.9rem; color: #666; margin-bottom: 0.5rem;">${punto.descripcion}</p>` : ''}
                    <p style="font-size: 0.85rem; color: #999; margin: 0;">
                        <i class="bi bi-geo-alt"></i> ${punto.latitud}, ${punto.longitud}
                    </p>
                </div>
            `;
            marker.bindPopup(popupContent);

            markers.push(marker);
        });

        // Dibujar la línea del recorrido
        const polyline = L.polyline(latlngs, {
            color: '#667eea',
            weight: 4,
            opacity: 0.8,
            dashArray: '10, 5',
            lineJoin: 'round'
        }).addTo(map);

        // Ajustar la vista del mapa para mostrar todo el recorrido
        map.fitBounds(polyline.getBounds(), { padding: [50, 50] });

        // Agregar flechas direccionales en el polyline
        const decorator = L.polylineDecorator(polyline, {
            patterns: [
                {
                    offset: '10%',
                    repeat: 100,
                    symbol: L.Symbol.arrowHead({
                        pixelSize: 12,
                        polygon: false,
                        pathOptions: {
                            stroke: true,
                            weight: 3,
                            color: '#667eea',
                            opacity: 0.8
                        }
                    })
                }
            ]
        }).addTo(map);

    } else {
        // Si no hay puntos, centrar en Bolivia
        map.setView([-16.5000, -68.1500], 6);
    }
</script>
@endsection