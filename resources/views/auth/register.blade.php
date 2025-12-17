@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ __('Registro de Usuario') }}</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input id="nombre" type="text" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       name="nombre" 
                                       value="{{ old('nombre') }}" 
                                       pattern="[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+"
                                       required autofocus>
                                @error('nombre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="apellido_paterno" class="form-label">Apellido Paterno <span class="text-danger">*</span></label>
                                <input id="apellido_paterno" type="text" 
                                       class="form-control @error('apellido_paterno') is-invalid @enderror" 
                                       name="apellido_paterno" 
                                       value="{{ old('apellido_paterno') }}" 
                                       pattern="[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+"
                                       required>
                                @error('apellido_paterno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="apellido_materno" class="form-label">Apellido Materno</label>
                                <input id="apellido_materno" type="text" 
                                       class="form-control @error('apellido_materno') is-invalid @enderror" 
                                       name="apellido_materno" 
                                       value="{{ old('apellido_materno') }}" 
                                       pattern="[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+">
                                @error('apellido_materno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ci" class="form-label">CI <span class="text-danger">*</span></label>
                                <input id="ci" type="text" 
                                       class="form-control @error('ci') is-invalid @enderror" 
                                       name="ci" 
                                       value="{{ old('ci') }}" 
                                       pattern="[0-9]{1,8}"
                                       maxlength="8"
                                       required>
                                @error('ci')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="departamento" class="form-label">Departamento <span class="text-danger">*</span></label>
                                <select id="departamento" 
                                        class="form-select @error('departamento') is-invalid @enderror" 
                                        name="departamento" 
                                        required>
                                    <option value="">Seleccione</option>
                                    <option value="La Paz" {{ old('departamento') == 'La Paz' ? 'selected' : '' }}>La Paz</option>
                                    <option value="Cochabamba" {{ old('departamento') == 'Cochabamba' ? 'selected' : '' }}>Cochabamba</option>
                                    <option value="Santa Cruz" {{ old('departamento') == 'Santa Cruz' ? 'selected' : '' }}>Santa Cruz</option>
                                    <option value="Oruro" {{ old('departamento') == 'Oruro' ? 'selected' : '' }}>Oruro</option>
                                    <option value="Potosí" {{ old('departamento') == 'Potosí' ? 'selected' : '' }}>Potosí</option>
                                    <option value="Chuquisaca" {{ old('departamento') == 'Chuquisaca' ? 'selected' : '' }}>Chuquisaca</option>
                                    <option value="Tarija" {{ old('departamento') == 'Tarija' ? 'selected' : '' }}>Tarija</option>
                                    <option value="Beni" {{ old('departamento') == 'Beni' ? 'selected' : '' }}>Beni</option>
                                    <option value="Pando" {{ old('departamento') == 'Pando' ? 'selected' : '' }}>Pando</option>
                                </select>
                                @error('departamento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                <input id="telefono" type="text" 
                                       class="form-control @error('telefono') is-invalid @enderror" 
                                       name="telefono" 
                                       value="{{ old('telefono') }}" 
                                       pattern="[0-9]{8}"
                                       maxlength="8"
                                       required>
                                @error('telefono')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input id="email" type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                <input id="password" type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" 
                                       required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password-confirm" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                                <input id="password-confirm" type="password" 
                                       class="form-control" 
                                       name="password_confirmation" 
                                       required>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    {{ __('Registrarse') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection