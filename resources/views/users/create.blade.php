@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('styles')
<style>
    .role-selector-card {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .role-selector-card:hover {
        border-color: #667eea;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }

    .role-selector-card.selected {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }

    .role-info {
        margin-left: 2rem;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-person-plus"></i> Crear Nuevo Usuario
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST" id="userForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-8">
                            <!-- Información Personal -->
                            <h5 class="text-primary mb-3"><i class="bi bi-person"></i> Información Personal</h5>
                            
                            <div class="row">
                                <!-- Nombre -->
                                <div class="col-md-4 mb-3">
                                    <label for="nombre" class="form-label required">Nombre</label>
                                    <input type="text" 
                                           class="form-control @error('nombre') is-invalid @enderror" 
                                           id="nombre" 
                                           name="nombre" 
                                           value="{{ old('nombre') }}"
                                           pattern="[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+"
                                           title="Solo letras permitidas"
                                           required>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Apellido Paterno -->
                                <div class="col-md-4 mb-3">
                                    <label for="apellido_paterno" class="form-label required">Apellido Paterno</label>
                                    <input type="text" 
                                           class="form-control @error('apellido_paterno') is-invalid @enderror" 
                                           id="apellido_paterno" 
                                           name="apellido_paterno" 
                                           value="{{ old('apellido_paterno') }}"
                                           pattern="[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+"
                                           title="Solo letras permitidas"
                                           required>
                                    @error('apellido_paterno')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Apellido Materno -->
                                <div class="col-md-4 mb-3">
                                    <label for="apellido_materno" class="form-label">Apellido Materno</label>
                                    <input type="text" 
                                           class="form-control @error('apellido_materno') is-invalid @enderror" 
                                           id="apellido_materno" 
                                           name="apellido_materno" 
                                           value="{{ old('apellido_materno') }}"
                                           pattern="[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+"
                                           title="Solo letras permitidas">
                                    @error('apellido_materno')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- CI -->
                                <div class="col-md-6 mb-3">
                                    <label for="ci" class="form-label required">Carnet de Identidad (CI)</label>
                                    <input type="text" 
                                           class="form-control @error('ci') is-invalid @enderror" 
                                           id="ci" 
                                           name="ci" 
                                           value="{{ old('ci') }}"
                                           pattern="[0-9]{1,8}"
                                           maxlength="8"
                                           title="Solo números, máximo 8 dígitos"
                                           required>
                                    <small class="form-text text-muted">Máximo 8 dígitos</small>
                                    @error('ci')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Departamento -->
                                <div class="col-md-6 mb-3">
                                    <label for="departamento" class="form-label required">Departamento</label>
                                    <select class="form-select @error('departamento') is-invalid @enderror" 
                                            id="departamento" 
                                            name="departamento" 
                                            required>
                                        <option value="">Seleccione un departamento</option>
                                        @foreach($departamentos as $depto)
                                            <option value="{{ $depto }}" {{ old('departamento') == $depto ? 'selected' : '' }}>
                                                {{ $depto }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('departamento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Teléfono -->
                                <div class="col-md-6 mb-3">
                                    <label for="telefono" class="form-label required">Teléfono</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="text" 
                                               class="form-control @error('telefono') is-invalid @enderror" 
                                               id="telefono" 
                                               name="telefono" 
                                               value="{{ old('telefono') }}"
                                               pattern="[0-9]{8}"
                                               maxlength="8"
                                               title="Exactamente 8 dígitos"
                                               placeholder="70123456"
                                               required>
                                        @error('telefono')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Exactamente 8 dígitos</small>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label required">Correo Electrónico</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}"
                                               placeholder="correo@ejemplo.com"
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <h5 class="text-primary mb-3 mt-4"><i class="bi bi-lock"></i> Credenciales de Acceso</h5>

                            <div class="row">
                                <!-- Password -->
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label required">Contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password"
                                               minlength="8"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="bi bi-eye" id="eyeIcon"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        Mínimo 8 caracteres, debe incluir mayúsculas, minúsculas, números y símbolos
                                    </small>
                                </div>

                                <!-- Confirmar Password -->
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label required">Confirmar Contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation"
                                               minlength="8"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                            <i class="bi bi-eye" id="eyeIconConfirm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selector de Roles -->
                        <div class="col-md-4">
                            @if(auth()->user()->isAdminOrAbove() && isset($roles) && $roles->count() > 0)
                                <div class="card bg-light">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="bi bi-shield-check"></i> Asignar Rol</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted small mb-3">
                                            Selecciona el rol del usuario
                                        </p>

                                        @foreach($roles as $role)
                                            <div class="role-selector-card {{ old('role_id') == $role->id ? 'selected' : '' }}" 
                                                 onclick="selectRole({{ $role->id }})">
                                                <div class="form-check">
                                                    <input class="form-check-input" 
                                                           type="radio" 
                                                           name="role_id" 
                                                           id="role{{ $role->id }}" 
                                                           value="{{ $role->id }}"
                                                           {{ old('role_id') == $role->id ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="role{{ $role->id }}">
                                                        <strong>{{ $role->nombre }}</strong>
                                                    </label>
                                                </div>
                                                <div class="role-info">
                                                    <small class="text-muted">{{ Str::limit($role->descripcion, 60) }}</small>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="alert alert-info mt-3 mb-0">
                                            <small>
                                                <i class="bi bi-info-circle"></i>
                                                Si no seleccionas un rol, el usuario tendrá acceso limitado.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="alert alert-info mt-3">
            <i class="bi bi-info-circle"></i> <strong>Nota:</strong> Los campos marcados con 
            <span class="text-danger">*</span> son obligatorios.
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (password.type === 'password') {
            password.type = 'text';
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
        } else {
            password.type = 'password';
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
        }
    });

    document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
        const password = document.getElementById('password_confirmation');
        const eyeIcon = document.getElementById('eyeIconConfirm');
        
        if (password.type === 'password') {
            password.type = 'text';
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
        } else {
            password.type = 'password';
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
        }
    });

    // Validación de contraseñas coincidentes
    document.getElementById('userForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirmation').value;
        
        if (password !== passwordConfirm) {
            e.preventDefault();
            alert('Las contraseñas no coinciden');
            return false;
        }
    });

    // Capitalizar primera letra automáticamente
    ['nombre', 'apellido_paterno', 'apellido_materno'].forEach(function(field) {
        document.getElementById(field).addEventListener('blur', function() {
            if (this.value) {
                this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();
            }
        });
    });

    // Función para seleccionar rol
    function selectRole(roleId) {
        // Deseleccionar todos
        document.querySelectorAll('.role-selector-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Seleccionar el radio button
        const radio = document.getElementById('role' + roleId);
        radio.checked = true;
        
        // Agregar clase selected al card
        radio.closest('.role-selector-card').classList.add('selected');
    }
</script>
@endsection