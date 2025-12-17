@extends('layouts.app')

@section('title', 'Bloques')

@section('styles')
<style>
    .bloque-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .bloque-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .bloque-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .bloque-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .bloque-body {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .bloque-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .bloque-info {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }

    .bloque-footer {
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }

    .stats-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #f3f4f6;
        border-radius: 8px;
        font-size: 0.875rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .no-image-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 4rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-grid-3x3"></i> Gestión de Bloques</h2>
            <p class="text-muted">Administra los bloques de las fraternidades</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('bloques.trashed') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-trash"></i> Bloques Eliminados
            </a>
            <a href="{{ route('bloques.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Bloque
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('bloques.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Buscar</label>
                        <input type="text" class="form-control" name="search" placeholder="Nombre del bloque..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select class="form-select" name="estado">
                            <option value="">Todos</option>
                            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fraternidad</label>
                        <select class="form-select" name="fraternidad_id">
                            <option value="">Todas</option>
                            @foreach(\App\Models\Fraternidad::orderBy('nombre')->get() as $frat)
                                <option value="{{ $frat->id }}" {{ request('fraternidad_id') == $frat->id ? 'selected' : '' }}>
                                    {{ $frat->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($bloques->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-grid-3x3" style="font-size: 5rem; color: #ccc;"></i>
            <h3 class="mt-3 text-muted">No hay bloques registrados</h3>
            <p class="text-muted">Comienza creando el primer bloque</p>
            <a href="{{ route('bloques.create') }}" class="btn btn-primary btn-lg mt-3">
                <i class="bi bi-plus-circle"></i> Crear Primer Bloque
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach($bloques as $bloque)
                <div class="col-lg-4 col-md-6">
                    <div class="bloque-card">
                        <!-- Imagen -->
                        <div class="bloque-image">
                            @if($bloque->foto_principal)
                                <img src="{{ Storage::url($bloque->foto_principal) }}" alt="{{ $bloque->nombre }}">
                            @else
                                <div class="no-image-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Contenido -->
                        <div class="bloque-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="bloque-title mb-0">{{ $bloque->nombre }}</h5>
                                @if($bloque->estado === 'activo')
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </div>

                            @if($bloque->fraternidad)
                                <div class="bloque-info">
                                    <i class="bi bi-people-fill text-primary"></i>
                                    {{ $bloque->fraternidad->nombre }}
                                </div>
                            @endif

                            @if($bloque->lema)
                                <p class="text-muted small fst-italic mb-3">
                                    "{{ Str::limit($bloque->lema, 60) }}"
                                </p>
                            @endif

                            <!-- Estadísticas -->
                            <div class="mb-3">
                                <span class="stats-badge">
                                    <i class="bi bi-people text-primary"></i>
                                    <strong>{{ $bloque->integrantes_aproximados }}</strong> integrantes
                                </span>
                                <span class="stats-badge">
                                    <i class="bi bi-person-badge text-warning"></i>
                                    <strong>{{ $bloque->responsables_count }}</strong> responsables
                                </span>
                            </div>

                            @if($bloque->fecha_fundacion)
                                <p class="small text-muted mb-0">
                                    <i class="bi bi-calendar-event"></i>
                                    Fundado: {{ $bloque->fecha_fundacion->format('d/m/Y') }}
                                </p>
                            @endif

                            <!-- Footer con acciones -->
                            <div class="bloque-footer">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('bloques.show', $bloque) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Ver Detalles
                                    </a>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('bloques.edit', $bloque) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $bloque->id }}">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de Confirmación de Eliminación -->
                <div class="modal fade" id="deleteModal{{ $bloque->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">
                                    <i class="bi bi-exclamation-triangle"></i> Confirmar Eliminación
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Está seguro que desea eliminar el bloque <strong>{{ $bloque->nombre }}</strong>?</p>
                                <p class="text-muted mb-0">
                                    <small>Esta acción se puede revertir desde la papelera.</small>
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <form action="{{ route('bloques.destroy', $bloque) }}" method="POST">
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
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $bloques->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection