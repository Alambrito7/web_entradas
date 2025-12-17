<?php

namespace App\Http\Controllers;

use App\Models\Danza;
use App\Models\DanzaPersonaje;
use App\Models\DanzaVestimenta;
use App\Models\DanzaMultimedia;
use App\Models\DanzaDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DanzaController extends Controller
{
    public function index()
    {
        $danzas = Danza::with('personajes', 'multimedia')
            ->latest()
            ->paginate(12);
        
        return view('danzas.index', compact('danzas'));
    }

    public function trashed()
    {
        $danzas = Danza::onlyTrashed()
            ->with('personajes', 'multimedia')
            ->latest()
            ->paginate(12);
        
        return view('danzas.trashed', compact('danzas'));
    }

    public function restore($id)
    {
        $danza = Danza::onlyTrashed()->findOrFail($id);
        $danza->restore();
        
        return redirect()->route('danzas.trashed')
            ->with('success', 'Danza restaurada exitosamente');
    }

    public function forceDelete($id)
    {
        $danza = Danza::onlyTrashed()->findOrFail($id);
        
        // Eliminar archivos asociados
        foreach ($danza->personajes as $personaje) {
            if ($personaje->foto) {
                Storage::disk('public')->delete($personaje->foto);
            }
        }
        
        foreach ($danza->multimedia as $media) {
            if ($media->archivo) {
                Storage::disk('public')->delete($media->archivo);
            }
        }
        
        foreach ($danza->documentos as $doc) {
            if ($doc->archivo) {
                Storage::disk('public')->delete($doc->archivo);
            }
        }
        
        $danza->forceDelete();
        
        return redirect()->route('danzas.trashed')
            ->with('success', 'Danza eliminada permanentemente');
    }

    public function create()
    {
        $categorias = Danza::getCategorias();
        $departamentos = Danza::getDepartamentos();
        $estadosFicha = Danza::getEstadosFicha();
        
        return view('danzas.create', compact('categorias', 'departamentos', 'estadosFicha'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|in:' . implode(',', Danza::getCategorias()),
            'departamento_principal' => 'required|in:' . implode(',', Danza::getDepartamentos()),
            'region_origen' => 'required|string|max:255',
            'tipo_fecha_origen' => 'required|in:siglo,anio_aprox,rango,exacta',
            'estado_ficha' => 'required|in:Borrador,En revisión,Publicada',
        ]);

        DB::beginTransaction();
        
        try {
            // Crear la danza
            $danza = Danza::create($validated + $request->only([
                'siglo_origen', 'anio_aprox', 'anio_inicio', 'anio_fin', 'fecha_origen',
                'descripcion_corta', 'historia_origen', 'significado_cultural',
                'instrumentos', 'ritmo_compas', 'pasos_basicos', 'formacion', 'declaratorias'
            ]));

            // Guardar personajes y sus vestimentas
            if ($request->has('personajes')) {
                foreach ($request->personajes as $personajeData) {
                    if (empty($personajeData['nombre'])) continue;
                    
                    $fotoPath = null;
                    if (isset($personajeData['foto']) && $personajeData['foto']) {
                        $fotoPath = $personajeData['foto']->store('danzas/personajes', 'public');
                    }
                    
                    $personaje = $danza->personajes()->create([
                        'nombre' => $personajeData['nombre'],
                        'descripcion' => $personajeData['descripcion'] ?? null,
                        'foto' => $fotoPath,
                    ]);
                    
                    // Guardar vestimentas del personaje
                    if (isset($personajeData['vestimentas'])) {
                        foreach ($personajeData['vestimentas'] as $vestimenta) {
                            if (empty($vestimenta['elemento'])) continue;
                            
                            $personaje->vestimentas()->create([
                                'elemento' => $vestimenta['elemento'],
                                'descripcion' => $vestimenta['descripcion'] ?? null,
                                'material' => $vestimenta['material'] ?? null,
                                'peso' => $vestimenta['peso'] ?? null,
                                'costo' => $vestimenta['costo'] ?? null,
                            ]);
                        }
                    }
                }
            }

            // Guardar multimedia
            if ($request->has('multimedia')) {
                foreach ($request->multimedia as $mediaData) {
                    $archivoPath = null;
                    if (isset($mediaData['archivo']) && $mediaData['archivo']) {
                        $folder = $mediaData['tipo'] === 'Foto' ? 'danzas/fotos' : 'danzas/videos';
                        $archivoPath = $mediaData['archivo']->store($folder, 'public');
                    }
                    
                    $danza->multimedia()->create([
                        'tipo' => $mediaData['tipo'],
                        'archivo' => $archivoPath,
                        'url' => $mediaData['url'] ?? null,
                        'titulo' => $mediaData['titulo'] ?? null,
                        'creditos' => $mediaData['creditos'] ?? null,
                    ]);
                }
            }

            // Guardar documentos
            if ($request->has('documentos')) {
                foreach ($request->documentos as $docData) {
                    if (empty($docData['titulo'])) continue;
                    
                    $archivoPath = null;
                    if (isset($docData['archivo']) && $docData['archivo']) {
                        $archivoPath = $docData['archivo']->store('danzas/documentos', 'public');
                    }
                    
                    $danza->documentos()->create([
                        'tipo' => $docData['tipo'],
                        'titulo' => $docData['titulo'],
                        'descripcion' => $docData['descripcion'] ?? null,
                        'archivo' => $archivoPath,
                        'fuente' => $docData['fuente'] ?? null,
                    ]);
                }
            }

            DB::commit();
            
            return redirect()->route('danzas.show', $danza)
                ->with('success', 'Danza creada exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Error al crear la danza: ' . $e->getMessage());
        }
    }

    public function show(Danza $danza)
    {
        $danza->load(['personajes.vestimentas', 'multimedia', 'documentos']);
        
        return view('danzas.show', compact('danza'));
    }

    public function edit(Danza $danza)
    {
        $danza->load(['personajes.vestimentas', 'multimedia', 'documentos']);
        
        $categorias = Danza::getCategorias();
        $departamentos = Danza::getDepartamentos();
        $estadosFicha = Danza::getEstadosFicha();
        
        return view('danzas.edit', compact('danza', 'categorias', 'departamentos', 'estadosFicha'));
    }

    public function update(Request $request, Danza $danza)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|in:' . implode(',', Danza::getCategorias()),
            'departamento_principal' => 'required|in:' . implode(',', Danza::getDepartamentos()),
            'region_origen' => 'required|string|max:255',
            'tipo_fecha_origen' => 'required|in:siglo,anio_aprox,rango,exacta',
            'estado_ficha' => 'required|in:Borrador,En revisión,Publicada',
        ]);

        DB::beginTransaction();
        
        try {
            // Actualizar la danza
            $danza->update($validated + $request->only([
                'siglo_origen', 'anio_aprox', 'anio_inicio', 'anio_fin', 'fecha_origen',
                'descripcion_corta', 'historia_origen', 'significado_cultural',
                'instrumentos', 'ritmo_compas', 'pasos_basicos', 'formacion', 'declaratorias'
            ]));

            // Eliminar personajes existentes y crear nuevos
            foreach ($danza->personajes as $personaje) {
                if ($personaje->foto) {
                    Storage::disk('public')->delete($personaje->foto);
                }
            }
            $danza->personajes()->delete();

            // Guardar personajes actualizados
            if ($request->has('personajes')) {
                foreach ($request->personajes as $personajeData) {
                    if (empty($personajeData['nombre'])) continue;
                    
                    $fotoPath = null;
                    if (isset($personajeData['foto']) && $personajeData['foto']) {
                        $fotoPath = $personajeData['foto']->store('danzas/personajes', 'public');
                    }
                    
                    $personaje = $danza->personajes()->create([
                        'nombre' => $personajeData['nombre'],
                        'descripcion' => $personajeData['descripcion'] ?? null,
                        'foto' => $fotoPath,
                    ]);
                    
                    if (isset($personajeData['vestimentas'])) {
                        foreach ($personajeData['vestimentas'] as $vestimenta) {
                            if (empty($vestimenta['elemento'])) continue;
                            
                            $personaje->vestimentas()->create([
                                'elemento' => $vestimenta['elemento'],
                                'descripcion' => $vestimenta['descripcion'] ?? null,
                                'material' => $vestimenta['material'] ?? null,
                                'peso' => $vestimenta['peso'] ?? null,
                                'costo' => $vestimenta['costo'] ?? null,
                            ]);
                        }
                    }
                }
            }

            // Actualizar multimedia
            $danza->multimedia()->delete();
            if ($request->has('multimedia')) {
                foreach ($request->multimedia as $mediaData) {
                    $archivoPath = null;
                    if (isset($mediaData['archivo']) && $mediaData['archivo']) {
                        $folder = $mediaData['tipo'] === 'Foto' ? 'danzas/fotos' : 'danzas/videos';
                        $archivoPath = $mediaData['archivo']->store($folder, 'public');
                    }
                    
                    $danza->multimedia()->create([
                        'tipo' => $mediaData['tipo'],
                        'archivo' => $archivoPath,
                        'url' => $mediaData['url'] ?? null,
                        'titulo' => $mediaData['titulo'] ?? null,
                        'creditos' => $mediaData['creditos'] ?? null,
                    ]);
                }
            }

            // Actualizar documentos
            $danza->documentos()->delete();
            if ($request->has('documentos')) {
                foreach ($request->documentos as $docData) {
                    if (empty($docData['titulo'])) continue;
                    
                    $archivoPath = null;
                    if (isset($docData['archivo']) && $docData['archivo']) {
                        $archivoPath = $docData['archivo']->store('danzas/documentos', 'public');
                    }
                    
                    $danza->documentos()->create([
                        'tipo' => $docData['tipo'],
                        'titulo' => $docData['titulo'],
                        'descripcion' => $docData['descripcion'] ?? null,
                        'archivo' => $archivoPath,
                        'fuente' => $docData['fuente'] ?? null,
                    ]);
                }
            }

            DB::commit();
            
            return redirect()->route('danzas.show', $danza)
                ->with('success', 'Danza actualizada exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Error al actualizar la danza: ' . $e->getMessage());
        }
    }

    public function destroy(Danza $danza)
    {
        $danza->delete();
        
        return redirect()->route('danzas.index')
            ->with('success', 'Danza eliminada exitosamente');
    }
}