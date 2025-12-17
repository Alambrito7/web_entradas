@extends('layouts.app')

@section('title', 'Entradas Folclóricas')

@section('styles')
<style>
    .entrada-card {
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        border: none;
        overflow: hidden;
    }
    
    .entrada-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .entrada-img {
        height: 250px;
        object-fit: cover;
        position: relative;
    }
    
    .entrada-img-placeholder {
        height: 250px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .status-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        backdrop-filter: blur(10px);
        z-index: 1;
    }
    
    .status-planificada {
        background: rgba(59, 130, 246, 0.9);
        color: white;
    }
    
    .status-en_curso {
        background: rgba(16, 185, 129, 0.9);
        color: white;
    }
    
    .status-finalizada {
        background: rgba(107, 114, 128, 0.9);
        color: white;
    }
    
    .departamento-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(10px);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
        color: white;
        z-index: 1;
    }
    
    .fecha-badge {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: bold;
    }
    
    .card-gradient-1 { border-left: 4px solid #667eea; }
    .card-gradient-2 { border-left: 4px solid #f093fb; }
    .card-gradient-3 { border-left: 4px solid #4facfe; }
    .card-gradient-4 { border-left: 4px solid #43e97b; }
    .card-gradient-5 { border-left: 4px solid #fa709a; }
    .card-gradient-6 { border-left: 4px solid #30cfd0; }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="mb-1"><i class="bi bi-calendar-event"></i> Entradas Folclóricas</h2>
        <p class="text-muted">Gestión de festividades y eventos culturales de Bolivia</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('entradas.trashed') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-trash"></i> Entradas Eliminadas
        </a>
        <a href="{{ route('entradas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Entrada
        </a>
    </div>
</div>

<!-- Filtros rápidos -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <select class="form-select" id="filterDepartamento">
                    <option value="">Todos los departamentos</option>
                    @foreach(\App\Models\Entrada::getDepartamentos() as $dep)
                        <option value="{{ $dep }}">{{ $dep }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterEstado">
                    <option value="">Todos los estados</option>
                    <option value="planificada">Planificada</option>
                    <option value="en_curso">En Curso</option>
                    <option value="finalizada">Finalizada</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterMes">
                    <option value="">Todos los meses</option>
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="searchEntrada" placeholder="Buscar entrada...">
            </div>
        </div>
    </div>
</div>

@if($entradas->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-calendar-x" style="font-size: 5rem; color: #ccc;"></i>
        <h3 class="mt-3 text-muted">No hay entradas registradas</h3>
        <p class="text-muted">Comienza a documentar las festividades folclóricas de Bolivia</p>
        <a href="{{ route('entradas.create') }}" class="btn btn-primary btn-lg mt-3">
            <i class="bi bi-plus-circle"></i> Registrar Primera Entrada
        </a>
    </div>
@else
    <div class="row g-4" id="entradasContainer">
        @foreach($entradas as $index => $entrada)
            <div class="col-md-4 entrada-item" 
                 data-departamento="{{ $entrada->departamento }}" 
                 data-estado="{{ $entrada->status }}"
                 data-mes="{{ $entrada->fecha_evento->format('m') }}"
                 data-nombre="{{ strtolower($entrada->nombre) }}">
                <div class="card entrada-card card-gradient-{{ ($index % 6) + 1 }}">
                    <div class="position-relative">
                        @if($entrada->imagen)
                            <img src="{{ asset('storage/' . $entrada->imagen) }}" 
                                 class="card-img-top entrada-img" 
                                 alt="{{ $entrada->nombre }}">
                        @else
                            <div class="entrada-img-placeholder">
                                <i class="bi bi-calendar-event text-white" style="font-size: 5rem; opacity: 0.3;"></i>
                            </div>
                        @endif
                        
                        <span class="departamento-badge">
                            <i class="bi bi-geo-alt"></i> {{ $entrada->departamento }}
                        </span>
                        
                        <span class="status-badge status-{{ $entrada->status }}">
                            @if($entrada->status === 'planificada')
                                <i class="bi bi-calendar-check"></i> Planificada
                            @elseif($entrada->status === 'en_curso')
                                <i class="bi bi-broadcast"></i> En Curso
                            @else
                                <i class="bi bi-check-circle"></i> Finalizada
                            @endif
                        </span>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-2">{{ $entrada->nombre }}</h5>
                        
                        @if($entrada->santo)
                            <p class="card-text text-muted small mb-2">
                                <i class="bi bi-star"></i> {{ $entrada->santo }}
                            </p>
                        @endif
                        
                        <div class="mb-2">
                            <span class="fecha-badge">
                                <i class="bi bi-calendar3"></i> 
                                {{ $entrada->fecha_evento->format('d/m/Y') }}
                            </span>
                        </div>
                        
                        @if($entrada->descripcion)
                            <p class="card-text small text-muted">
                                {{ Str::limit($entrada->descripcion, 100) }}
                            </p>
                        @endif
                        
                        <div class="d-flex gap-2 mt-3">
                            @if($entrada->tiene_ubicacion)
                                <span class="badge bg-success bg-opacity-75">
                                    <i class="bi bi-geo"></i> Con ubicación
                                </span>
                            @endif
                            
                            @if($entrada->fecha_fundacion)
                                <span class="badge bg-info bg-opacity-75">
                                    <i class="bi bi-clock-history"></i> 
                                    Desde {{ $entrada->fecha_fundacion->format('Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent border-0">
                        <div class="d-grid gap-2">
                            <a href="{{ route('entradas.show', $entrada) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-eye"></i> Ver Detalles
                            </a>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('entradas.edit', $entrada) }}" class="btn btn-outline-warning">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal{{ $entrada->id }}">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal de Confirmación -->
                <div class="modal fade" id="deleteModal{{ $entrada->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">
                                    <i class="bi bi-exclamation-triangle"></i> Confirmar Eliminación
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Está seguro que desea eliminar esta entrada folclórica?</p>
                                <div class="alert alert-warning">
                                    <strong>{{ $entrada->nombre }}</strong><br>
                                    <small>{{ $entrada->departamento }} - {{ $entrada->fecha_evento->format('d/m/Y') }}</small>
                                </div>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-info-circle"></i> 
                                    La entrada se moverá a la papelera y podrá ser restaurada posteriormente.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancelar
                                </button>
                                <form action="{{ route('entradas.destroy', $entrada) }}" method="POST" class="d-inline">
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
        {{ $entradas->links('pagination::bootstrap-5') }}
    </div>

    <div class="mt-3">
        <p class="text-muted small">
            <i class="bi bi-info-circle"></i> 
            Mostrando {{ $entradas->count() }} de {{ $entradas->total() }} entradas registradas
        </p>
    </div>
@endif

<!-- Estadísticas rápidas -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body text-center">
                <i class="bi bi-calendar-check text-primary" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ \App\Models\Entrada::where('status', 'planificada')->count() }}</h3>
                <p class="text-muted mb-0">Planificadas</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body text-center">
                <i class="bi bi-broadcast text-success" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ \App\Models\Entrada::where('status', 'en_curso')->count() }}</h3>
                <p class="text-muted mb-0">En Curso</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-secondary">
            <div class="card-body text-center">
                <i class="bi bi-check-circle text-secondary" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ \App\Models\Entrada::where('status', 'finalizada')->count() }}</h3>
                <p class="text-muted mb-0">Finalizadas</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Filtros dinámicos
    const filterDepartamento = document.getElementById('filterDepartamento');
    const filterEstado = document.getElementById('filterEstado');
    const filterMes = document.getElementById('filterMes');
    const searchEntrada = document.getElementById('searchEntrada');
    const entradaItems = document.querySelectorAll('.entrada-item');

    function filterEntradas() {
        const departamentoValue = filterDepartamento.value.toLowerCase();
        const estadoValue = filterEstado.value.toLowerCase();
        const mesValue = filterMes.value;
        const searchValue = searchEntrada.value.toLowerCase();

        entradaItems.forEach(item => {
            const departamento = item.dataset.departamento.toLowerCase();
            const estado = item.dataset.estado.toLowerCase();
            const mes = item.dataset.mes;
            const nombre = item.dataset.nombre;

            const matchDepartamento = !departamentoValue || departamento === departamentoValue;
            const matchEstado = !estadoValue || estado === estadoValue;
            const matchMes = !mesValue || mes === mesValue;
            const matchSearch = !searchValue || nombre.includes(searchValue);

            if (matchDepartamento && matchEstado && matchMes && matchSearch) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    filterDepartamento.addEventListener('change', filterEntradas);
    filterEstado.addEventListener('change', filterEntradas);
    filterMes.addEventListener('change', filterEntradas);
    searchEntrada.addEventListener('input', filterEntradas);
</script>
@endsection