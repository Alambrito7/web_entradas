@extends('layouts.app')

@section('title', 'Danzas Folclóricas')

@section('styles')
<style>
    .danza-card {
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        border: none;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .danza-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .danza-img {
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    }
    .categoria-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(10px);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
    }
    .estado-badge {
        position: absolute;
        top: 10px;
        left: 10px;
    }
    .card-gradient-1 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .card-gradient-2 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .card-gradient-3 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .card-gradient-4 { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
    .card-gradient-5 { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
    .card-gradient-6 { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); }
    .card-gradient-7 { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
    .card-gradient-8 { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="mb-1"><i class="bi bi-music-note-beamed"></i> Danzas Folclóricas de Bolivia</h2>
        <p class="text-muted">Registro y gestión del patrimonio cultural inmaterial</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('danzas.trashed') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-trash"></i> Danzas Eliminadas
        </a>
        <a href="{{ route('danzas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Danza
        </a>
    </div>
</div>

<!-- Filtros rápidos -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <select class="form-select" id="filterCategoria">
                    <option value="">Todas las categorías</option>
                    @foreach(\App\Models\Danza::getCategorias() as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterDepartamento">
                    <option value="">Todos los departamentos</option>
                    @foreach(\App\Models\Danza::getDepartamentos() as $dep)
                        <option value="{{ $dep }}">{{ $dep }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterEstado">
                    <option value="">Todos los estados</option>
                    <option value="Borrador">Borrador</option>
                    <option value="En revisión">En revisión</option>
                    <option value="Publicada">Publicada</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="searchDanza" placeholder="Buscar danza...">
            </div>
        </div>
    </div>
</div>

@if($danzas->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-music-note" style="font-size: 5rem; color: #ccc;"></i>
        <h3 class="mt-3 text-muted">No hay danzas registradas</h3>
        <p class="text-muted">Comienza a documentar el patrimonio cultural de Bolivia</p>
        <a href="{{ route('danzas.create') }}" class="btn btn-primary btn-lg mt-3">
            <i class="bi bi-plus-circle"></i> Registrar Primera Danza
        </a>
    </div>
@else
    <div class="row g-4" id="danzasContainer">
        @foreach($danzas as $index => $danza)
            <div class="col-md-4 danza-item" 
                 data-categoria="{{ $danza->categoria }}" 
                 data-departamento="{{ $danza->departamento_principal }}"
                 data-estado="{{ $danza->estado_ficha }}"
                 data-nombre="{{ strtolower($danza->nombre) }}">
                <div class="card danza-card card-gradient-{{ ($index % 8) + 1 }}">
                    <div class="position-relative">
                        @php
                            $primeraFoto = $danza->multimedia->where('tipo', 'Foto')->first();
                        @endphp
                        
                        @if($primeraFoto && $primeraFoto->archivo)
                            <img src="{{ asset('storage/' . $primeraFoto->archivo) }}" 
                                 class="card-img-top danza-img" 
                                 alt="{{ $danza->nombre }}">
                        @elseif($primeraFoto && $primeraFoto->url)
                            <img src="{{ $primeraFoto->url }}" 
                                 class="card-img-top danza-img" 
                                 alt="{{ $danza->nombre }}">
                        @else
                            <div class="danza-img d-flex align-items-center justify-content-center">
                                <i class="bi bi-music-note-beamed" style="font-size: 4rem; opacity: 0.3;"></i>
                            </div>
                        @endif
                        
                        <span class="categoria-badge">{{ $danza->categoria }}</span>
                        
                        @if($danza->estado_ficha === 'Publicada')
                            <span class="badge bg-success estado-badge">
                                <i class="bi bi-check-circle"></i> Publicada
                            </span>
                        @elseif($danza->estado_ficha === 'En revisión')
                            <span class="badge bg-warning estado-badge">
                                <i class="bi bi-clock"></i> En revisión
                            </span>
                        @else
                            <span class="badge bg-secondary estado-badge">
                                <i class="bi bi-pencil"></i> Borrador
                            </span>
                        @endif
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-2">{{ $danza->nombre }}</h5>
                        
                        <div class="mb-2">
                            <small>
                                <i class="bi bi-geo-alt"></i> {{ $danza->departamento_principal }} · {{ $danza->region_origen }}
                            </small>
                        </div>
                        
                        <div class="mb-2">
                            <small>
                                <i class="bi bi-calendar"></i> {{ $danza->origen_formateado }}
                            </small>
                        </div>
                        
                        @if($danza->descripcion_corta)
                            <p class="card-text small" style="opacity: 0.9;">
                                {{ Str::limit($danza->descripcion_corta, 100) }}
                            </p>
                        @endif
                        
                        <div class="d-flex gap-2 mt-3">
                            <span class="badge bg-dark bg-opacity-50">
                                <i class="bi bi-people"></i> {{ $danza->personajes->count() }} personajes
                            </span>
                            <span class="badge bg-dark bg-opacity-50">
                                <i class="bi bi-images"></i> {{ $danza->multimedia->count() }} multimedia
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <div class="d-grid gap-2">
                            <a href="{{ route('danzas.show', $danza) }}" class="btn btn-light btn-sm">
                                <i class="bi bi-eye"></i> Ver Detalles
                            </a>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('danzas.edit', $danza) }}" class="btn btn-outline-light">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-light" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal{{ $danza->id }}">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal de Confirmación -->
                <div class="modal fade" id="deleteModal{{ $danza->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">
                                    <i class="bi bi-exclamation-triangle"></i> Confirmar Eliminación
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Está seguro que desea eliminar la danza?</p>
                                <div class="alert alert-warning">
                                    <strong>{{ $danza->nombre }}</strong><br>
                                    <small>{{ $danza->categoria }} - {{ $danza->departamento_principal }}</small>
                                </div>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-info-circle"></i> 
                                    La danza se moverá a la papelera y podrá ser restaurada posteriormente.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancelar
                                </button>
                                <form action="{{ route('danzas.destroy', $danza) }}" method="POST" class="d-inline">
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
            </div>
        @endforeach
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4">
        {{ $danzas->links('pagination::bootstrap-5') }}
    </div>

    <div class="mt-3">
        <p class="text-muted small">
            <i class="bi bi-info-circle"></i> 
            Mostrando {{ $danzas->count() }} de {{ $danzas->total() }} danzas registradas
        </p>
    </div>
@endif
@endsection

@section('scripts')
<script>
    // Filtros dinámicos
    const filterCategoria = document.getElementById('filterCategoria');
    const filterDepartamento = document.getElementById('filterDepartamento');
    const filterEstado = document.getElementById('filterEstado');
    const searchDanza = document.getElementById('searchDanza');
    const danzaItems = document.querySelectorAll('.danza-item');

    function filterDanzas() {
        const categoriaValue = filterCategoria.value.toLowerCase();
        const departamentoValue = filterDepartamento.value.toLowerCase();
        const estadoValue = filterEstado.value.toLowerCase();
        const searchValue = searchDanza.value.toLowerCase();

        danzaItems.forEach(item => {
            const categoria = item.dataset.categoria.toLowerCase();
            const departamento = item.dataset.departamento.toLowerCase();
            const estado = item.dataset.estado.toLowerCase();
            const nombre = item.dataset.nombre;

            const matchCategoria = !categoriaValue || categoria === categoriaValue;
            const matchDepartamento = !departamentoValue || departamento === departamentoValue;
            const matchEstado = !estadoValue || estado === estadoValue;
            const matchSearch = !searchValue || nombre.includes(searchValue);

            if (matchCategoria && matchDepartamento && matchEstado && matchSearch) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    filterCategoria.addEventListener('change', filterDanzas);
    filterDepartamento.addEventListener('change', filterDanzas);
    filterEstado.addEventListener('change', filterDanzas);
    searchDanza.addEventListener('input', filterDanzas);
</script>
@endsection