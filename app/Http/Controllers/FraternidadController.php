<?php

namespace App\Http\Controllers;

use App\Models\Fraternidad;
use App\Models\FraternidadPasante;  // Agregar esta línea
use App\Models\Danza;
use App\Models\Entrada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class FraternidadController extends Controller
{
    public function index()
    {
        $fraternidades = Fraternidad::with(['danza', 'pasantes', 'entradas'])
            ->latest()
            ->paginate(12);
        
        return view('fraternidades.index', compact('fraternidades'));
    }

    public function trashed()
    {
        $fraternidades = Fraternidad::onlyTrashed()
            ->with(['danza', 'pasantes', 'entradas'])
            ->latest()
            ->paginate(12);
        
        return view('fraternidades.trashed', compact('fraternidades'));
    }

    public function restore($id)
    {
        $fraternidad = Fraternidad::onlyTrashed()->findOrFail($id);
        $fraternidad->restore();
        
        return redirect()->route('fraternidades.trashed')
            ->with('success', 'Fraternidad restaurada exitosamente');
    }

    public function forceDelete($id)
    {
        $fraternidad = Fraternidad::onlyTrashed()->findOrFail($id);
        $fraternidad->forceDelete();
        
        return redirect()->route('fraternidades.trashed')
            ->with('success', 'Fraternidad eliminada permanentemente');
    }

    public function create()
    {
        $danzas = Danza::orderBy('nombre')->get();
        $entradas = Entrada::orderBy('nombre')->get();
        
        return view('fraternidades.create', compact('danzas', 'entradas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_fundacion' => 'nullable|date',
            'descripcion' => 'nullable|string',
            'danza_id' => 'nullable|exists:danzas,id',
            'lema' => 'nullable|string|max:255',
            'telefono' => 'nullable|numeric|digits:8',
            'correo_electronico' => 'nullable|email|max:255',
            'pasantes' => 'nullable|array',
            'pasantes.*' => 'nullable|string|max:255',
            'entradas' => 'nullable|array',
            'entradas.*' => 'exists:entradas,id',
        ]);

        DB::beginTransaction();
        
        try {
            $fraternidad = Fraternidad::create($validated);

            // Guardar pasantes
            if ($request->has('pasantes')) {
                foreach ($request->pasantes as $index => $nombrePasante) {
                    if (!empty($nombrePasante)) {
                        $fraternidad->pasantes()->create([
                            'nombre' => $nombrePasante,
                            'orden' => $index + 1,
                        ]);
                    }
                }
            }

            // Asociar entradas
            if ($request->has('entradas')) {
                $fraternidad->entradas()->attach($request->entradas);
            }

            DB::commit();
            
            return redirect()->route('fraternidades.show', $fraternidad)
                ->with('success', 'Fraternidad creada exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Error al crear la fraternidad: ' . $e->getMessage());
        }
    }

    public function show(Fraternidad $fraternidade)
    {
        $fraternidade->load(['danza', 'pasantes', 'entradas']);
        
        return view('fraternidades.show', compact('fraternidade'));
    }

    public function edit(Fraternidad $fraternidade)
    {
        $fraternidade->load(['danza', 'pasantes', 'entradas']);
        
        $danzas = Danza::orderBy('nombre')->get();
        $entradas = Entrada::orderBy('nombre')->get();
        
        return view('fraternidades.edit', compact('fraternidade', 'danzas', 'entradas'));
    }

    public function update(Request $request, Fraternidad $fraternidade)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_fundacion' => 'nullable|date',
            'descripcion' => 'nullable|string',
            'danza_id' => 'nullable|exists:danzas,id',
            'lema' => 'nullable|string|max:255',
            'telefono' => 'nullable|numeric|digits:8',
            'correo_electronico' => 'nullable|email|max:255',
            'pasantes' => 'nullable|array',
            'pasantes.*' => 'nullable|string|max:255',
            'entradas' => 'nullable|array',
            'entradas.*' => 'exists:entradas,id',
        ]);

        DB::beginTransaction();
        
        try {
            $fraternidade->update($validated);

            // Actualizar pasantes (eliminar y recrear)
            $fraternidade->pasantes()->delete();
            
            if ($request->has('pasantes')) {
                foreach ($request->pasantes as $index => $nombrePasante) {
                    if (!empty($nombrePasante)) {
                        $fraternidade->pasantes()->create([
                            'nombre' => $nombrePasante,
                            'orden' => $index + 1,
                        ]);
                    }
                }
            }

            // Sincronizar entradas
            if ($request->has('entradas')) {
                $fraternidade->entradas()->sync($request->entradas);
            } else {
                $fraternidade->entradas()->detach();
            }

            DB::commit();
            
            return redirect()->route('fraternidades.show', $fraternidade)
                ->with('success', 'Fraternidad actualizada exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Error al actualizar la fraternidad: ' . $e->getMessage());
        }
    }

    public function destroy(Fraternidad $fraternidade)
    {
        $fraternidade->delete();
        
        return redirect()->route('fraternidades.index')
            ->with('success', 'Fraternidad eliminada exitosamente');
    }
}