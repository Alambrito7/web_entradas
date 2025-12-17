@extends('layouts.app')

@section('title', 'Recorridos')

@section('styles')
<style>
    .recorrido-card {
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        border: none;
        overflow: hidden;
    }
    
    .recorrido-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .recorrido-header {
        background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
        color: white;
        padding: 20px;
        min-height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    
    .entrada-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
        color: #ec4899;
    }
    
    .puntos-badge {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: bold;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .distancia-badge {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: bold;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .card-gradient-1 { border-left: 4px solid #ec4899; }
    .card-gradient-2 { border-left: 4px solid #8b5cf6; }
    .card-gradient-3 { border-left: 4px solid #06b6d4; }
    .card-gradient-4 { border-left: 4px solid #10b981; }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="mb-1"><i class="bi bi-map"></i> Recorridos</h2>
        <p class="text-muted">Gestión de recorridos de entradas folclóricas</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('recorridos.trashed') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-trash"></i> Recorridos Eliminados
        </a>
        <a href="{{ route('recorridos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Recorrido
        </a>
    </div>
</div>

@if($recorridos->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-map" style="font-size: 5rem; color: #ccc;"></i>
        <h3 class="mt-3 text-muted">No hay recorridos registrados</h3>
        <p class="text-muted">Comienza a documentar los recorridos de las entradas folclóricas</p>
        <a href="{{ route('recorridos.create') }}" class="btn btn-primary btn-lg mt-3">
            <i class="bi bi-plus-circle"></i> Registrar Primer Recorrido
        </a>
    </div>
@else
    <div class="row g-4">
        @foreach($recorridos as $index => $recorrido)
            <div class="col-md-6">
                <div class="card recorrido-card card-gradient-{{ ($index % 4) + 1 }}">
                    <div class="recorrido-header position-relative">
                        <span class="entrada-badge">
                            <i class="bi bi-calendar-event"></i> {{ $recorrido->entrada->nombre }}
                        </span>
                        <h5 class="m-0">
                            {{ $recorrido->nombre ?: 'Recorrido de ' . $recorrido->entrada->nombre }}
                        </h5>
                    </div>
                    
                    <div class="card-body">
                        @if($recorrido->descripcion)
                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit($recorrido->descripcion, 100) }}
                            </p>
                        @endif
                        
                        <div class="d-flex gap-2 mb-3 flex-wrap">
                            <div class="puntos-badge">
                                <i class="bi bi-geo-alt-fill"></i>
                                {{ $recorrido->total_puntos }} Puntos
                            </div>
                            
                            @if($recorrido->distancia_aproximada > 0)
                                <div class="distancia-badge">
                                    <i class="bi bi-rulers"></i>
                                    {{ $recorrido->distancia_aproximada }} km
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <strong class="text-muted small">Entrada:</strong>
                            <p class="mb-1">
                                <a href="{{ route('entradas.show', $recorrido->entrada) }}" class="text-decoration-none">
                                    {{ $recorrido->entrada->nombre }}
                                </a>
                            </p>
                            <small class="text-muted">
                                <i class="bi bi-geo-alt"></i> {{ $recorrido->entrada->departamento }} | 
                                <i class="bi bi-calendar3"></i> {{ $recorrido->entrada->fecha_evento->format('d/m/Y') }}
                            </small>
                        </div>
                        
                        @if($recorrido->puntos->count() > 0)
                            <div class="bg-light rounded p-2">
                                <small class="text-muted"><strong>Puntos del recorrido:</strong></small>
                                <ul class="small mb-0 mt-1">
                                    @foreach($recorrido->puntos->take(3) as $punto)
                                        <li>{{ $punto->nombre }}</li>
                                    @endforeach
                                    @if($recorrido->puntos->count() > 3)
                                        <li class="text-muted">... y {{ $recorrido->puntos->count() - 3 }} más</li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    </div>
                    
                    <div class="card-footer bg-transparent border-0">
                        <div class="d-grid gap-2">
                            <a href="{{ route('recorridos.show', $recorrido) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-eye"></i> Ver Recorrido
                            </a>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('recorridos.edit', $recorrido) }}" class="btn btn-outline-warning">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $recorrido->id }}">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal -->
                <div class="modal fade" id="deleteModal{{ $recorrido->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Confirmar Eliminación</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Eliminar este recorrido?</p>
                                <div class="alert alert-warning">
                                    <strong>{{ $recorrido->nombre ?: 'Recorrido' }}</strong><br>
                                    <small>Entrada: {{ $recorrido->entrada->nombre }}</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <form action="{{ route('recorridos.destroy', $recorrido) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $recorridos->links('pagination::bootstrap-5') }}
    </div>
@endif

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body text-center">
                <i class="bi bi-map text-primary" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ \App\Models\Recorrido::count() }}</h3>
                <p class="text-muted mb-0">Total Recorridos</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body text-center">
                <i class="bi bi-geo-alt-fill text-success" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ \App\Models\RecorridoPunto::count() }}</h3>
                <p class="text-muted mb-0">Total Puntos</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-info">
            <div class="card-body text-center">
                <i class="bi bi-calendar-event text-info" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ \App\Models\Entrada::has('recorridos')->count() }}</h3>
                <p class="text-muted mb-0">Entradas con Recorrido</p>
            </div>
        </div>
    </div>
</div>
@endsection