@extends('layouts.app')

@section('title', $danza->nombre)

@section('styles')
<style>
    :root {
        --gold: #d4af37;
        --line: rgba(212, 175, 55, .28);
        --radius: 18px;
        --shadow: 0 14px 40px rgba(0, 0, 0, .35);
    }

    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: var(--radius);
        padding: 40px;
        margin-bottom: 30px;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,101.3C1248,85,1344,75,1392,69.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') bottom;
        background-size: cover;
        opacity: 0.3;
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .info-card {
        border: 1px solid var(--line);
        border-radius: var(--radius);
        background: linear-gradient(180deg, rgba(17, 24, 39, .92), rgba(15, 23, 42, .72));
        box-shadow: var(--shadow);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .info-card-header {
        padding: 15px 20px;
        border-bottom: 1px solid rgba(212, 175, 55, .14);
        background: linear-gradient(90deg, rgba(212, 175, 55, .10), transparent 60%);
    }

    .info-card-title {
        font-weight: 700;
        color: var(--gold);
        margin: 0;
        font-size: 18px;
    }

    .info-card-body {
        padding: 20px;
    }

    .info-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid rgba(212, 175, 55, .08);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: var(--gold);
        min-width: 180px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-value {
        flex: 1;
        color: #e5e7eb;
    }

    .badge-custom {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .personaje-card {
        border: 1px solid rgba(212, 175, 55, .18);
        background: rgba(15, 23, 42, .55);
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 20px;
        transition: transform 0.3s;
    }

    .personaje-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(212, 175, 55, .15);
    }

    .personaje-header {
        display: flex;
        gap: 20px;
        align-items: start;
        margin-bottom: 15px;
    }

    .personaje-foto {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        object-fit: cover;
        border: 3px solid var(--gold);
    }

    .personaje-info {
        flex: 1;
    }

    .personaje-nombre {
        font-size: 20px;
        font-weight: 700;
        color: var(--gold);
        margin: 0 0 8px 0;
    }

    .vestimenta-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid rgba(212, 175, 55, .18);
        border-radius: 10px;
        overflow: hidden;
        background: rgba(3, 7, 18, .35);
        margin-top: 12px;
    }

    .vestimenta-table th,
    .vestimenta-table td {
        padding: 10px;
        border-bottom: 1px solid rgba(212, 175, 55, .12);
        font-size: 13px;
    }

    .vestimenta-table th {
        background: rgba(212, 175, 55, .06);
        color: var(--gold);
        font-weight: 600;
        text-align: left;
    }

    .vestimenta-table tr:last-child td {
        border-bottom: none;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .gallery-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 1;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .gallery-item:hover {
        transform: scale(1.05);
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .gallery-item-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        padding: 12px;
        color: white;
    }

    .video-item {
        border: 1px solid rgba(212, 175, 55, .18);
        background: rgba(15, 23, 42, .55);
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
    }

    .video-item iframe {
        width: 100%;
        height: 300px;
        border-radius: 8px;
    }

    .documento-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        border: 1px solid rgba(212, 175, 55, .18);
        background: rgba(15, 23, 42, .55);
        border-radius: 12px;
        margin-bottom: 10px;
        transition: background 0.3s;
    }

    .documento-item:hover {
        background: rgba(15, 23, 42, .75);
    }

    .documento-icon {
        width: 50px;
        height: 50px;
        background: rgba(212, 175, 55, .15);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: var(--gold);
    }

    .documento-info {
        flex: 1;
    }

    .sticky-actions {
        position: sticky;
        top: 20px;
        background: linear-gradient(180deg, rgba(17, 24, 39, .95), rgba(15, 23, 42, .95));
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 20px;
        box-shadow: var(--shadow);
    }

    .action-btn {
        width: 100%;
        margin-bottom: 10px;
    }

    .estado-publicada { background: linear-gradient(135deg, #10b981, #059669); color: white; }
    .estado-revision { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }
    .estado-borrador { background: linear-gradient(135deg, #6b7280, #4b5563); color: white; }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-content">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <span class="badge badge-custom estado-{{ strtolower(str_replace(' ', '-', $danza->estado_ficha)) }}">
                    {{ $danza->estado_ficha }}
                </span>
                <span class="badge badge-custom" style="background: rgba(255,255,255,0.2);">
                    {{ $danza->categoria }}
                </span>
            </div>
            <a href="{{ route('danzas.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
        <h1 class="display-4 fw-bold mb-3">{{ $danza->nombre }}</h1>
        <p class="lead mb-2">
            <i class="bi bi-geo-alt-fill"></i> {{ $danza->departamento_principal }} · {{ $danza->region_origen }}
        </p>
        <p class="mb-0">
            <i class="bi bi-calendar-event"></i> Origen: {{ $danza->origen_formateado }}
        </p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Descripción Corta -->
        @if($danza->descripcion_corta)
        <div class="info-card">
            <div class="info-card-header">
                <h5 class="info-card-title"><i class="bi bi-quote"></i> Descripción</h5>
            </div>
            <div class="info-card-body">
                <p class="mb-0">{{ $danza->descripcion_corta }}</p>
            </div>
        </div>
        @endif

        <!-- Historia y Cultura -->
        @if($danza->historia_origen || $danza->significado_cultural)
        <div class="info-card">
            <div class="info-card-header">
                <h5 class="info-card-title"><i class="bi bi-book"></i> Historia y Cultura</h5>
            </div>
            <div class="info-card-body">
                @if($danza->historia_origen)
                    <h6 class="text-warning mb-2"><i class="bi bi-clock-history"></i> Historia y Origen</h6>
                    <p>{{ $danza->historia_origen }}</p>
                @endif

                @if($danza->significado_cultural)
                    <h6 class="text-warning mb-2 mt-4"><i class="bi bi-star"></i> Significado Cultural</h6>
                    <p class="mb-0">{{ $danza->significado_cultural }}</p>
                @endif
            </div>
        </div>
        @endif

        <!-- Música y Coreografía -->
        @if($danza->instrumentos || $danza->ritmo_compas || $danza->pasos_basicos || $danza->formacion)
        <div class="info-card">
            <div class="info-card-header">
                <h5 class="info-card-title"><i class="bi bi-music-note-beamed"></i> Música y Coreografía</h5>
            </div>
            <div class="info-card-body">
                @if($danza->instrumentos)
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-soundwave"></i> Instrumentos
                        </div>
                        <div class="info-value">{{ $danza->instrumentos }}</div>
                    </div>
                @endif

                @if($danza->ritmo_compas)
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-music-note"></i> Ritmo y Compás
                        </div>
                        <div class="info-value">{{ $danza->ritmo_compas }}</div>
                    </div>
                @endif

                @if($danza->pasos_basicos)
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-person-walking"></i> Pasos Básicos
                        </div>
                        <div class="info-value">{{ $danza->pasos_basicos }}</div>
                    </div>
                @endif

                @if($danza->formacion)
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-people"></i> Formación
                        </div>
                        <div class="info-value">{{ $danza->formacion }}</div>
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Personajes y Vestimenta -->
        @if($danza->personajes->count() > 0)
        <div class="info-card">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="bi bi-person-badge"></i> Personajes y Vestimenta 
                    <span class="badge bg-secondary ms-2">{{ $danza->personajes->count() }}</span>
                </h5>
            </div>
            <div class="info-card-body">
                @foreach($danza->personajes as $personaje)
                    <div class="personaje-card">
                        <div class="personaje-header">
                            @if($personaje->foto)
                                <img src="{{ asset('storage/' . $personaje->foto) }}" 
                                     alt="{{ $personaje->nombre }}" 
                                     class="personaje-foto">
                            @else
                                <div class="personaje-foto d-flex align-items-center justify-content-center bg-secondary">
                                    <i class="bi bi-person" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            
                            <div class="personaje-info">
                                <h5 class="personaje-nombre">{{ $personaje->nombre }}</h5>
                                @if($personaje->descripcion)
                                    <p class="text-muted mb-0">{{ $personaje->descripcion }}</p>
                                @endif
                            </div>
                        </div>

                        @if($personaje->vestimentas->count() > 0)
                            <h6 class="text-warning mt-3 mb-2">
                                <i class="bi bi-bag"></i> Vestimenta ({{ $personaje->vestimentas->count() }} elementos)
                            </h6>
                            <table class="vestimenta-table">
                                <thead>
                                    <tr>
                                        <th>Elemento</th>
                                        <th>Descripción</th>
                                        <th>Material</th>
                                        <th>Peso</th>
                                        <th>Costo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($personaje->vestimentas as $vest)
                                        <tr>
                                            <td><strong>{{ $vest->elemento }}</strong></td>
                                            <td>{{ $vest->descripcion ?? '-' }}</td>
                                            <td>{{ $vest->material ?? '-' }}</td>
                                            <td>{{ $vest->peso ? $vest->peso . ' kg' : '-' }}</td>
                                            <td>{{ $vest->costo ? 'Bs. ' . number_format($vest->costo, 2) : '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Multimedia: Fotos -->
        @php
            $fotos = $danza->multimedia->where('tipo', 'Foto');
            $videos = $danza->multimedia->where('tipo', 'Video');
        @endphp

        @if($fotos->count() > 0)
        <div class="info-card">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="bi bi-images"></i> Galería de Fotos
                    <span class="badge bg-secondary ms-2">{{ $fotos->count() }}</span>
                </h5>
            </div>
            <div class="info-card-body">
                <div class="gallery-grid">
                    @foreach($fotos as $foto)
                        <div class="gallery-item">
                            @if($foto->archivo)
                                <img src="{{ asset('storage/' . $foto->archivo) }}" alt="{{ $foto->titulo }}">
                            @elseif($foto->url)
                                <img src="{{ $foto->url }}" alt="{{ $foto->titulo }}">
                            @endif
                            
                            @if($foto->titulo || $foto->creditos)
                                <div class="gallery-item-overlay">
                                    @if($foto->titulo)
                                        <div class="fw-bold">{{ $foto->titulo }}</div>
                                    @endif
                                    @if($foto->creditos)
                                        <small>{{ $foto->creditos }}</small>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Multimedia: Videos -->
        @if($videos->count() > 0)
        <div class="info-card">
            <div class="info-card-header">
                <h5 class="info-card-title">
                    <i class="bi bi-play-circle"></i> Videos
                    <span class="badge bg-secondary ms-2">{{ $videos->count() }}</span>
                </h5>
            </div>
            <div class="info-card-body">
                @foreach($videos as $video)
                    <div class="video-item">
                        @if($video->titulo)
                            <h6 class="text-warning mb-2">{{ $video->titulo }}</h6>
                        @endif
                        
                        @if($video->url)
                            @php
                                $videoUrl = $video->url;
                                // Convertir URLs de YouTube a embed
                                if(strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false) {
                                    preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\?\/]+)/', $videoUrl, $matches);
                                    if(isset($matches[1])) {
                                        $videoUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                    }
                                }
                            @endphp
                            <iframe src="{{ $videoUrl }}" frameborder="0" allowfullscreen></iframe>
                        @elseif($video->archivo)
                            <video controls class="w-100" style="border-radius: 8px;">
                                <source src="{{ asset('storage/' . $video->archivo) }}" type="video/mp4">
                            </video>
                        @endif
                        
                        @if($video->creditos)
                            <p class="text-muted small mb-0 mt-2">
                                <i class="bi bi-info-circle"></i> {{ $video->creditos }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Patrimonio y Documentos -->
        @if($danza->declaratorias || $danza->documentos->count() > 0)
        <div class="info-card">
            <div class="info-card-header">
                <h5 class="info-card-title"><i class="bi bi-award"></i> Patrimonio</h5>
            </div>
            <div class="info-card-body">
                @if($danza->declaratorias)
                    <h6 class="text-warning mb-2"><i class="bi bi-trophy"></i> Declaratorias Patrimoniales</h6>
                    <p>{{ $danza->declaratorias }}</p>
                @endif

                @if($danza->documentos->count() > 0)
                    <h6 class="text-warning mb-3 mt-4">
                        <i class="bi bi-file-earmark-text"></i> Documentos de Respaldo 
                        <span class="badge bg-secondary ms-2">{{ $danza->documentos->count() }}</span>
                    </h6>
                    
                    @foreach($danza->documentos as $doc)
                        <div class="documento-item">
                            <div class="documento-icon">
                                @if($doc->tipo == 'PDF')
                                    <i class="bi bi-file-pdf"></i>
                                @elseif($doc->tipo == 'Word')
                                    <i class="bi bi-file-word"></i>
                                @else
                                    <i class="bi bi-file-earmark"></i>
                                @endif
                            </div>
                            <div class="documento-info">
                                <h6 class="mb-1">{{ $doc->titulo }}</h6>
                                @if($doc->descripcion)
                                    <p class="text-muted small mb-1">{{ $doc->descripcion }}</p>
                                @endif
                                @if($doc->fuente)
                                    <p class="text-muted small mb-0">
                                        <i class="bi bi-person"></i> {{ $doc->fuente }}
                                    </p>
                                @endif
                            </div>
                            @if($doc->archivo)
                                <a href="{{ asset('storage/' . $doc->archivo) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-download"></i> Descargar
                                </a>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar con acciones -->
    <div class="col-lg-4">
        <div class="sticky-actions">
            <h5 class="text-warning mb-3"><i class="bi bi-gear"></i> Acciones</h5>
            
            <a href="{{ route('danzas.edit', $danza) }}" class="btn btn-warning action-btn">
                <i class="bi bi-pencil"></i> Editar Danza
            </a>
            
            <button type="button" class="btn btn-danger action-btn" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="bi bi-trash"></i> Eliminar Danza
            </button>
            
            <hr class="my-3" style="border-color: var(--line);">
            
            <a href="{{ route('danzas.index') }}" class="btn btn-outline-secondary action-btn">
                <i class="bi bi-arrow-left"></i> Volver al Listado
            </a>

            <hr class="my-3" style="border-color: var(--line);">
            
            <h6 class="text-warning mb-2"><i class="bi bi-info-circle"></i> Información</h6>
            <p class="small text-muted mb-2">
                <strong>Creado:</strong> {{ $danza->created_at->format('d/m/Y H:i') }}
            </p>
            <p class="small text-muted mb-0">
                <strong>Actualizado:</strong> {{ $danza->updated_at->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar esta danza?</p>
                <div class="alert alert-warning">
                    <strong>{{ $danza->nombre }}</strong><br>
                    {{ $danza->categoria }} - {{ $danza->departamento_principal }}
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
@endsection