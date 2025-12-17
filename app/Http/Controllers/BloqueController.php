<?php

namespace App\Http\Controllers;

use App\Models\Bloque;
use App\Models\Fraternidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BloqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bloques = Bloque::with(['fraternidad', 'responsables'])
            ->withCount('responsables')
            ->latest()
            ->paginate(12);
            
        return view('bloques.index', compact('bloques'));
    }

    /**
     * Mostrar bloques eliminados
     */
    public function trashed()
    {
        $bloques = Bloque::onlyTrashed()
            ->with(['fraternidad', 'responsables'])
            ->withCount('responsables')
            ->latest()
            ->paginate(12);
            
        return view('bloques.trashed', compact('bloques'));
    }

    /**
     * Restaurar bloque eliminado
     */
    public function restore($id)
    {
        $bloque = Bloque::onlyTrashed()->findOrFail($id);
        $bloque->restore();
        
        return redirect()->route('bloques.trashed')
            ->with('success', 'Bloque restaurado exitosamente');
    }

    /**
     * Eliminar permanentemente
     */
    public function forceDelete($id)
    {
        $bloque = Bloque::onlyTrashed()->findOrFail($id);
        
        // Eliminar foto principal
        if ($bloque->foto_principal) {
            Storage::disk('public')->delete($bloque->foto_principal);
        }
        
        $bloque->forceDelete();
        
        return redirect()->route('bloques.trashed')
            ->with('success', 'Bloque eliminado permanentemente');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fraternidades = Fraternidad::orderBy('nombre')->get();
        $estados = Bloque::getEstados();
        
        return view('bloques.create', compact('fraternidades', 'estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fraternidad_id' => 'nullable|exists:fraternidades,id',
            'nombre' => 'required|string|max:255',
            'fecha_fundacion' => 'nullable|date',
            'lema' => 'nullable|string|max:255',
            'integrantes_aproximados' => 'nullable|integer|min:0',
            'historia' => 'nullable|string',
            'facebook' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'foto_principal' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'estado' => 'required|in:activo,inactivo',
            'responsables' => 'nullable|array',
            'responsables.*.nombre' => 'required|string|max:255',
            'responsables.*.telefono' => 'nullable|numeric|digits:8',
            'responsables.*.email' => 'nullable|email|max:255',
        ]);

        DB::beginTransaction();
        
        try {
            // Guardar foto principal
            if ($request->hasFile('foto_principal')) {
                $validated['foto_principal'] = $request->file('foto_principal')->store('bloques', 'public');
            }

            // Crear bloque
            $bloque = Bloque::create($validated);

            // Guardar responsables
            if ($request->has('responsables')) {
                foreach ($request->responsables as $index => $responsableData) {
                    if (!empty($responsableData['nombre'])) {
                        $bloque->responsables()->create([
                            'nombre' => $responsableData['nombre'],
                            'telefono' => $responsableData['telefono'] ?? null,
                            'email' => $responsableData['email'] ?? null,
                            'orden' => $index + 1,
                        ]);
                    }
                }
            }

            DB::commit();
            
            return redirect()->route('bloques.show', $bloque)
                ->with('success', 'Bloque creado exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Error al crear el bloque: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Bloque $bloque)
    {
        $bloque->load(['fraternidad', 'responsables']);
        
        return view('bloques.show', compact('bloque'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bloque $bloque)
    {
        $bloque->load(['fraternidad', 'responsables']);
        
        $fraternidades = Fraternidad::orderBy('nombre')->get();
        $estados = Bloque::getEstados();
        
        return view('bloques.edit', compact('bloque', 'fraternidades', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bloque $bloque)
    {
        $validated = $request->validate([
            'fraternidad_id' => 'nullable|exists:fraternidades,id',
            'nombre' => 'required|string|max:255',
            'fecha_fundacion' => 'nullable|date',
            'lema' => 'nullable|string|max:255',
            'integrantes_aproximados' => 'nullable|integer|min:0',
            'historia' => 'nullable|string',
            'facebook' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'foto_principal' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'estado' => 'required|in:activo,inactivo',
            'responsables' => 'nullable|array',
            'responsables.*.nombre' => 'required|string|max:255',
            'responsables.*.telefono' => 'nullable|numeric|digits:8',
            'responsables.*.email' => 'nullable|email|max:255',
        ]);

        DB::beginTransaction();
        
        try {
            // Actualizar foto principal
            if ($request->hasFile('foto_principal')) {
                // Eliminar foto anterior
                if ($bloque->foto_principal) {
                    Storage::disk('public')->delete($bloque->foto_principal);
                }
                $validated['foto_principal'] = $request->file('foto_principal')->store('bloques', 'public');
            }

            // Actualizar bloque
            $bloque->update($validated);

            // Actualizar responsables: eliminar existentes y crear nuevos
            $bloque->responsables()->delete();
            
            if ($request->has('responsables')) {
                foreach ($request->responsables as $index => $responsableData) {
                    if (!empty($responsableData['nombre'])) {
                        $bloque->responsables()->create([
                            'nombre' => $responsableData['nombre'],
                            'telefono' => $responsableData['telefono'] ?? null,
                            'email' => $responsableData['email'] ?? null,
                            'orden' => $index + 1,
                        ]);
                    }
                }
            }

            DB::commit();
            
            return redirect()->route('bloques.show', $bloque)
                ->with('success', 'Bloque actualizado exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Error al actualizar el bloque: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bloque $bloque)
    {
        $bloque->delete();
        
        return redirect()->route('bloques.index')
            ->with('success', 'Bloque eliminado exitosamente');
    }

    /**
     * Incrementar integrantes
     */
    public function incrementarIntegrantes(Request $request, Bloque $bloque)
    {
        $cantidad = $request->input('cantidad', 1);
        $bloque->incrementarIntegrantes($cantidad);
        
        return back()->with('success', "Se agregaron {$cantidad} integrantes");
    }

    /**
     * Decrementar integrantes
     */
    public function decrementarIntegrantes(Request $request, Bloque $bloque)
    {
        $cantidad = $request->input('cantidad', 1);
        $bloque->decrementarIntegrantes($cantidad);
        
        return back()->with('success', "Se restaron {$cantidad} integrantes");
    }
}