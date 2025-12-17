@extends('layouts.app')

@section('title', 'Registrar Nueva Fraternidad')

@section('styles')
<style>
    .section-card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 20px;
    }

    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

    .pasante-item {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .pasante-item input {
        flex: 1;
    }

    .pasante-number {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        flex-shrink: 0;
    }

    .remove-pasante {
        flex-shrink: 0;
    }

    .entrada-checkbox {
        padding: 12px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 10px;
        transition: all 0.3s;
        cursor: pointer;
    }

    .entrada-checkbox:hover {
        background: #f8f9fa;
        border-color: #667eea;
    }

    .entrada-checkbox input[type="checkbox"]:checked ~ label {
        color: #667eea;
        font-weight: 600;
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

    .pasantes-container {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 10px;
    }

    .pasantes-container::-webkit-scrollbar {
        width: 8px;
    }

    .pasantes-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .pasantes-container::-webkit-scrollbar-thumb {
        background: #667eea;
        border-radius: 10px;
    }
</style>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <h2><i class="bi bi-people-fill"></i> Registrar Nueva Fraternidad</h2>
        <p class="text-muted">Complete el formulario para registrar una nueva fraternidad folclórica</p>
    </div>
</div>

<form action="{{ route('fraternidades.store') }}" method="POST" id="fraternidadForm">
    @csrf

    <!-- Información Básica -->
    <section class="card section-card">
        <div class="section-header">
            <h5 class="section-title"><i class="bi bi-info-circle"></i> Información Básica</h5>
            <span class="pill">Obligatorio</span>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label required">Nombre de la Fraternidad</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                           value="{{ old('nombre') }}" 
                           placeholder="Ej: Fraternidad Señorial Illimani, Los Waca Tokhoris..." required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Danza</label>
                    <select name="danza_id" class="form-select @error('danza_id') is-invalid @enderror">
                        <option value="">Seleccione una danza...</option>
                        @foreach($danzas as $danza)
                            <option value="{{ $danza->id }}" {{ old('danza_id') == $danza->id ? 'selected' : '' }}>
                                {{ $danza->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Danza que representa la fraternidad</small>
                    @error('danza_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Fecha de Fundación</label>
                    <input type="date" name="fecha_fundacion" 
                           class="form-control @error('fecha_fundacion') is-invalid @enderror" 
                           value="{{ old('fecha_fundacion') }}">
                    @error('fecha_fundacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Lema</label>
                    <input type="text" name="lema" 
                           class="form-control @error('lema') is-invalid @enderror" 
                           value="{{ old('lema') }}" 
                           placeholder="Lema o frase característica de la fraternidad">
                    @error('lema')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" 
                              rows="4" placeholder="Historia, características, tradiciones de la fraternidad...">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    <!-- Datos de Contacto -->
    <section class="card section-card">
        <div class="section-header">
            <h5 class="section-title"><i class="bi bi-telephone"></i> Datos de Contacto</h5>
            <span class="pill">Opcional</span>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" 
                           class="form-control @error('telefono') is-invalid @enderror" 
                           value="{{ old('telefono') }}" 
                           pattern="[0-9]{8}"
                           maxlength="8"
                           placeholder="70123456">
                    <small class="form-text text-muted">Exactamente 8 dígitos</small>
                    @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="correo_electronico" 
                           class="form-control @error('correo_electronico') is-invalid @enderror" 
                           value="{{ old('correo_electronico') }}" 
                           placeholder="fraternidad@ejemplo.com">
                    @error('correo_electronico')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    <!-- Pasantes Actuales -->
    <section class="card section-card">
        <div class="section-header">
            <h5 class="section-title"><i class="bi bi-person-badge"></i> Pasantes Actuales</h5>
            <span class="pill">Opcional</span>
        </div>
        <div class="section-body">
            <button type="button" class="btn btn-primary btn-sm mb-3" id="addPasante">
                <i class="bi bi-plus-circle"></i> Agregar Pasante
            </button>
            <small class="d-block text-muted mb-3">
                Agregue los nombres de los pasantes actuales de la fraternidad. Puede agregar tantos como necesite.
            </small>

            <div id="pasantesContainer" class="pasantes-container"></div>
        </div>
    </section>

    <!-- Entradas Folclóricas -->
    <section class="card section-card">
        <div class="section-header">
            <h5 class="section-title"><i class="bi bi-calendar-event"></i> Entradas Folclóricas</h5>
            <span class="pill">Opcional</span>
        </div>
        <div class="section-body">
            <p class="text-muted small mb-3">
                Seleccione las entradas folclóricas en las que participa o ha participado esta fraternidad
            </p>

            @if($entradas->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No hay entradas registradas. 
                    <a href="{{ route('entradas.create') }}">Crear una entrada</a>
                </div>
            @else
                <div class="row">
                    @foreach($entradas as $entrada)
                        <div class="col-md-6 mb-2">
                            <div class="entrada-checkbox">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="entradas[]" 
                                           value="{{ $entrada->id }}" 
                                           id="entrada{{ $entrada->id }}"
                                           {{ in_array($entrada->id, old('entradas', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="entrada{{ $entrada->id }}">
                                        <strong>{{ $entrada->nombre }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-geo-alt"></i> {{ $entrada->departamento }} - 
                                            <i class="bi bi-calendar3"></i> {{ $entrada->fecha_evento->format('d/m/Y') }}
                                        </small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Acciones Sticky -->
    <div class="sticky-actions">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <span class="text-muted small">
                    <i class="bi bi-info-circle"></i> Complete al menos el nombre de la fraternidad
                </span>
            </div>
            <div>
                <a href="{{ route('fraternidades.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar Fraternidad
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    let pasanteCount = 0;

    // Función para agregar un pasante
    function addPasante() {
        pasanteCount++;
        const container = document.getElementById('pasantesContainer');
        
        const div = document.createElement('div');
        div.className = 'pasante-item';
        div.innerHTML = `
            <div class="pasante-number">${pasanteCount}</div>
            <input type="text" 
                   name="pasantes[]" 
                   class="form-control" 
                   placeholder="Nombre completo del pasante ${pasanteCount}">
            <button type="button" class="btn btn-danger btn-sm remove-pasante" onclick="removePasante(this)">
                <i class="bi bi-trash"></i>
            </button>
        `;
        
        container.appendChild(div);
    }

    // Función para eliminar un pasante
    function removePasante(button) {
        const item = button.closest('.pasante-item');
        item.remove();
        
        // Renumerar los pasantes
        const items = document.querySelectorAll('.pasante-item');
        items.forEach((item, index) => {
            const number = item.querySelector('.pasante-number');
            number.textContent = index + 1;
            
            const input = item.querySelector('input');
            input.placeholder = `Nombre completo del pasante ${index + 1}`;
        });
        
        pasanteCount = items.length;
    }

    // Evento del botón agregar
    document.getElementById('addPasante').addEventListener('click', addPasante);

    // Agregar un pasante inicial
    addPasante();

    // Validación del formulario
    document.getElementById('fraternidadForm').addEventListener('submit', function(e) {
        const nombre = document.querySelector('[name="nombre"]').value;
        
        if (!nombre.trim()) {
            e.preventDefault();
            alert('Por favor ingrese el nombre de la fraternidad');
            return false;
        }
    });

    // Seleccionar/Deseleccionar todas las entradas
    const entradasCheckboxes = document.querySelectorAll('input[name="entradas[]"]');
    
    // Agregar botón para seleccionar todas
    if (entradasCheckboxes.length > 0) {
        const btnContainer = document.createElement('div');
        btnContainer.className = 'mb-3';
        btnContainer.innerHTML = `
            <button type="button" class="btn btn-outline-primary btn-sm me-2" id="selectAllEntradas">
                <i class="bi bi-check-square"></i> Seleccionar Todas
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="deselectAllEntradas">
                <i class="bi bi-square"></i> Deseleccionar Todas
            </button>
        `;
        
        const entradasSection = entradasCheckboxes[0].closest('.section-body');
        entradasSection.insertBefore(btnContainer, entradasSection.querySelector('.row'));
        
        document.getElementById('selectAllEntradas').addEventListener('click', function() {
            entradasCheckboxes.forEach(cb => cb.checked = true);
        });
        
        document.getElementById('deselectAllEntradas').addEventListener('click', function() {
            entradasCheckboxes.forEach(cb => cb.checked = false);
        });
    }
</script>
@endsection