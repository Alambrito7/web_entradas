@extends('layouts.app')

@section('title', 'Crear Bloque')

@section('styles')
<style>
    .form-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f3f4f6;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .responsable-item {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        position: relative;
    }

    .responsable-item .remove-btn {
        position: absolute;
        top: 1rem;
        right: 1rem;
    }

    .preview-image {
        max-width: 200px;
        max-height: 200px;
        border-radius: 10px;
        margin-top: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-plus-circle"></i> Crear Nuevo Bloque</h2>
            <p class="text-muted">Registra un nuevo bloque en el sistema</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('bloques.index') }}" class="btn btn-outline-secondary">
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

    <form action="{{ route('bloques.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Información Básica -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="bi bi-info-circle"></i>
                Información Básica
            </h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label required">Nombre del Bloque</label>
                    <input type="text" 
                           class="form-control @error('nombre') is-invalid @enderror" 
                           id="nombre" 
                           name="nombre" 
                           value="{{ old('nombre') }}"
                           required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="fraternidad_id" class="form-label">Fraternidad</label>
                    <select class="form-select @error('fraternidad_id') is-invalid @enderror" 
                            id="fraternidad_id" 
                            name="fraternidad_id">
                        <option value="">Sin fraternidad</option>
                        @foreach($fraternidades as $fraternidad)
                            <option value="{{ $fraternidad->id }}" {{ old('fraternidad_id') == $fraternidad->id ? 'selected' : '' }}>
                                {{ $fraternidad->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('fraternidad_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="fecha_fundacion" class="form-label">Fecha de Fundación</label>
                    <input type="date" 
                           class="form-control @error('fecha_fundacion') is-invalid @enderror" 
                           id="fecha_fundacion" 
                           name="fecha_fundacion" 
                           value="{{ old('fecha_fundacion') }}">
                    @error('fecha_fundacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="estado" class="form-label required">Estado</label>
                    <select class="form-select @error('estado') is-invalid @enderror" 
                            id="estado" 
                            name="estado" 
                            required>
                        @foreach($estados as $key => $value)
                            <option value="{{ $key }}" {{ old('estado', 'activo') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('estado')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="lema" class="form-label">Lema / Frase</label>
                    <input type="text" 
                           class="form-control @error('lema') is-invalid @enderror" 
                           id="lema" 
                           name="lema" 
                           value="{{ old('lema') }}"
                           placeholder="Ej: Unidos por la tradición">
                    @error('lema')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="historia" class="form-label">Historia / Reseña</label>
                    <textarea class="form-control @error('historia') is-invalid @enderror" 
                              id="historia" 
                              name="historia" 
                              rows="4"
                              placeholder="Escribe la historia del bloque...">{{ old('historia') }}</textarea>
                    @error('historia')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Responsables -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="bi bi-person-badge"></i>
                Responsables del Bloque
            </h5>

            <div id="responsables-container">
                <!-- Los responsables se agregarán aquí dinámicamente -->
            </div>

            <button type="button" class="btn btn-outline-primary" id="add-responsable">
                <i class="bi bi-plus-circle"></i> Agregar Responsable
            </button>
        </div>

        <!-- Contador de Integrantes -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="bi bi-people"></i>
                Integrantes
            </h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="integrantes_aproximados" class="form-label">Número Aproximado de Integrantes</label>
                    <input type="number" 
                           class="form-control @error('integrantes_aproximados') is-invalid @enderror" 
                           id="integrantes_aproximados" 
                           name="integrantes_aproximados" 
                           value="{{ old('integrantes_aproximados', 0) }}"
                           min="0">
                    @error('integrantes_aproximados')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Este número se puede incrementar automáticamente cuando se inscriban nuevos integrantes</small>
                </div>
            </div>
        </div>

        <!-- Redes Sociales -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="bi bi-share"></i>
                Redes Sociales (Opcional)
            </h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="facebook" class="form-label">
                        <i class="bi bi-facebook text-primary"></i> Facebook
                    </label>
                    <input type="url" 
                           class="form-control @error('facebook') is-invalid @enderror" 
                           id="facebook" 
                           name="facebook" 
                           value="{{ old('facebook') }}"
                           placeholder="https://facebook.com/...">
                    @error('facebook')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="youtube" class="form-label">
                        <i class="bi bi-youtube text-danger"></i> YouTube / Video
                    </label>
                    <input type="url" 
                           class="form-control @error('youtube') is-invalid @enderror" 
                           id="youtube" 
                           name="youtube" 
                           value="{{ old('youtube') }}"
                           placeholder="https://youtube.com/...">
                    @error('youtube')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Foto Principal -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="bi bi-image"></i>
                Foto Principal del Bloque
            </h5>

            <div class="row g-3">
                <div class="col-md-12">
                    <label for="foto_principal" class="form-label">Seleccionar Foto</label>
                    <input type="file" 
                           class="form-control @error('foto_principal') is-invalid @enderror" 
                           id="foto_principal" 
                           name="foto_principal" 
                           accept="image/*"
                           onchange="previewImage(this)">
                    @error('foto_principal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Formatos: JPG, PNG. Tamaño máximo: 2MB</small>
                </div>

                <div class="col-md-12">
                    <img id="image-preview" class="preview-image" style="display: none;">
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="form-section">
            <div class="d-flex justify-content-between">
                <a href="{{ route('bloques.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Crear Bloque
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let responsableIndex = 0;

    // Agregar responsable
    document.getElementById('add-responsable').addEventListener('click', function() {
        const container = document.getElementById('responsables-container');
        const responsableHTML = `
            <div class="responsable-item" id="responsable-${responsableIndex}">
                <button type="button" class="btn btn-sm btn-danger remove-btn" onclick="removeResponsable(${responsableIndex})">
                    <i class="bi bi-trash"></i>
                </button>
                
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label required">Nombre del Responsable</label>
                        <input type="text" 
                               class="form-control" 
                               name="responsables[${responsableIndex}][nombre]" 
                               required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" 
                               class="form-control" 
                               name="responsables[${responsableIndex}][telefono]" 
                               pattern="[0-9]{8}"
                               maxlength="8"
                               placeholder="70123456">
                        <small class="text-muted">8 dígitos</small>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" 
                               class="form-control" 
                               name="responsables[${responsableIndex}][email]" 
                               placeholder="correo@ejemplo.com">
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', responsableHTML);
        responsableIndex++;
    });

    // Remover responsable
    function removeResponsable(index) {
        const element = document.getElementById(`responsable-${index}`);
        element.remove();
    }

    // Agregar un responsable al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('add-responsable').click();
    });

    // Preview de imagen
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection