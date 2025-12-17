@extends('layouts.app')

@section('title', 'Registrar Nueva Danza')

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
        background: linear-gradient(90deg, var(--gold), rgba(212, 175, 55, .65));
        color: #0b0c10;
        border: 0;
        font-weight: 700;
    }

    .btn-gold:hover {
        background: var(--gold);
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
</style>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <h2><i class="bi bi-music-note-beamed"></i> Registrar Nueva Danza</h2>
        <p class="text-muted">Complete el formulario para documentar una nueva danza folclórica de Bolivia</p>
    </div>
</div>

<form action="{{ route('danzas.store') }}" method="POST" enctype="multipart/form-data" id="danzaForm">
    @csrf

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
                           value="{{ old('nombre') }}" placeholder="Ej: Morenada, Diablada, Tinku..." required>
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
                            <option value="{{ $cat }}" {{ old('categoria') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
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
                            <option value="{{ $dep }}" {{ old('departamento_principal') == $dep ? 'selected' : '' }}>{{ $dep }}</option>
                        @endforeach
                    </select>
                    @error('departamento_principal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Región de Origen <span class="text-danger">*</span></label>
                    <input type="text" name="region_origen" class="form-control @error('region_origen') is-invalid @enderror" 
                           value="{{ old('region_origen') }}" placeholder="Ej: Altiplano, Valle..." required>
                    @error('region_origen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tipo de Fecha de Origen <span class="text-danger">*</span></label>
                    <select name="tipo_fecha_origen" id="tipo_fecha_origen" class="form-select" required>
                        <option value="siglo" selected>Siglo (aprox.)</option>
                        <option value="anio_aprox">Año aproximado</option>
                        <option value="rango">Rango de años</option>
                        <option value="exacta">Fecha exacta</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3" id="origenDynamic">
                    <!-- Se llenará dinámicamente con JavaScript -->
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Estado de Ficha <span class="text-danger">*</span></label>
                    <select name="estado_ficha" class="form-select" required>
                        @foreach($estadosFicha as $estado)
                            <option value="{{ $estado }}" {{ old('estado_ficha', 'Borrador') == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Descripción Corta (para tarjetas)</label>
                    <textarea name="descripcion_corta" class="form-control" rows="3" 
                              placeholder="Resumen breve (300-500 caracteres)">{{ old('descripcion_corta') }}</textarea>
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
                          placeholder="Origen, contexto histórico, evolución de la danza...">{{ old('historia_origen') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Significado Cultural</label>
                <textarea name="significado_cultural" class="form-control" rows="4" 
                          placeholder="Qué representa, identidad, símbolos, importancia en la comunidad...">{{ old('significado_cultural') }}</textarea>
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
                           value="{{ old('instrumentos') }}" placeholder="Ej: banda, bronces, bombos, sikus...">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Ritmo y Compás</label>
                    <input type="text" name="ritmo_compas" class="form-control" 
                           value="{{ old('ritmo_compas') }}" placeholder="Descripción breve del ritmo">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pasos Básicos</label>
                    <textarea name="pasos_basicos" class="form-control" rows="3" 
                              placeholder="Descripción de los pasos y movimientos principales">{{ old('pasos_basicos') }}</textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Formación Tradicional</label>
                    <textarea name="formacion" class="form-control" rows="3" 
                              placeholder="Filas, bloques, rueda, formaciones especiales...">{{ old('formacion') }}</textarea>
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
                Cada personaje puede tener foto y su vestimenta detallada (elementos ilimitados)
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
                Fotos: sube archivo o coloca URL. Videos: sube archivo (mp4) o coloca link (YouTube/Facebook)
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
                          placeholder="UNESCO / Nacional / Departamental (si aplica)">{{ old('declaratorias') }}</textarea>
            </div>

            <div class="dynamic-block">
                <div class="block-header">
                    <div class="block-title"><i class="bi bi-file-earmark-text"></i> Documentos de Respaldo</div>
                    <button type="button" class="btn btn-outline-gold btn-sm" id="addDocumento">
                        <i class="bi bi-plus-circle"></i> Agregar Documento
                    </button>
                </div>
                <small class="d-block text-muted mb-3">
                    PDF/Word: investigaciones, resoluciones, libros digitalizados, actas, etc.
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
                <span class="text-muted small" id="statusText">
                    <i class="bi bi-info-circle"></i> Complete los campos obligatorios
                </span>
            </div>
            <div>
                <a href="{{ route('danzas.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-gold">
                    <i class="bi bi-save"></i> Guardar Danza
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
                       placeholder="Ej: 18" required>
            `;
        } else if (tipo === 'anio_aprox') {
            html = `
                <label class="form-label">Año Aproximado <span class="text-danger">*</span></label>
                <input type="number" name="anio_aprox" class="form-control" min="1500" max="${year}" 
                       placeholder="Ej: 1780" required>
            `;
        } else if (tipo === 'rango') {
            html = `
                <label class="form-label">Rango de Años <span class="text-danger">*</span></label>
                <div class="row">
                    <div class="col-6">
                        <input type="number" name="anio_inicio" class="form-control" min="1500" max="${year}" 
                               placeholder="Inicio" required>
                    </div>
                    <div class="col-6">
                        <input type="number" name="anio_fin" class="form-control" min="1500" max="${year}" 
                               placeholder="Fin" required>
                    </div>
                </div>
            `;
        } else {
            html = `
                <label class="form-label">Fecha Exacta <span class="text-danger">*</span></label>
                <input type="date" name="fecha_origen" class="form-control" required>
            `;
        }
        
        origenDynamic.innerHTML = html;
    }
    
    tipoOrigen.addEventListener('change', renderOrigenFields);
    renderOrigenFields();

    // === Personajes/Roles
    const personajesContainer = document.getElementById('personajesContainer');
    
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
        
        personajesContainer.appendChild(div);
        addVestimenta(id); // Agregar primera fila de vestimenta
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
    let mediaCount = 0;
    
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
    let docCount = 0;
    
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
        const statusText = document.getElementById('statusText');
        statusText.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando danza...';
    });
</script>
@endsection