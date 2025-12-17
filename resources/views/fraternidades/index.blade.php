@extends('layouts.app')

@section('title', 'Fraternidades')

@section('styles')
<style>
    .fraternidad-card {
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        border: none;
        overflow: hidden;
    }
    
    .fraternidad-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .fraternidad-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    
    .fraternidad-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0;
    }
    
    .danza-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
        color: #667eea;
    }
    
    .pasantes-badge {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: bold;
    }
    
    .entradas-badge {
        background: linear-gradient(135deg, #10b981, #059669);
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

    .info-icon {
        width: 30px;
        height: 30px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="mb-1"><i class="bi bi-people-fill"></i> Fraternidades</h2>
        <p class="text-muted">Gestión de fraternidades folclóricas de Bolivia</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('fraternidades.trashed') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-trash"></i> Fraternidades Eliminadas
        </a>
        <a href="{{ route('fraternidades.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Fraternidad
        </a>
    </div>
</div>

<!-- Filtros rápidos -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <select class="form-select" id="filterDanza">
                    <option value="">Todas las danzas</option>
                    @foreach(\App\Models\Danza::orderBy('nombre')->get() as $danza)
                        <option value="{{ $danza->id }}">{{ $danza->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="filterEntrada">
                    <option value="">Todas las entradas</option>
                    @foreach(\App\Models\Entrada::orderBy('nombre')->get() as $entrada)
                        <option value="{{ $entrada->id }}">{{ $entrada->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="searchFraternidad" placeholder="Buscar fraternidad...">
            </div>
        </div>
    </div>
</div>

@if($fraternidades->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-people" style="font-size: 5rem; color: #ccc;"></i>
        <h3 class="mt-3 text-muted">No hay fraternidades registradas</h3>
        <p class="text-muted">Comienza a documentar las fraternidades folclóricas de Bolivia</p>
        <a href="{{ route('fraternidades.create') }}" class="btn btn-primary btn-lg mt-3">
            <i class="bi bi-plus-circle"></i> Registrar Primera Fraternidad
        </a>
    </div>
@else
    <div class="row g-4" id="fraternidadesContainer">
        @foreach($fraternidades as $index => $fraternidad)
            <div class="col-md-4 fraternidad-item" 
                 data-danza="{{ $fraternidad->danza_id ?? '' }}"
                 data-entradas="{{ $fraternidad->entradas->pluck('id')->join(',') }}"
                 data-nombre="{{ strtolower($fraternidad->nombre) }}">
                <div class="card fraternidad-card card-gradient-{{ ($index % 6) + 1 }}">
                    <div class="fraternidad-header position-relative">
                        @if($fraternidad->danza)
                            <span class="danza-badge">
                                <i class="bi bi-music-note"></i> {{ $fraternidad->danza->nombre }}
                            </span>
                        @endif
                        <h5 class="fraternidad-title">{{ $fraternidad->nombre }}</h5>
                    </div>
                    
                    <div class="card-body">
                        @if($fraternidad->lema)
                            <p class="card-text text-muted fst-italic small mb-3">
                                <i class="bi bi-quote"></i> {{ $fraternidad->lema }}
                            </p>
                        @endif
                        
                        <div class="mb-3">
                            @if($fraternidad->fecha_fundacion)
                                <div class="d-flex align-items-center mb-2">
                                    <span class="info-icon me-2">
                                        <i class="bi bi-calendar"></i>
                                    </span>
                                    <div>
                                        <small class="text-muted d-block">Fundación</small>
                                        <strong>{{ $fraternidad->fecha_fundacion->format('Y') }}</strong>
                                        <small class="text-muted">({{ $fraternidad->anios_fundacion }} años)</small>
                                    </div>
                                </div>
                            @endif
                            
                            @if($fraternidad->telefono)
                                <div class="d-flex align-items-center mb-2">
                                    <span class="info-icon me-2">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <div>
                                        <small class="text-muted d-block">Teléfono</small>
                                        <strong>{{ $fraternidad->telefono }}</strong>
                                    </div>
                                </div>
                            @endif
                            
                            @if($fraternidad->correo_electronico)
                                <div class="d-flex align-items-center">
                                    <span class="info-icon me-2">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <div>
                                        <small class="text-muted d-block">Email</small>
                                        <strong class="small">{{ $fraternidad->correo_electronico }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        @if($fraternidad->descripcion)
                            <p class="card-text small text-muted">
                                {{ Str::limit($fraternidad->descripcion, 100) }}
                            </p>
                        @endif
                        
                        <div class="d-flex gap-2 mt-3 flex-wrap">
                            @if($fraternidad->pasantes->count() > 0)
                                <span class="pasantes-badge">
                                    <i class="bi bi-person-badge"></i> 
                                    {{ $fraternidad->pasantes->count() }} Pasante{{ $fraternidad->pasantes->count() > 1 ? 's' : '' }}
                                </span>
                            @endif
                            
                            @if($fraternidad->entradas->count() > 0)
                                <span class="entradas-badge">
                                    <i class="bi bi-calendar-event"></i> 
                                    {{ $fraternidad->entradas->count() }} Entrada{{ $fraternidad->entradas->count() > 1 ? 's' : '' }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent border-0">
                        <div class="d-grid gap-2">
                            <a href="{{ route('fraternidades.show', $fraternidad) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-eye"></i> Ver Detalles
                            </a>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('fraternidades.edit', $fraternidad) }}" class="btn btn-outline-warning">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal{{ $fraternidad->id }}">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal de Confirmación -->
                <div class="modal fade" id="deleteModal{{ $fraternidad->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">
                                    <i class="bi bi-exclamation-triangle"></i> Confirmar Eliminación
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Está seguro que desea eliminar esta fraternidad?</p>
                                <div class="alert alert-warning">
                                    <strong>{{ $fraternidad->nombre }}</strong><br>
                                    @if($fraternidad->danza)
                                        <small>Danza: {{ $fraternidad->danza->nombre }}</small>
                                    @endif
                                </div>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-info-circle"></i> 
                                    La fraternidad se moverá a la papelera y podrá ser restaurada posteriormente.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancelar
                                </button>
                                <form action="{{ route('fraternidades.destroy', $fraternidad) }}" method="POST" class="d-inline">
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
        {{ $fraternidades->links('pagination::bootstrap-5') }}
    </div>

    <div class="mt-3">
        <p class="text-muted small">
            <i class="bi bi-info-circle"></i> 
            Mostrando {{ $fraternidades->count() }} de {{ $fraternidades->total() }} fraternidades registradas
        </p>
    </div>
@endif

<!-- Estadísticas rápidas -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body text-center">
                <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ \App\Models\Fraternidad::count() }}</h3>
                <p class="text-muted mb-0">Total Fraternidades</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-warning">
            <div class="card-body text-center">
                <i class="bi bi-person-badge text-warning" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ \App\Models\FraternidadPasante::count() }}</h3>
                <p class="text-muted mb-0">Total Pasantes</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body text-center">
                <i class="bi bi-music-note-beamed text-success" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ \App\Models\Danza::has('fraternidades')->count() }}</h3>
                <p class="text-muted mb-0">Danzas Representadas</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Filtros dinámicos
    const filterDanza = document.getElementById('filterDanza');
    const filterEntrada = document.getElementById('filterEntrada');
    const searchFraternidad = document.getElementById('searchFraternidad');
    const fraternidadItems = document.querySelectorAll('.fraternidad-item');

    function filterFraternidades() {
        const danzaValue = filterDanza.value;
        const entradaValue = filterEntrada.value;
        const searchValue = searchFraternidad.value.toLowerCase();

        fraternidadItems.forEach(item => {
            const danza = item.dataset.danza;
            const entradas = item.dataset.entradas.split(',');
            const nombre = item.dataset.nombre;

            const matchDanza = !danzaValue || danza === danzaValue;
            const matchEntrada = !entradaValue || entradas.includes(entradaValue);
            const matchSearch = !searchValue || nombre.includes(searchValue);

            if (matchDanza && matchEntrada && matchSearch) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    filterDanza.addEventListener('change', filterFraternidades);
    filterEntrada.addEventListener('change', filterFraternidades);
    searchFraternidad.addEventListener('input', filterFraternidades);
</script>
@endsection