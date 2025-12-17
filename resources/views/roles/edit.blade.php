@extends('layouts.app')

@section('title', 'Editar Rol')

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

    .permissions-grid {
        display: grid;
        gap: 1.5rem;
    }

    .module-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .module-card:hover {
        border-color: #667eea;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }

    .module-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .module-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .module-title {
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .module-select-all {
        margin-left: auto;
    }

    .permissions-checkboxes {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 0.75rem;
    }

    .permission-item {
        display: flex;
        align-items: center;
    }

    .permission-item .form-check {
        margin: 0;
        padding: 0.5rem 1rem;
        background: white;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        width: 100%;
        transition: all 0.3s ease;
    }

    .permission-item .form-check:hover {
        border-color: #667eea;
        background: #f9fafb;
    }

    .permission-item .form-check-input:checked ~ .form-check-label {
        color: #667eea;
        font-weight: 600;
    }

    .permission-item .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    .sticky-actions {
        position: sticky;
        bottom: 0;
        background: white;
        padding: 1.5rem;
        border-top: 3px solid #667eea;
        box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.05);
        margin: 0 -2rem -2rem -2rem;
        border-radius: 0 0 15px 15px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-pencil"></i> Editar Rol: {{ $role->nombre }}</h2>
            <p class="text-muted">Modifica los permisos y configuración del rol</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('roles.show', $role) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Cancelar
            </a>
        </div>
    </div>

    @if($role->nombre === 'Superadmin')
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i>
            <strong>Advertencia:</strong> Estás editando el rol Superadmin. Ten cuidado con los cambios que realices.
        </div>
    @endif

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

    <form action="{{ route('roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Información Básica -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="bi bi-info-circle"></i>
                Información Básica
            </h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label required">Nombre del Rol</label>
                    <input type="text" 
                           class="form-control @error('nombre') is-invalid @enderror" 
                           id="nombre" 
                           name="nombre" 
                           value="{{ old('nombre', $role->nombre) }}"
                           placeholder="Ej: Editor, Moderador..."
                           {{ $role->nombre === 'Superadmin' ? 'readonly' : '' }}
                           required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($role->nombre === 'Superadmin')
                        <small class="text-danger">El nombre del rol Superadmin no se puede cambiar</small>
                    @else
                        <small class="text-muted">El nombre debe ser único y descriptivo</small>
                    @endif
                </div>

                <div class="col-md-6">
                    <label for="activo" class="form-label">Estado</label>
                    <div class="form-check form-switch" style="padding-top: 0.5rem;">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="activo" 
                               name="activo" 
                               value="1"
                               {{ old('activo', $role->activo) ? 'checked' : '' }}>
                        <label class="form-check-label" for="activo">
                            Rol activo
                        </label>
                    </div>
                    <small class="text-muted">Los roles inactivos no pueden ser asignados a usuarios</small>
                </div>

                <div class="col-md-12">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                              id="descripcion" 
                              name="descripcion" 
                              rows="3"
                              placeholder="Describe las responsabilidades y alcance de este rol...">{{ old('descripcion', $role->descripcion) }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Permisos -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="bi bi-key"></i>
                Permisos del Rol
            </h5>

            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <strong>Nota:</strong> Selecciona los permisos que tendrá este rol. 
                Puedes marcar/desmarcar todos los permisos de un módulo con el botón "Seleccionar Todo".
            </div>

            <div class="permissions-grid">
                @foreach($modulos as $moduloKey => $moduloNombre)
                    <div class="module-card">
                        <div class="module-header">
                            <div class="module-icon">
                                @switch($moduloKey)
                                    @case('users')
                                        <i class="bi bi-people"></i>
                                        @break
                                    @case('roles')
                                        <i class="bi bi-shield-lock"></i>
                                        @break
                                    @case('danzas')
                                        <i class="bi bi-music-note-beamed"></i>
                                        @break
                                    @case('entradas')
                                        <i class="bi bi-calendar-event"></i>
                                        @break
                                    @case('fraternidades')
                                        <i class="bi bi-people-fill"></i>
                                        @break
                                    @case('recorridos')
                                        <i class="bi bi-map"></i>
                                        @break
                                    @default
                                        <i class="bi bi-folder"></i>
                                @endswitch
                            </div>
                            <h6 class="module-title">{{ $moduloNombre }}</h6>
                            <div class="module-select-all">
                                <button type="button" 
                                        class="btn btn-sm btn-outline-primary select-all-btn"
                                        data-module="{{ $moduloKey }}">
                                    <i class="bi bi-check-all"></i> Seleccionar Todo
                                </button>
                            </div>
                        </div>

                        <div class="permissions-checkboxes">
                            @foreach($acciones as $accionKey => $accionNombre)
                                @php
                                    $hasPermission = $role->permisos && 
                                                   isset($role->permisos[$moduloKey]) && 
                                                   is_array($role->permisos[$moduloKey]) && 
                                                   in_array($accionKey, $role->permisos[$moduloKey]);
                                    
                                    $isChecked = old('permisos.'.$moduloKey) 
                                               ? (is_array(old('permisos.'.$moduloKey)) && in_array($accionKey, old('permisos.'.$moduloKey)))
                                               : $hasPermission;
                                @endphp
                                <div class="permission-item">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="permisos[{{ $moduloKey }}][]" 
                                               value="{{ $accionKey }}"
                                               id="permiso_{{ $moduloKey }}_{{ $accionKey }}"
                                               data-module="{{ $moduloKey }}"
                                               {{ $isChecked ? 'checked' : '' }}>
                                        <label class="form-check-label" for="permiso_{{ $moduloKey }}_{{ $accionKey }}">
                                            @switch($accionKey)
                                                @case('create')
                                                    <i class="bi bi-plus-circle"></i>
                                                    @break
                                                @case('read')
                                                    <i class="bi bi-eye"></i>
                                                    @break
                                                @case('update')
                                                    <i class="bi bi-pencil"></i>
                                                    @break
                                                @case('delete')
                                                    <i class="bi bi-trash"></i>
                                                    @break
                                                @case('restore')
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                    @break
                                                @case('force_delete')
                                                    <i class="bi bi-trash-fill"></i>
                                                    @break
                                            @endswitch
                                            {{ $accionNombre }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Acciones -->
        <div class="sticky-actions">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('roles.show', $role) }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Actualizar Rol
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Funcionalidad de "Seleccionar Todo" por módulo
    document.querySelectorAll('.select-all-btn').forEach(button => {
        button.addEventListener('click', function() {
            const module = this.dataset.module;
            const checkboxes = document.querySelectorAll(`input[data-module="${module}"]`);
            
            // Verificar si todos están marcados
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            // Si todos están marcados, desmarcar todos; si no, marcar todos
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
            
            // Cambiar texto del botón
            updateButtonText(this, !allChecked);
        });
    });

    // Actualizar texto del botón al cambiar checkboxes individualmente
    document.querySelectorAll('.permission-item input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const module = this.dataset.module;
            const checkboxes = document.querySelectorAll(`input[data-module="${module}"]`);
            const button = document.querySelector(`.select-all-btn[data-module="${module}"]`);
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            updateButtonText(button, allChecked);
        });
    });

    function updateButtonText(button, allChecked) {
        if (allChecked) {
            button.innerHTML = '<i class="bi bi-x-circle"></i> Deseleccionar Todo';
        } else {
            button.innerHTML = '<i class="bi bi-check-all"></i> Seleccionar Todo';
        }
    }

    // Inicializar botones según estado actual
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.select-all-btn').forEach(button => {
            const module = button.dataset.module;
            const checkboxes = document.querySelectorAll(`input[data-module="${module}"]`);
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            updateButtonText(button, allChecked);
        });
    });
</script>
@endsection