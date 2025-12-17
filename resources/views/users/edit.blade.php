@extends('layouts.app')

@section('title', 'Editar Usuario')

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
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">
                    <i class="bi bi-pencil-square"></i> Editar Usuario
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', $user) }}" method="POST" id="userEditForm">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Nombre -->
                        <div class="col-md-4 mb-3">
                            <label for="nombre" class="form-label required">Nombre</label>
                            <input type="text" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre', $user->nombre) }}"
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
                                   value="{{ old('apellido_paterno', $user->apellido_paterno) }}"
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
                                   value="{{ old('apellido_materno', $user->apellido_materno) }}"
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
                                   value="{{ old('ci', $user->ci) }}"
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
                                    <option value="{{ $depto }}" 
                                        {{ old('departamento', $user->departamento) == $depto ? 'selected' : '' }}>
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
                                       value="{{ old('telefono', $user->telefono) }}"
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
                                       value="{{ old('email', $user->email) }}"
                                       placeholder="correo@ejemplo.com"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- NUEVO: Selector de Rol -->
                    @if(auth()->user()->isAdminOrAbove() && isset($roles) && $roles->count() > 0)
                        <div class="mb-3">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-shield-check"></i> Rol del Usuario
                            </h5>

                            @if($user->isSuperadmin() && !auth()->user()->isSuperadmin())
                                <div class="alert alert-warning">
                                    <i class="bi bi-lock"></i>
                                    <strong>Protegido:</strong> Solo otro Superadmin puede cambiar el rol de este usuario.
                                </div>
                                <input type="hidden" name="role_id" value="{{ $user->role_id }}">
                            @else
                                <!-- Opción Sin Rol -->
                                <div class="role-selector-card {{ old('role_id', $user->role_id) === null ? 'selected' : '' }}" 
                                     onclick="selectRole('none')">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="role_id" 
                                               id="roleNone" 
                                               value=""
                                               {{ old('role_id', $user->role_id) === null ? 'checked' : '' }}>
                                        <label class="form-check-label" for="roleNone">
                                            <strong>Sin Rol</strong>
                                        </label>
                                    </div>
                                    <div class="role-info">
                                        <small class="text-muted">Usuario con acceso limitado al sistema</small>
                                    </div>
                                </div>

                                @foreach($roles as $role)
                                    <div class="role-selector-card {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}" 
                                         onclick="selectRole({{ $role->id }})">
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="role_id" 
                                                   id="role{{ $role->id }}" 
                                                   value="{{ $role->id }}"
                                                   {{ old('role_id', $user->role_id) == $role->id ? 'checked' : '' }}>
                                            <label class="form-check-label" for="role{{ $role->id }}">
                                                <strong>{{ $role->nombre }}</strong>
                                                @if($role->nombre === 'Superadmin')
                                                    <i class="bi bi-star-fill text-danger"></i>
                                                @elseif($role->nombre === 'Administrador')
                                                    <i class="bi bi-shield-check text-primary"></i>
                                                @elseif($role->nombre === 'Encargado')
                                                    <i class="bi bi-person-badge text-warning"></i>
                                                @else
                                                    <i class="bi bi-person text-secondary"></i>
                                                @endif
                                            </label>
                                        </div>
                                        <div class="role-info">
                                            <small class="text-muted">{{ $role->descripcion }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <hr>
                    @endif

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> 
                        <strong>Cambiar Contraseña:</strong> Solo complete estos campos si desea cambiar la contraseña. 
                        Déjelos en blanco para mantener la contraseña actual.
                    </div>

                    <div class="row">
                        <!-- Password -->
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password"
                                       minlength="8">
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
                            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation"
                                       minlength="8">
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                    <i class="bi bi-eye" id="eyeIconConfirm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Usuario
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
    // NUEVO: Función para seleccionar rol
    function selectRole(roleId) {
        // Deseleccionar todos
        document.querySelectorAll('.role-selector-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Seleccionar el radio button
        let radio;
        if (roleId === 'none') {
            radio = document.getElementById('roleNone');
        } else {
            radio = document.getElementById('role' + roleId);
        }
        
        if (radio) {
            radio.checked = true;
            // Agregar clase selected al card
            radio.closest('.role-selector-card').classList.add('selected');
        }
    }

    // NUEVO: Event listeners para los roles
    document.querySelectorAll('.role-selector-card input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.role-selector-card').forEach(card => {
                card.classList.remove('selected');
            });
            if (this.checked) {
                this.closest('.role-selector-card').classList.add('selected');
            }
        });
    });

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
    document.getElementById('userEditForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirmation').value;
        
        // Solo validar si se está cambiando la contraseña
        if (password || passwordConfirm) {
            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return false;
            }
        }
    });

    // Capitalizar primera letra automáticamente
    ['nombre', 'apellido_paterno', 'apellido_materno'].forEach(function(field) {
        const element = document.getElementById(field);
        if (element) {
            element.addEventListener('blur', function() {
                if (this.value) {
                    this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();
                }
            });
        }
    });
</script>
@endsection