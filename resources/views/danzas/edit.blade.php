@extends('layouts.app')

@section('title', 'Editar Danza')

@section('styles')
<style>
    :root {
        --bg: #07080c;
        --panel: #0f172a;
        --panel2: #111827;
        --text: #e5e7eb;
        --muted: #9ca3af;
        --gold: #d4af37;
        --line: rgba(212, 175, 55, .28);
        --radius: 18px;
        --shadow: 0 14px 40px rgba(0, 0, 0, .35);
    }

    .form-section {
        border: 1px solid var(--line);
        border-radius: var(--radius);
        background: linear-gradient(180deg, rgba(17, 24, 39, .92), rgba(15, 23, 42, .72));
        box-shadow: var(--shadow);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .section-header {
        padding: 15px 20px;
        border-bottom: 1px solid rgba(212, 175, 55, .14);
        background: linear-gradient(90deg, rgba(212, 175, 55, .10), transparent 60%);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-title {
        font-weight: 700;
        letter-spacing: .2px;
        color: var(--gold);
        margin: 0;
    }

    .pill {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        border: 1px solid rgba(212, 175, 55, .30);
        background: rgba(212, 175, 55, .08);
        color: var(--gold);
        font-size: 11px;
        font-weight: 900;
    }

    .section-body {
        padding: 20px;
    }

    .dynamic-block {
        border: 1px solid rgba(212, 175, 55, .18);
        background: rgba(15, 23, 42, .55);
        border-radius: 16px;
        padding: 15px;
        margin-top: 15px;
        position: relative;
    }

    .block-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .block-title {
        font-weight: 700;
        color: var(--gold);
        font-size: 14px;
    }

    .btn-gold {
        background: linear-gradient(90deg, #f59e0b, rgba(245, 158, 11, .65));
        color: #0b0c10;
        border: 0;
        font-weight: 700;
    }

    .btn-gold:hover {
        background: #f59e0b;
        color: #0b0c10;
    }

    .btn-outline-gold {
        border: 1px solid rgba(212, 175, 55, .35);
        background: rgba(212, 175, 55, .10);
        color: var(--text);
    }

    .btn-outline-gold:hover {
        background: rgba(212, 175, 55, .20);
        color: var(--text);
    }

    .mini-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid rgba(212, 175, 55, .18);
        border-radius: 12px;
        overflow: hidden;
        background: rgba(3, 7, 18, .35);
        margin-top: 10px;
    }

    .mini-table th,
    .mini-table td {
        padding: 8px;
        border-bottom: 1px solid rgba(212, 175, 55, .12);
        font-size: 13px;
    }

    .mini-table th {
        color: var(--gold);
        background: rgba(212, 175, 55, .06);
        font-weight: 600;
    }

    .mini-table tr:last-child td {
        border-bottom: none;
    }

    .mini-table input,
    .mini-table select {
        background: rgba(3, 7, 18, .55);
        border: 1px solid rgba(212, 175, 55, .22);
        color: var(--text);
        padding: 6px 8px;
        border-radius: 8px;
        width: 100%;
        font-size: 12px;
    }

    .remove-btn {
        padding: 4px 8px;
        font-size: 12px;
        border-radius: 8px;
        border: 1px solid rgba(239, 68, 68, .35);
        background: rgba(239, 68, 68, .12);
    }

    .sticky-actions {
        position: sticky;
        bottom: 0;
        background: linear-gradient(180deg, rgba(17, 24, 39, .95), rgba(15, 23, 42, .95));
        border-top: 1px solid var(--line);
        padding: 15px 20px;
        margin-top: 20px;
        border-radius: var(--radius);
        box-shadow: 0 -5px 20px rgba(0, 0, 0, .3);
        z-index: 10;
    }

    .preview-image {
        max-width: 100px;
        max-height: 100px;
        border-radius: 8px;
        margin-top: 5px;
    }
</style>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <h2><i class="bi bi-pencil-square"></i> Editar Danza: {{ $danza->nombre }}</h2>
        <p class="text-muted">Actualice la información de la danza folclórica</p>
    </div>
</div>

<form action="{{ route('danzas.update', $danza) }}" method="POST" enctype="multipart/form-data" id="danzaForm">
    @csrf
    @method('PUT')

    <!-- A) Datos Básicos -->
    <section class="form-section">
        <div class="section-header">
            <h5 class="section-title"><i class="bi bi-card-list"></i> Datos Básicos</h5>
            <span class="pill">Obligatorio</span>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Nombre de la danza <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                           value="{{ old('nombre', $danza->nombre) }}" placeholder="Ej: Morenada, Diablada, Tinku..." required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Categoría / Tipo <span class="text-danger">*</span></label>
                    <select name="categoria" class="form-select @error('categoria') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat }}" {{ old('categoria', $danza->categoria) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('categoria')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Departamento Principal <span class="text-danger">*</span></label>
                    <select name="departamento_principal" class="form-select @error('departamento_principal') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        @foreach($departamentos as $dep)
                            <option value="{{ $dep }}" {{ old('departamento_principal', $danza->departamento_principal) == $dep ? 'selected' : '' }}>{{ $dep }}</option>
                        @endforeach
                    </select>
                    @error('departamento_principal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Región de Origen <span class="text-danger">*</span></label>
                    <input type="text" name="region_origen" class="form-control @error('region_origen') is-invalid @enderror" 
                           value="{{ old('region_origen', $danza->region_origen) }}" placeholder="Ej: Altiplano, Valle..." required>
                    @error('region_origen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tipo de Fecha de Origen <span class="text-danger">*</span></label>
                    <select name="tipo_fecha_origen" id="tipo_fecha_origen" class="form-select" required>
                        <option value="siglo" {{ old('tipo_fecha_origen', $danza->tipo_fecha_origen) == 'siglo' ? 'selected' : '' }}>Siglo (aprox.)</option>
                        <option value="anio_aprox" {{ old('tipo_fecha_origen', $danza->tipo_fecha_origen) == 'anio_aprox' ? 'selected' : '' }}>Año aproximado</option>
                        <option value="rango" {{ old('tipo_fecha_origen', $danza->tipo_fecha_origen) == 'rango' ? 'selected' : '' }}>Rango de años</option>
                        <option value="exacta" {{ old('tipo_fecha_origen', $danza->tipo_fecha_origen) == 'exacta' ? 'selected' : '' }}>Fecha exacta</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3" id="origenDynamic">
                    <!-- Se llenará dinámicamente con JavaScript -->
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Estado de Ficha <span class="text-danger">*</span></label>
                    <select name="estado_ficha" class="form-select" required>
                        @foreach($estadosFicha as $estado)
                            <option value="{{ $estado }}" {{ old('estado_ficha', $danza->estado_ficha) == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Descripción Corta (para tarjetas)</label>
                    <textarea name="descripcion_corta" class="form-control" rows="3" 
                              placeholder="Resumen breve (300-500 caracteres)">{{ old('descripcion_corta', $danza->descripcion_corta) }}</textarea>
                    <small class="text-muted">Esta descripción aparecerá en las tarjetas de vista previa</small>
                </div>
            </div>
        </div>
    </section>

    <!-- B) Historia y Cultura -->
    <section class="form-section">
        <div class="section-header">
            <h5 class="section-title"><i class="bi bi-book"></i> Historia y Cultura</h5>
            <span class="pill">Resumido</span>
        </div>
        <div class="section-body">
            <div class="mb-3">
                <label class="form-label">Historia y Origen</label>
                <textarea name="historia_origen" class="form-control" rows="4" 
                          placeholder="Origen, contexto histórico, evolución de la danza...">{{ old('historia_origen', $danza->historia_origen) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Significado Cultural</label>
                <textarea name="significado_cultural" class="form-control" rows="4" 
                          placeholder="Qué representa, identidad, símbolos, importancia en la comunidad...">{{ old('significado_cultural', $danza->significado_cultural) }}</textarea>
            </div>
        </div>
    </section>

    <!-- C) Música y Coreografía -->
    <section class="form-section">
        <div class="section-header">
            <h5 class="section-title"><i class="bi bi-music-note"></i> Música y Coreografía</h5>
            <span class="pill">Básico</span>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Instrumentos Característicos</label>
                    <input type="text" name="instrumentos" class="form-control" 
                           value="{{ old('instrumentos', $danza->instrumentos) }}" placeholder="Ej: banda, bronces, bombos, sikus...">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Ritmo y Compás</label>
                    <input type="text" name="ritmo_compas" class="form-control" 
                           value="{{ old('ritmo_compas', $danza->ritmo_compas) }}" placeholder="Descripción breve del ritmo">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pasos Básicos</label>
                    <textarea name="pasos_basicos" class="form-control" rows="3" 
                              placeholder="Descripción de los pasos y movimientos principales">{{ old('pasos_basicos', $danza->pasos_basicos) }}</textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Formación Tradicional</label>
                    <textarea name="formacion" class="form-control" rows="3" 
                              placeholder="Filas, bloques, rueda, formaciones especiales...">{{ old('formacion', $danza->formacion) }}</textarea>
                </div>
            </div>
        </div>
    </section>

    <!-- D) Personajes/Roles -->
    <section class="form-section">
        <div class="section-header">
            <h5 class="section-title"><i class="bi bi-people"></i> Personajes/Roles</h5>
            <span class="pill">Repetible</span>
        </div>
        <div class="section-body">
            <button type="button" class="btn btn-outline-gold btn-sm mb-3" id="addPersonaje">
                <i class="bi bi-plus-circle"></i> Agregar Personaje/Rol
            </button>
            <small class="d-block text-muted mb-3">
                Los personajes existentes se eliminarán y se reemplazarán con los nuevos que agregue aquí
            </small>

            <div id="personajesContainer"></div>
        </div>
    </section>

    <!-- E) Multimedia -->
    <section class="form-section">
        <div class="section-header">
            <h5 class="section-title"><i class="bi bi-camera"></i> Multimedia</h5>
            <span class="pill">Fotos y Videos</span>
        </div>
        <div class="section-body">
            <div class="mb-3">
                <button type="button" class="btn btn-outline-gold btn-sm me-2" id="addFoto">
                    <i class="bi bi-image"></i> Agregar Foto
                </button>
                <button type="button" class="btn btn-outline-gold btn-sm" id="addVideo">
                    <i class="bi bi-camera-video"></i> Agregar Video
                </button>
            </div>
            <small class="d-block text-muted mb-3">
                La multimedia existente se reemplazará. Fotos: sube archivo o coloca URL. Videos: sube archivo (mp4) o link
            </small>

            <table class="mini-table">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Archivo</th>
                        <th>URL/Link</th>
                        <th>Título</th>
                        <th>Créditos</th>
                        <th style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody id="mediaBody"></tbody>
            </table>
        </div>
    </section>

    <!-- F) Patrimonio y Documentos -->
    <section class="form-section">
        <div class="section-header">
            <h5 class="section-title"><i class="bi bi-award"></i> Patrimonio</h5>
            <span class="pill">Opcional</span>
        </div>
        <div class="section-body">
            <div class="mb-3">
                <label class="form-label">Declaratorias Patrimoniales</label>
                <textarea name="declaratorias" class="form-control" rows="3" 
                          placeholder="UNESCO / Nacional / Departamental (si aplica)">{{ old('declaratorias', $danza->declaratorias) }}</textarea>
            </div>

            <div class="dynamic-block">
                <div class="block-header">
                    <div class="block-title"><i class="bi bi-file-earmark-text"></i> Documentos de Respaldo</div>
                    <button type="button" class="btn btn-outline-gold btn-sm" id="addDocumento">
                        <i class="bi bi-plus-circle"></i> Agregar Documento
                    </button>
                </div>
                <small class="d-block text-muted mb-3">
                    Los documentos existentes se reemplazarán. PDF/Word: investigaciones, resoluciones, etc.
                </small>

                <table class="mini-table">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Archivo</th>
                            <th>Fuente/Autor</th>
                            <th style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody id="documentosBody"></tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Acciones Sticky -->
    <div class="sticky-actions">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <span class="text-muted small">
                    <i class="bi bi-info-circle"></i> Editando: {{ $danza->nombre }}
                </span>
            </div>
            <div>
                <a href="{{ route('danzas.show', $danza) }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-gold">
                    <i class="bi bi-save"></i> Actualizar Danza
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    let personajeCount = 0;
    let vestimentaCounters = {};
    let mediaCount = 0;
    let docCount = 0;

    // Datos existentes de la danza
    const danzaData = {
        tipo_fecha_origen: '{{ $danza->tipo_fecha_origen }}',
        siglo_origen: '{{ $danza->siglo_origen }}',
        anio_aprox: '{{ $danza->anio_aprox }}',
        anio_inicio: '{{ $danza->anio_inicio }}',
        anio_fin: '{{ $danza->anio_fin }}',
        fecha_origen: '{{ $danza->fecha_origen ? $danza->fecha_origen->format('Y-m-d') : '' }}',
    };

    // === Origen dinámico
    const tipoOrigen = document.getElementById('tipo_fecha_origen');
    const origenDynamic = document.getElementById('origenDynamic');
    
    function renderOrigenFields() {
        const tipo = tipoOrigen.value;
        const year = new Date().getFullYear();
        let html = '';
        
        if (tipo === 'siglo') {
            html = `
                <label class="form-label">Siglo <span class="text-danger">*</span></label>
                <input type="number" name="siglo_origen" class="form-control" min="1" max="21" 
                       value="${danzaData.siglo_origen || ''}" placeholder="Ej: 18" required>
            `;
        } else if (tipo === 'anio_aprox') {
            html = `
                <label class="form-label">Año Aproximado <span class="text-danger">*</span></label>
                <input type="number" name="anio_aprox" class="form-control" min="1500" max="${year}" 
                       value="${danzaData.anio_aprox || ''}" placeholder="Ej: 1780" required>
            `;
        } else if (tipo === 'rango') {
            html = `
                <label class="form-label">Rango de Años <span class="text-danger">*</span></label>
                <div class="row">
                    <div class="col-6">
                        <input type="number" name="anio_inicio" class="form-control" min="1500" max="${year}" 
                               value="${danzaData.anio_inicio || ''}" placeholder="Inicio" required>
                    </div>
                    <div class="col-6">
                        <input type="number" name="anio_fin" class="form-control" min="1500" max="${year}" 
                               value="${danzaData.anio_fin || ''}" placeholder="Fin" required>
                    </div>
                </div>
            `;
        } else {
            html = `
                <label class="form-label">Fecha Exacta <span class="text-danger">*</span></label>
                <input type="date" name="fecha_origen" class="form-control" 
                       value="${danzaData.fecha_origen || ''}" required>
            `;
        }
        
        origenDynamic.innerHTML = html;
    }
    
    tipoOrigen.addEventListener('change', renderOrigenFields);
    renderOrigenFields();

    // === Cargar personajes existentes
    @foreach($danza->personajes as $personaje)
        personajeCount++;
        const personajeId{{ $personaje->id }} = `personaje_${personajeCount}`;
        vestimentaCounters[personajeId{{ $personaje->id }}] = 0;
        
        const divPersonaje{{ $personaje->id }} = document.createElement('div');
        divPersonaje{{ $personaje->id }}.className = 'dynamic-block';
        divPersonaje{{ $personaje->id }}.dataset.personajeId = personajeId{{ $personaje->id }};
        
        divPersonaje{{ $personaje->id }}.innerHTML = `
            <div class="block-header">
                <div class="block-title">Personaje/Rol #${personajeCount}</div>
                <div>
                    <button type="button" class="btn btn-outline-gold btn-sm me-2" onclick="addVestimenta('personajeId{{ $personaje->id }}')">
                        <i class="bi bi-plus-circle"></i> Vestimenta
                    </button>
                    <button type="button" class="btn btn-danger btn-sm remove-btn" onclick="removePersonaje('personajeId{{ $personaje->id }}')">
                        <i class="bi bi-trash"></i> Quitar
                    </button>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">Nombre del Rol</label>
                    <input type="text" name="personajes[${personajeCount}][nombre]" class="form-control" 
                           value="{{ $personaje->nombre }}" placeholder="Ej: Moreno, Diablo, Ángel...">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Foto del Personaje</label>
                    <input type="file" name="personajes[${personajeCount}][foto]" class="form-control" accept="image/*">
                    @if($personaje->foto)
                        <img src="{{ asset('storage/' . $personaje->foto) }}" class="preview-image" alt="Foto actual">
                        <small class="d-block text-muted">Foto actual (sube nueva para reemplazar)</small>
                    @endif
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Descripción (opcional)</label>
                <textarea name="personajes[${personajeCount}][descripcion]" class="form-control" rows="2" 
                          placeholder="Breve descripción del rol...">{{ $personaje->descripcion }}</textarea>
            </div>
            
            <label class="form-label fw-bold">Vestimenta del Rol</label>
            <table class="mini-table">
                <thead>
                    <tr>
                        <th>Elemento</th>
                        <th>Descripción</th>
                        <th>Material</th>
                        <th>Peso (kg)</th>
                        <th>Costo (Bs)</th>
                        <th style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody id="vestimenta_personajeId{{ $personaje->id }}"></tbody>
            </table>
        `;
        
        document.getElementById('personajesContainer').appendChild(divPersonaje{{ $personaje->id }});
        
        // Cargar vestimentas del personaje
        @foreach($personaje->vestimentas as $vestimenta)
            vestimentaCounters['personajeId{{ $personaje->id }}']++;
            const vestCount{{ $vestimenta->id }} = vestimentaCounters['personajeId{{ $personaje->id }}'];
            const tbody{{ $vestimenta->id }} = document.getElementById('vestimenta_personajeId{{ $personaje->id }}');
            
            const tr{{ $vestimenta->id }} = document.createElement('tr');
            tr{{ $vestimenta->id }}.innerHTML = `
                <td><input type="text" name="personajes[${personajeCount}][vestimentas][${vestCount{{ $vestimenta->id }}}][elemento]" value="{{ $vestimenta->elemento }}"></td>
                <td><input type="text" name="personajes[${personajeCount}][vestimentas][${vestCount{{ $vestimenta->id }}}][descripcion]" value="{{ $vestimenta->descripcion }}"></td>
                <td><input type="text" name="personajes[${personajeCount}][vestimentas][${vestCount{{ $vestimenta->id }}}][material]" value="{{ $vestimenta->material }}"></td>
                <td><input type="number" name="personajes[${personajeCount}][vestimentas][${vestCount{{ $vestimenta->id }}}][peso]" step="0.1" value="{{ $vestimenta->peso }}"></td>
                <td><input type="number" name="personajes[${personajeCount}][vestimentas][${vestCount{{ $vestimenta->id }}}][costo]" step="1" value="{{ $vestimenta->costo }}"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-btn" onclick="this.closest('tr').remove()">✕</button></td>
        `;
        document.getElementById('mediaBody').appendChild(trMedia{{ $media->id }});
    @endforeach

    // === Cargar documentos existentes
    @foreach($danza->documentos as $doc)
        docCount++;
        const trDoc{{ $doc->id }} = document.createElement('tr');
        trDoc{{ $doc->id }}.innerHTML = `
            <td>
                <select name="documentos[${docCount}][tipo]" class="form-control">
                    <option {{ $doc->tipo == 'PDF' ? 'selected' : '' }}>PDF</option>
                    <option {{ $doc->tipo == 'Word' ? 'selected' : '' }}>Word</option>
                    <option {{ $doc->tipo == 'Libro' ? 'selected' : '' }}>Libro</option>
                    <option {{ $doc->tipo == 'Resolución' ? 'selected' : '' }}>Resolución</option>
                    <option {{ $doc->tipo == 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>
            </td>
            <td><input type="text" name="documentos[${docCount}][titulo]" value="{{ $doc->titulo }}"></td>
            <td><input type="text" name="documentos[${docCount}][descripcion]" value="{{ $doc->descripcion }}"></td>
            <td>
                <input type="file" name="documentos[${docCount}][archivo]" accept=".pdf,.doc,.docx">
                @if($doc->archivo)
                    <small class="d-block text-muted">Actual: {{ basename($doc->archivo) }}</small>
                @endif
            </td>
            <td><input type="text" name="documentos[${docCount}][fuente]" value="{{ $doc->fuente }}"></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-btn" onclick="this.closest('tr').remove()">✕</button></td>
        `;
        document.getElementById('documentosBody').appendChild(trDoc{{ $doc->id }});
    @endforeach

    // === Funciones para agregar nuevos elementos
    function addPersonaje() {
        personajeCount++;
        const id = `personaje_${personajeCount}`;
        vestimentaCounters[id] = 0;
        
        const div = document.createElement('div');
        div.className = 'dynamic-block';
        div.dataset.personajeId = id;
        
        div.innerHTML = `
            <div class="block-header">
                <div class="block-title">Personaje/Rol #${personajeCount}</div>
                <div>
                    <button type="button" class="btn btn-outline-gold btn-sm me-2" onclick="addVestimenta('${id}')">
                        <i class="bi bi-plus-circle"></i> Vestimenta
                    </button>
                    <button type="button" class="btn btn-danger btn-sm remove-btn" onclick="removePersonaje('${id}')">
                        <i class="bi bi-trash"></i> Quitar
                    </button>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">Nombre del Rol</label>
                    <input type="text" name="personajes[${personajeCount}][nombre]" class="form-control" 
                           placeholder="Ej: Moreno, Diablo, Ángel...">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Foto del Personaje</label>
                    <input type="file" name="personajes[${personajeCount}][foto]" class="form-control" accept="image/*">
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Descripción (opcional)</label>
                <textarea name="personajes[${personajeCount}][descripcion]" class="form-control" rows="2" 
                          placeholder="Breve descripción del rol..."></textarea>
            </div>
            
            <label class="form-label fw-bold">Vestimenta del Rol</label>
            <table class="mini-table">
                <thead>
                    <tr>
                        <th>Elemento</th>
                        <th>Descripción</th>
                        <th>Material</th>
                        <th>Peso (kg)</th>
                        <th>Costo (Bs)</th>
                        <th style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody id="vestimenta_${id}"></tbody>
            </table>
        `;
        
        document.getElementById('personajesContainer').appendChild(div);
        addVestimenta(id);
    }
    
    function removePersonaje(id) {
        const div = document.querySelector(`[data-personaje-id="${id}"]`);
        if (div) div.remove();
    }
    
    function addVestimenta(personajeId) {
        vestimentaCounters[personajeId]++;
        const count = vestimentaCounters[personajeId];
        const tbody = document.getElementById(`vestimenta_${personajeId}`);
        const personajeNum = personajeId.split('_')[1];
        
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="personajes[${personajeNum}][vestimentas][${count}][elemento]" placeholder="Ej: máscara"></td>
            <td><input type="text" name="personajes[${personajeNum}][vestimentas][${count}][descripcion]" placeholder="Detalles"></td>
            <td><input type="text" name="personajes[${personajeNum}][vestimentas][${count}][material]" placeholder="Material"></td>
            <td><input type="number" name="personajes[${personajeNum}][vestimentas][${count}][peso]" step="0.1" placeholder="0.0"></td>
            <td><input type="number" name="personajes[${personajeNum}][vestimentas][${count}][costo]" step="1" placeholder="0"></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-btn" onclick="this.closest('tr').remove()">✕</button></td>
        `;
        
        tbody.appendChild(tr);
    }
    
    document.getElementById('addPersonaje').addEventListener('click', addPersonaje);

    // === Multimedia
    function addMedia(tipo) {
        mediaCount++;
        const tbody = document.getElementById('mediaBody');
        const isPhoto = tipo === 'Foto';
        
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <select name="multimedia[${mediaCount}][tipo]" class="form-control">
                    <option value="Foto" ${isPhoto ? 'selected' : ''}>Foto</option>
                    <option value="Video" ${!isPhoto ? 'selected' : ''}>Video</option>
                </select>
            </td>
            <td><input type="file" name="multimedia[${mediaCount}][archivo]" accept="${isPhoto ? 'image/*' : 'video/*'}"></td>
            <td><input type="text" name="multimedia[${mediaCount}][url]" placeholder="${isPhoto ? 'URL de imagen' : 'Link de YouTube/Facebook'}"></td>
            <td><input type="text" name="multimedia[${mediaCount}][titulo]" placeholder="Título"></td>
            <td><input type="text" name="multimedia[${mediaCount}][creditos]" placeholder="Autor/fuente"></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-btn" onclick="this.closest('tr').remove()">✕</button></td>
        `;
        
        tbody.appendChild(tr);
    }
    
    document.getElementById('addFoto').addEventListener('click', () => addMedia('Foto'));
    document.getElementById('addVideo').addEventListener('click', () => addMedia('Video'));

    // === Documentos
    function addDocumento() {
        docCount++;
        const tbody = document.getElementById('documentosBody');
        
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <select name="documentos[${docCount}][tipo]" class="form-control">
                    <option>PDF</option>
                    <option>Word</option>
                    <option>Libro</option>
                    <option>Resolución</option>
                    <option>Otro</option>
                </select>
            </td>
            <td><input type="text" name="documentos[${docCount}][titulo]" placeholder="Título del documento"></td>
            <td><input type="text" name="documentos[${docCount}][descripcion]" placeholder="Breve descripción"></td>
            <td><input type="file" name="documentos[${docCount}][archivo]" accept=".pdf,.doc,.docx"></td>
            <td><input type="text" name="documentos[${docCount}][fuente]" placeholder="Autor/institución/año"></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-btn" onclick="this.closest('tr').remove()">✕</button></td>
        `;
        
        tbody.appendChild(tr);
    }
    
    document.getElementById('addDocumento').addEventListener('click', addDocumento);

    // Validación del formulario
    document.getElementById('danzaForm').addEventListener('submit', function(e) {
        console.log('Actualizando danza...');
    });
</script>
@endsection
