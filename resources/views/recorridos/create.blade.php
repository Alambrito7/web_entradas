@extends('layouts.app')

@section('title', 'Crear Recorrido')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .section-card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 20px;
    }

    .section-header {
        background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 0.375rem 0.375rem 0 0;
    }

    .section-body {
        padding: 25px;
        background: white;
    }

    #map {
        height: 500px;
        width: 100%;
        border-radius: 8px;
        border: 2px solid #dee2e6;
        z-index: 1;
    }

    .map-instructions {
        background: #fce7f3;
        border: 1px solid #ec4899;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .punto-item {
        background: #f8f9fa;
        border-left: 4px solid #ec4899;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        display: flex;
        gap: 10px;
        align-items: start;
        transition: all 0.3s;
    }

    .punto-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .punto-number {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, #ec4899, #8b5cf6);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        flex-shrink: 0;
    }

    .punto-content {
        flex: 1;
    }

    .punto-actions {
        display: flex;
        gap: 5px;
        flex-shrink: 0;
    }

    .puntos-container {
        max-height: 400px;
        overflow-y: auto;
    }

    .puntos-container::-webkit-scrollbar {
        width: 8px;
    }

    .puntos-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .puntos-container::-webkit-scrollbar-thumb {
        background: #ec4899;
        border-radius: 10px;
    }

    .distancia-info {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
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
        <h2><i class="bi bi-map"></i> Crear Nuevo Recorrido</h2>
        <p class="text-muted">Defina el recorrido de una entrada folclórica marcando puntos en el mapa</p>
    </div>
</div>

<form action="{{ route('recorridos.store') }}" method="POST" id="recorridoForm">
    @csrf

    <!-- Información Básica -->
    <section class="card section-card">
        <div class="section-header">
            <h5 class="m-0"><i class="bi bi-info-circle"></i> Información Básica</h5>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label required">Entrada Folclórica</label>
                    <select name="entrada_id" id="entrada_id" class="form-select @error('entrada_id') is-invalid @enderror" required>
                        <option value="">Seleccione una entrada...</option>
                        @foreach($entradas as $entrada)
                            <option value="{{ $entrada->id }}" 
                                    data-lat="{{ $entrada->latitud }}" 
                                    data-lng="{{ $entrada->longitud }}"
                                    {{ old('entrada_id') == $entrada->id ? 'selected' : '' }}>
                                {{ $entrada->nombre }} - {{ $entrada->departamento }} ({{ $entrada->fecha_evento->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                    @error('entrada_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Nombre del Recorrido</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                           value="{{ old('nombre') }}" placeholder="Ej: Recorrido Principal, Ruta Tradicional...">
                    <small class="form-text text-muted">Opcional - Si no especifica, se usará el nombre de la entrada</small>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" 
                              rows="3" placeholder="Descripción del recorrido, características especiales...">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    <!-- Mapa Interactivo -->
    <section class="card section-card">
        <div class="section-header">
            <h5 class="m-0"><i class="bi bi-map-fill"></i> Mapa del Recorrido</h5>
        </div>
        <div class="section-body">
            <div class="map-instructions">
                <p class="mb-2">
                    <i class="bi bi-info-circle"></i> 
                    <strong>Instrucciones:</strong>
                </p>
                <ul class="mb-0 small">
                    <li>Seleccione una entrada para centrar el mapa</li>
                    <li><strong>Haga clic en el mapa</strong> para agregar puntos al recorrido</li>
                    <li>Los puntos se conectarán automáticamente en el orden que los agregue</li>
                    <li>Debe agregar <strong>al menos 2 puntos</strong> para crear el recorrido</li>
                    <li>Puede eliminar puntos con el botón de la papelera</li>
                </ul>
            </div>

            <div id="map"></div>

            @if($entradas->count() > 0 && $entradas->first()->tiene_ubicacion)
                <div class="distancia-info mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <i class="bi bi-geo-alt-fill"></i>
                            <strong>Puntos:</strong> <span id="totalPuntos">0</span>
                        </div>
                        <div class="col-md-6">
                            <i class="bi bi-rulers"></i>
                            <strong>Distancia aproximada:</strong> <span id="distanciaTotal">0</span> km
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Lista de Puntos -->
    <section class="card section-card">
        <div class="section-header">
            <h5 class="m-0"><i class="bi bi-list-ol"></i> Puntos del Recorrido</h5>
        </div>
        <div class="section-body">
            <p class="text-muted small mb-3">
                Lista de puntos en el orden del recorrido. Debe tener al menos 2 puntos.
            </p>

            <div id="puntosContainer" class="puntos-container"></div>

            <div id="noPuntosMessage" class="text-center text-muted py-4">
                <i class="bi bi-geo" style="font-size: 3rem;"></i>
                <p class="mt-2">Haga clic en el mapa para agregar puntos al recorrido</p>
            </div>
        </div>
    </section>

    <!-- Acciones -->
    <div class="sticky-actions">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <span class="text-muted small" id="statusText">
                    <i class="bi bi-info-circle"></i> Agregue al menos 2 puntos al recorrido
                </span>
            </div>
            <div>
                <a href="{{ route('recorridos.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                    <i class="bi bi-save"></i> Guardar Recorrido
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map;
    let markers = [];
    let polyline;
    let puntos = [];
    let puntoCount = 0;

    // Inicializar mapa centrado en Bolivia
    map = L.map('map').setView([-16.5000, -68.1500], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);

    // Cambiar vista al seleccionar entrada
    document.getElementById('entrada_id').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const lat = option.dataset.lat;
        const lng = option.dataset.lng;
        
        if (lat && lng) {
            map.setView([lat, lng], 13);
        }
    });

    // Click en el mapa para agregar punto
    map.on('click', function(e) {
        agregarPunto(e.latlng.lat, e.latlng.lng);
    });

    function agregarPunto(lat, lng, nombre = '', descripcion = '') {
        puntoCount++;
        const index = puntos.length;
        
        // Crear marcador
        const marker = L.marker([lat, lng], {
            title: `Punto ${puntoCount}`,
            icon: L.divIcon({
                className: 'custom-marker',
                html: `<div style="background: #ec4899; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">${puntoCount}</div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            })
        }).addTo(map);
        
        markers.push(marker);
        
        // Agregar a array de puntos
        puntos.push({
            id: puntoCount,
            lat: lat,
            lng: lng,
            nombre: nombre || `Punto ${puntoCount}`,
            descripcion: descripcion
        });
        
        // Actualizar línea del recorrido
        actualizarPolyline();
        
        // Agregar a la lista
        agregarPuntoALista(puntoCount, lat, lng);
        
        // Actualizar distancia
        calcularDistanciaTotal();
        
        // Actualizar UI
        actualizarUI();
    }

    function agregarPuntoALista(id, lat, lng) {
        const container = document.getElementById('puntosContainer');
        document.getElementById('noPuntosMessage').style.display = 'none';
        
        const div = document.createElement('div');
        div.className = 'punto-item';
        div.dataset.puntoId = id;
        
        div.innerHTML = `
            <div class="punto-number">${id}</div>
            <div class="punto-content">
                <input type="hidden" name="puntos[${id}][latitud]" value="${lat}">
                <input type="hidden" name="puntos[${id}][longitud]" value="${lng}">
                <div class="mb-2">
                    <input type="text" name="puntos[${id}][nombre]" class="form-control form-control-sm" 
                           placeholder="Nombre del punto ${id}" value="Punto ${id}" required>
                </div>
                <div>
                    <textarea name="puntos[${id}][descripcion]" class="form-control form-control-sm" 
                              rows="2" placeholder="Descripción (opcional)"></textarea>
                </div>
                <small class="text-muted">
                    <i class="bi bi-geo-alt"></i> ${lat.toFixed(6)}, ${lng.toFixed(6)}
                </small>
            </div>
            <div class="punto-actions">
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarPunto(${id})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        
        container.appendChild(div);
    }

    function eliminarPunto(id) {
        // Eliminar de array
        const index = puntos.findIndex(p => p.id === id);
        if (index !== -1) {
            puntos.splice(index, 1);
            
            // Eliminar marcador
            if (markers[index]) {
                map.removeLayer(markers[index]);
                markers.splice(index, 1);
            }
        }
        
        // Eliminar de la lista
        const item = document.querySelector(`[data-punto-id="${id}"]`);
        if (item) item.remove();
        
        // Actualizar polyline
        actualizarPolyline();
        
        // Renumerar puntos
        renumerarPuntos();
        
        // Actualizar distancia
        calcularDistanciaTotal();
        
        // Actualizar UI
        actualizarUI();
        
        if (puntos.length === 0) {
            document.getElementById('noPuntosMessage').style.display = 'block';
        }
    }

    function renumerarPuntos() {
        const items = document.querySelectorAll('.punto-item');
        puntoCount = 0;
        
        // Actualizar markers en el mapa
        markers.forEach((marker, index) => {
            puntoCount++;
            marker.setIcon(L.divIcon({
                className: 'custom-marker',
                html: `<div style="background: #ec4899; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">${puntoCount}</div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            }));
        });
        
        // Actualizar lista visual
        items.forEach((item, index) => {
            const number = item.querySelector('.punto-number');
            number.textContent = index + 1;
            
            const nombre = item.querySelector('input[type="text"]');
            if (nombre.value.startsWith('Punto ')) {
                nombre.value = `Punto ${index + 1}`;
                nombre.placeholder = `Nombre del punto ${index + 1}`;
            }
        });
    }

    function actualizarPolyline() {
        if (polyline) {
            map.removeLayer(polyline);
        }
        
        if (puntos.length >= 2) {
            const latlngs = puntos.map(p => [p.lat, p.lng]);
            polyline = L.polyline(latlngs, {
                color: '#ec4899',
                weight: 4,
                opacity: 0.7,
                dashArray: '10, 5'
            }).addTo(map);
            
            // Ajustar vista al recorrido
            map.fitBounds(polyline.getBounds(), { padding: [50, 50] });
        }
    }

    function calcularDistanciaTotal() {
        if (puntos.length < 2) {
            document.getElementById('distanciaTotal').textContent = '0';
            return;
        }
        
        let distancia = 0;
        for (let i = 0; i < puntos.length - 1; i++) {
            distancia += calcularDistancia(
                puntos[i].lat,
                puntos[i].lng,
                puntos[i + 1].lat,
                puntos[i + 1].lng
            );
        }
        
        document.getElementById('distanciaTotal').textContent = distancia.toFixed(2);
    }

    function calcularDistancia(lat1, lon1, lat2, lon2) {
        const R = 6371; // Radio de la Tierra en km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    function actualizarUI() {
        const total = puntos.length;
        document.getElementById('totalPuntos').textContent = total;
        
        const submitBtn = document.getElementById('submitBtn');
        const statusText = document.getElementById('statusText');
        
        if (total >= 2) {
            submitBtn.disabled = false;
            statusText.innerHTML = '<i class="bi bi-check-circle"></i> Recorrido listo para guardar';
        } else {
            submitBtn.disabled = true;
            statusText.innerHTML = `<i class="bi bi-info-circle"></i> Agregue ${2 - total} punto(s) más`;
        }
    }

    // Validación del formulario
    document.getElementById('recorridoForm').addEventListener('submit', function(e) {
        if (puntos.length < 2) {
            e.preventDefault();
            alert('Debe agregar al menos 2 puntos al recorrido');
            return false;
        }
    });
</script>
@endsection