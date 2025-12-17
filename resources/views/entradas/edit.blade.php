@extends('layouts.app')

@section('title', 'Editar Entrada')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    .section-card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 20px;
    }

    .section-header {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 0.375rem 0.375rem 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-title {
        font-weight: 700;
        margin: 0;
        font-size: 1.1rem;
    }

    .pill {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.2);
        font-size: 11px;
        font-weight: 700;
    }

    .section-body {
        padding: 25px;
        background: white;
    }

    #map {
        height: 450px;
        width: 100%;
        border-radius: 8px;
        border: 2px solid #dee2e6;
        z-index: 1;
    }

    .map-instructions {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .map-instructions i {
        color: #f59e0b;
    }

    .coordinates-display {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
    }

    .preview-image {
        max-width: 100%;
        max-height: 250px;
        border-radius: 8px;
        margin-top: 15px;
        border: 2px solid #dee2e6;
    }

    .current-image {
        max-width: 300px;
        border-radius: 8px;
        border: 2px solid #dee2e6;
    }

    .sticky-actions {
        position: sticky;
        bottom: 0;
        background: white;
        border-top: 2px solid #dee2e6;
        padding: 20px;
        margin-top: 30px;
        box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.1);
        z-index: 10;
        border-radius: 8px;
    }

    .required::after {
        content: " *";
        color: red;
    }
</style>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <h2><i class="bi bi-pencil-square"></i> Editar Entrada: {{ $entrada->nombre }}</h2>
        <p class="text-muted">Actualice la información de la festividad cultural</p>
    </div>
</div>

<form action="{{ route('entradas.update', $entrada) }}" method="POST" enctype="multipart/form-data" id="entradaForm">
    @csrf
    @method('PUT')

    <!-- Información Básica -->
    <section class="card section-card">
        <div class="section-header">
            <h5 class="section-title"><i class="bi bi-info-circle"></i> Información Básica</h5>
            <span class="pill">Obligatorio</span>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label required">Nombre de la Entrada</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                           value="{{ old('nombre', $entrada->nombre) }}" 
                           placeholder="Ej: Gran Poder, Carnaval de Oruro, Urkupiña..." required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Departamento</label>
                    <select name="departamento" id="departamento" 
                            class="form-select @error('departamento') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        @foreach($departamentos as $dep)
                            <option value="{{ $dep }}" {{ old('departamento', $entrada->departamento) == $dep ? 'selected' : '' }}>
                                {{ $dep }}
                            </option>
                        @endforeach
                    </select>
                    @error('departamento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label required">Estado</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        <option value="planificada" {{ old('status', $entrada->status) == 'planificada' ? 'selected' : '' }}>
                            Planificada
                        </option>
                        <option value="en_curso" {{ old('status', $entrada->status) == 'en_curso' ? 'selected' : '' }}>
                            En Curso
                        </option>
                        <option value="finalizada" {{ old('status', $entrada->status) == 'finalizada' ? 'selected' : '' }}>
                            Finalizada
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Fecha del Evento</label>
                    <input type="date" name="fecha_evento" 
                           class="form-control @error('fecha_evento') is-invalid @enderror" 
                           value="{{ old('fecha_evento', $entrada->fecha_evento->format('Y-m-d')) }}" required>
                    @error('fecha_evento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Fecha de Fundación</label>
                    <input type="date" name="fecha_fundacion" 
                           class="form-control @error('fecha_fundacion') is-invalid @enderror" 
                           value="{{ old('fecha_fundacion', $entrada->fecha_fundacion ? $entrada->fecha_fundacion->format('Y-m-d') : '') }}">
                    <small class="form-text text-muted">Opcional - Fecha en que se fundó la festividad</small>
                    @error('fecha_fundacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Santo o Virgen (Patrono)</label>
                    <input type="text" name="santo" 
                           class="form-control @error('santo') is-invalid @enderror" 
                           value="{{ old('santo', $entrada->santo) }}" 
                           placeholder="Ej: Virgen de Urkupiña, Señor del Gran Poder...">
                    @error('santo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" 
                              rows="3" placeholder="Descripción breve de la entrada folclórica">{{ old('descripcion', $entrada->descripcion) }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    <!-- Historia -->
    <section class="card section-card">
        <div class="section-header">
            <h5 class="section-title"><i class="bi bi-book"></i> Historia</h5>
            <span class="pill">Opcional</span>
        </div>
        <div class="section-body">
            <div class="mb-3">
                <label class="form-label">Historia de la Entrada</label>
                <textarea name="historia" class="form-control @error('historia') is-invalid @enderror" 
                          rows="6" placeholder="Origen, evolución histórica, contexto cultural...">{{ old('historia', $entrada->historia) }}</textarea>
                @error('historia')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </section>

    <!-- Localizador con Mapa -->
    <section class="card section-card">
        <div class="section-header">
            <h5 class="section-title"><i class="bi bi-geo-alt"></i> Localizador (Mapa)</h5>
            <span class="pill">Opcional</span>
        </div>
        <div class="section-body">
            <div class="map-instructions">
                <p class="mb-2">
                    <i class="bi bi-info-circle"></i> 
                    <strong>Instrucciones:</strong>
                </p>
                <ul class="mb-0 small">
                    <li>El mapa muestra la ubicación actual (si existe)</li>
                    <li>Haga clic en el mapa para cambiar la ubicación</li>
                    <li>Puede arrastrar el marcador para ajustar la posición</li>
                    <li>Cambiar el departamento actualizará la vista del mapa</li>
                </ul>
            </div>

            <div id="map"></div>

            <div class="coordinates-display">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label small">Latitud:</label>
                        <input type="text" id="latitud_display" class="form-control form-control-sm" readonly 
                               value="{{ $entrada->latitud }}" placeholder="Haga clic en el mapa">
                        <input type="hidden" name="latitud" id="latitud" value="{{ $entrada->latitud }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Longitud:</label>
                        <input type="text" id="longitud_display" class="form-control form-control-sm" readonly 
                               value="{{ $entrada->longitud }}" placeholder="Haga clic en el mapa">
                        <input type="hidden" name="longitud" id="longitud" value="{{ $entrada->longitud }}">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Imagen -->
    <section class="card section-card">
        <div class="section-header">
            <h5 class="section-title"><i class="bi bi-image"></i> Imagen</h5>
            <span class="pill">Opcional</span>
        </div>
        <div class="section-body">
            @if($entrada->imagen)
                <div class="mb-3">
                    <label class="form-label">Imagen Actual:</label>
                    <div>
                        <img src="{{ asset('storage/' . $entrada->imagen) }}" 
                             class="current-image" 
                             alt="{{ $entrada->nombre }}">
                    </div>
                    <small class="text-muted">Suba una nueva imagen para reemplazar la actual</small>
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">{{ $entrada->imagen ? 'Nueva Imagen' : 'Imagen Representativa' }}</label>
                <input type="file" name="imagen" id="imagen" 
                       class="form-control @error('imagen') is-invalid @enderror" 
                       accept="image/*">
                <small class="form-text text-muted">Formatos: JPG, PNG. Tamaño máximo: 2MB</small>
                @error('imagen')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                
                <img id="preview" class="preview-image" style="display: none;" alt="Vista previa">
            </div>
        </div>
    </section>

    <!-- Acciones Sticky -->
    <div class="sticky-actions">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <span class="text-muted small">
                    <i class="bi bi-info-circle"></i> Editando: {{ $entrada->nombre }}
                </span>
            </div>
            <div>
                <a href="{{ route('entradas.show', $entrada) }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save"></i> Actualizar Entrada
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Coordenadas por defecto para cada departamento
    const coordenadasDepartamentos = {
        'La Paz': [-16.5000, -68.1500],
        'Cochabamba': [-17.3935, -66.1570],
        'Santa Cruz': [-17.7833, -63.1821],
        'Oruro': [-17.9833, -67.1250],
        'Potosí': [-19.5836, -65.7531],
        'Chuquisaca': [-19.0333, -65.2627],
        'Tarija': [-21.5355, -64.7296],
        'Beni': [-14.8333, -64.9000],
        'Pando': [-11.0267, -68.7692]
    };

    // Coordenadas actuales o por defecto
    let currentLat = {{ $entrada->latitud ?? -16.5000 }};
    let currentLng = {{ $entrada->longitud ?? -68.1500 }};
    let currentDept = '{{ $entrada->departamento }}';

    // Inicializar mapa
    let map = L.map('map').setView([currentLat, currentLng], currentLat && currentLng ? 13 : 6);
    let marker = null;

    // Agregar capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);

    // Si ya tiene ubicación, agregar marcador
    @if($entrada->tiene_ubicacion)
        marker = L.marker([currentLat, currentLng], {
            draggable: true
        }).addTo(map);

        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            updateCoordinates(position.lat, position.lng);
        });
    @endif

    // Función para actualizar coordenadas
    function updateCoordinates(lat, lng) {
        document.getElementById('latitud').value = lat.toFixed(7);
        document.getElementById('longitud').value = lng.toFixed(7);
        document.getElementById('latitud_display').value = lat.toFixed(7);
        document.getElementById('longitud_display').value = lng.toFixed(7);
    }

    // Click en el mapa
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], {
                draggable: true
            }).addTo(map);

            marker.on('dragend', function(e) {
                const position = marker.getLatLng();
                updateCoordinates(position.lat, position.lng);
            });
        }

        updateCoordinates(lat, lng);
    });

    // Cambiar vista del mapa al seleccionar departamento
    document.getElementById('departamento').addEventListener('change', function() {
        const departamento = this.value;
        if (departamento && coordenadasDepartamentos[departamento]) {
            const coords = coordenadasDepartamentos[departamento];
            map.setView(coords, 10);
        }
    });

    // Preview de imagen
    document.getElementById('imagen').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
</script>
@endsection