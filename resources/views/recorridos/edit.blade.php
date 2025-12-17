@extends('layouts.app')

@section('title', 'Editar Recorrido')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .section-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-body {
        padding: 1.5rem;
    }

    #map {
        height: 500px;
        width: 100%;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
    }

    .map-instructions {
        background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
        border-left: 4px solid #3b82f6;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .punto-item {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .punto-item:hover {
        border-color: #667eea;
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.2);
    }

    .punto-number {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

    .punto-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-delete-punto {
        background: #ef4444;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-delete-punto:hover {
        background: #dc2626;
        transform: scale(1.05);
    }

    .distancia-info {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-left: 4px solid #f59e0b;
        padding: 1rem;
        border-radius: 8px;
    }

    .sticky-actions {
        position: sticky;
        bottom: 0;
        background: white;
        padding: 1.5rem;
        border-top: 3px solid #667eea;
        box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.05);
        z-index: 1000;
        margin: 0 -1.5rem -1.5rem -1.5rem;
    }

    .custom-marker {
        background: transparent;
        border: none;
    }

    .alert-warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-left: 4px solid #f59e0b;
    }

    .puntos-container {
        max-height: 600px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    .puntos-container::-webkit-scrollbar {
        width: 8px;
    }

    .puntos-container::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    .puntos-container::-webkit-scrollbar-thumb {
        background: #667eea;
        border-radius: 4px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-pencil"></i> Editar Recorrido</h2>
            <p class="text-muted">Modifica los puntos y detalles del recorrido</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('recorridos.show', $recorrido) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Cancelar
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Errores en el formulario</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

<form action="{{ route('recorridos.update', $recorrido) }}" method="POST" id="formRecorrido">
    @csrf
    @method('PUT')

    <!-- Información Básica -->
    <section class="card section-card">
        <div class="section-header">
            <h5 class="m-0"><i class="bi bi-info-circle"></i> Información del Recorrido</h5>
        </div>
        <div class="section-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="entrada_id" class="form-label">Entrada Folclórica *</label>
                    <select name="entrada_id" id="entrada_id" 
                            class="form-select @error('entrada_id') is-invalid @enderror" required>
                        <option value="">Seleccione una entrada</option>
                        @foreach($entradas as $entrada)
                            <option value="{{ $entrada->id }}" 
                                    data-lat="{{ $entrada->latitud }}" 
                                    data-lng="{{ $entrada->longitud }}"
                                    {{ old('entrada_id', $recorrido->entrada_id) == $entrada->id ? 'selected' : '' }}>
                                {{ $entrada->nombre }} ({{ $entrada->departamento }})
                            </option>
                        @endforeach
                    </select>
                    @error('entrada_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre del Recorrido (Opcional)</label>
                    <input type="text" name="nombre" id="nombre" 
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $recorrido->nombre) }}"
                           placeholder="Ej: Recorrido Principal, Recorrido Nocturno...">
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" 
                              class="form-control @error('descripcion') is-invalid @enderror" 
                              rows="3" placeholder="Descripción del recorrido, características especiales...">{{ old('descripcion', $recorrido->descripcion) }}</textarea>
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
                    <strong>Instrucciones para editar:</strong>
                </p>
                <ul class="mb-0 small">
                    <li><strong>Haga clic en el mapa</strong> para agregar nuevos puntos</li>
                    <li>Los puntos actuales aparecerán en morado</li>
                    <li>Puede eliminar puntos con el botón de la papelera en la lista</li>
                    <li>Debe mantener <strong>al menos 2 puntos</strong> en el recorrido</li>
                    <li>Los cambios se guardarán al hacer clic en "Actualizar Recorrido"</li>
                </ul>
            </div>

            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                <strong>Importante:</strong> Los puntos existentes se eliminarán y se reemplazarán con los nuevos que agregue aquí.
            </div>

            <div id="map"></div>

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
        </div>
    </section>

    <!-- Lista de Puntos -->
    <section class="card section-card">
        <div class="section-header">
            <h5 class="m-0"><i class="bi bi-list-ol"></i> Puntos del Recorrido</h5>
            <span class="badge bg-light text-dark" id="contadorPuntos">0 puntos</span>
        </div>
        <div class="section-body">
            <p class="text-muted small mb-3">
                Lista de puntos en el orden del recorrido. Debe tener al menos 2 puntos.
            </p>

            <div id="puntosContainer" class="puntos-container"></div>

            <div id="noPuntosMessage" class="text-center text-muted py-4" style="display: none;">
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
                    <i class="bi bi-info-circle"></i> Debe tener al menos 2 puntos
                </span>
            </div>
            <div>
                <a href="{{ route('recorridos.show', $recorrido) }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="bi bi-save"></i> Actualizar Recorrido
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

    // Datos existentes del recorrido
    const puntosExistentes = @json($recorrido->puntos);

    // Inicializar mapa
    map = L.map('map').setView([-16.5000, -68.1500], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);

    // Cargar puntos existentes
    if (puntosExistentes.length > 0) {
        puntosExistentes.forEach(punto => {
            agregarPunto(
                parseFloat(punto.latitud), 
                parseFloat(punto.longitud), 
                punto.nombre, 
                punto.descripcion || ''
            );
        });
    }

    // Cambiar vista al seleccionar entrada
    document.getElementById('entrada_id').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const lat = option.dataset.lat;
        const lng = option.dataset.lng;
        
        if (lat && lng) {
            map.setView([lat, lng], 13);
        }
    });

    // Centrar en la entrada seleccionada inicialmente
    const entradaActual = document.getElementById('entrada_id').selectedOptions[0];
    if (entradaActual && entradaActual.dataset.lat && entradaActual.dataset.lng) {
        const lat = parseFloat(entradaActual.dataset.lat);
        const lng = parseFloat(entradaActual.dataset.lng);
        if (puntosExistentes.length === 0) {
            map.setView([lat, lng], 13);
        }
    }

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
                html: `<div style="background: #667eea; color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 3px solid white; box-shadow: 0 3px 8px rgba(0,0,0,0.3);">${puntoCount}</div>`,
                iconSize: [35, 35],
                iconAnchor: [17.5, 17.5]
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
        agregarPuntoALista(puntoCount, lat, lng, nombre, descripcion);
        
        // Actualizar distancia
        calcularDistanciaTotal();
        
        // Actualizar UI
        actualizarUI();
    }

    function agregarPuntoALista(id, lat, lng, nombre = '', descripcion = '') {
        const container = document.getElementById('puntosContainer');
        document.getElementById('noPuntosMessage').style.display = 'none';
        
        const div = document.createElement('div');
        div.className = 'punto-item';
        div.dataset.puntoId = id;
        
        div.innerHTML = `
            <div class="d-flex align-items-start">
                <div class="punto-number">${id}</div>
                <div class="flex-grow-1">
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="form-label small mb-1">Nombre del Punto *</label>
                            <input type="text" name="puntos[${id}][nombre]" 
                                   class="form-control form-control-sm" 
                                   value="${nombre || 'Punto ' + id}"
                                   placeholder="Nombre del punto ${id}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small mb-1">Descripción</label>
                            <input type="text" name="puntos[${id}][descripcion]" 
                                   class="form-control form-control-sm" 
                                   value="${descripcion || ''}"
                                   placeholder="Descripción opcional">
                        </div>
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-md-5">
                            <label class="form-label small mb-1">Latitud</label>
                            <input type="number" name="puntos[${id}][latitud]" 
                                   class="form-control form-control-sm" 
                                   value="${lat}" step="0.000001" readonly required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small mb-1">Longitud</label>
                            <input type="number" name="puntos[${id}][longitud]" 
                                   class="form-control form-control-sm" 
                                   value="${lng}" step="0.000001" readonly required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-delete-punto w-100" 
                                    onclick="eliminarPunto(${id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(div);
    }

    function eliminarPunto(id) {
        // Encontrar el índice del punto
        const index = puntos.findIndex(p => p.id === id);
        if (index === -1) return;
        
        // Remover marcador del mapa
        if (markers[index]) {
            map.removeLayer(markers[index]);
            markers.splice(index, 1);
        }
        
        // Remover del array
        puntos.splice(index, 1);
        
        // Remover de la lista visual
        const item = document.querySelector(`[data-punto-id="${id}"]`);
        if (item) {
            item.remove();
        }
        
        // Renumerar puntos
        renumerarPuntos();
        
        // Actualizar polyline
        actualizarPolyline();
        
        // Actualizar distancia
        calcularDistanciaTotal();
        
        // Actualizar UI
        actualizarUI();
        
        // Mostrar mensaje si no hay puntos
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
                html: `<div style="background: #667eea; color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 3px solid white; box-shadow: 0 3px 8px rgba(0,0,0,0.3);">${puntoCount}</div>`,
                iconSize: [35, 35],
                iconAnchor: [17.5, 17.5]
            }));
        });
        
        // Actualizar lista visual
        items.forEach((item, index) => {
            const number = item.querySelector('.punto-number');
            number.textContent = index + 1;
            
            // Actualizar nombres de los inputs
            const inputs = item.querySelectorAll('input');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                const newName = name.replace(/\[\d+\]/, `[${index + 1}]`);
                input.setAttribute('name', newName);
            });
        });
    }

    function actualizarPolyline() {
        if (polyline) {
            map.removeLayer(polyline);
        }
        
        if (puntos.length >= 2) {
            const latlngs = puntos.map(p => [p.lat, p.lng]);
            polyline = L.polyline(latlngs, {
                color: '#667eea',
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
        
        let distanciaTotal = 0;
        for (let i = 0; i < puntos.length - 1; i++) {
            const punto1 = L.latLng(puntos[i].lat, puntos[i].lng);
            const punto2 = L.latLng(puntos[i + 1].lat, puntos[i + 1].lng);
            distanciaTotal += punto1.distanceTo(punto2);
        }
        
        // Convertir de metros a kilómetros
        const distanciaKm = (distanciaTotal / 1000).toFixed(2);
        document.getElementById('distanciaTotal').textContent = distanciaKm;
    }

    function actualizarUI() {
        const totalPuntos = puntos.length;
        document.getElementById('totalPuntos').textContent = totalPuntos;
        document.getElementById('contadorPuntos').textContent = `${totalPuntos} punto${totalPuntos !== 1 ? 's' : ''}`;
        
        const submitBtn = document.getElementById('submitBtn');
        const statusText = document.getElementById('statusText');
        
        if (totalPuntos < 2) {
            submitBtn.disabled = true;
            statusText.innerHTML = '<i class="bi bi-exclamation-circle"></i> Debe tener al menos 2 puntos';
            statusText.className = 'text-danger small';
        } else {
            submitBtn.disabled = false;
            statusText.innerHTML = `<i class="bi bi-check-circle"></i> Listo para guardar (${totalPuntos} puntos)`;
            statusText.className = 'text-success small';
        }
    }

    // Actualizar UI inicial
    actualizarUI();
</script>
@endsection