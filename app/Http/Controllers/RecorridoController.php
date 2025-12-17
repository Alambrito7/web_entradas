<?php

namespace App\Http\Controllers;

use App\Models\Recorrido;
use App\Models\Entrada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecorridoController extends Controller
{
    public function index()
    {
        $recorridos = Recorrido::with(['entrada', 'puntos'])
            ->latest()
            ->paginate(12);
        
        return view('recorridos.index', compact('recorridos'));
    }

    public function trashed()
    {
        $recorridos = Recorrido::onlyTrashed()
            ->with(['entrada', 'puntos'])
            ->latest()
            ->paginate(12);
        
        return view('recorridos.trashed', compact('recorridos'));
    }

    public function restore($id)
    {
        $recorrido = Recorrido::onlyTrashed()->findOrFail($id);
        $recorrido->restore();
        
        return redirect()->route('recorridos.trashed')
            ->with('success', 'Recorrido restaurado exitosamente');
    }

    public function forceDelete($id)
    {
        $recorrido = Recorrido::onlyTrashed()->findOrFail($id);
        $recorrido->forceDelete();
        
        return redirect()->route('recorridos.trashed')
            ->with('success', 'Recorrido eliminado permanentemente');
    }

    public function create()
    {
        $entradas = Entrada::orderBy('nombre')->get();
        
        return view('recorridos.create', compact('entradas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'entrada_id' => 'required|exists:entradas,id',
            'nombre' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'puntos' => 'required|array|min:2',
            'puntos.*.nombre' => 'required|string|max:255',
            'puntos.*.descripcion' => 'nullable|string',
            'puntos.*.latitud' => 'required|numeric|between:-90,90',
            'puntos.*.longitud' => 'required|numeric|between:-180,180',
        ], [
            'puntos.required' => 'Debe agregar al menos 2 puntos al recorrido',
            'puntos.min' => 'El recorrido debe tener al menos 2 puntos',
        ]);

        DB::beginTransaction();
        
        try {
            $recorrido = Recorrido::create([
                'entrada_id' => $validated['entrada_id'],
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
            ]);

            foreach ($request->puntos as $index => $punto) {
                $recorrido->puntos()->create([
                    'nombre' => $punto['nombre'],
                    'descripcion' => $punto['descripcion'] ?? null,
                    'latitud' => $punto['latitud'],
                    'longitud' => $punto['longitud'],
                    'orden' => $index + 1,
                ]);
            }

            DB::commit();
            
            return redirect()->route('recorridos.show', $recorrido)
                ->with('success', 'Recorrido creado exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Error al crear el recorrido: ' . $e->getMessage());
        }
    }

    public function show(Recorrido $recorrido)
    {
        $recorrido->load(['entrada', 'puntos']);
        
        return view('recorridos.show', compact('recorrido'));
    }

    public function edit(Recorrido $recorrido)
    {
        $recorrido->load(['entrada', 'puntos']);
        $entradas = Entrada::orderBy('nombre')->get();
        
        return view('recorridos.edit', compact('recorrido', 'entradas'));
    }

    public function update(Request $request, Recorrido $recorrido)
    {
        $validated = $request->validate([
            'entrada_id' => 'required|exists:entradas,id',
            'nombre' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'puntos' => 'required|array|min:2',
            'puntos.*.nombre' => 'required|string|max:255',
            'puntos.*.descripcion' => 'nullable|string',
            'puntos.*.latitud' => 'required|numeric|between:-90,90',
            'puntos.*.longitud' => 'required|numeric|between:-180,180',
        ]);

        DB::beginTransaction();
        
        try {
            $recorrido->update([
                'entrada_id' => $validated['entrada_id'],
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
            ]);

            // Eliminar puntos existentes y crear nuevos
            $recorrido->puntos()->delete();
            
            foreach ($request->puntos as $index => $punto) {
                $recorrido->puntos()->create([
                    'nombre' => $punto['nombre'],
                    'descripcion' => $punto['descripcion'] ?? null,
                    'latitud' => $punto['latitud'],
                    'longitud' => $punto['longitud'],
                    'orden' => $index + 1,
                ]);
            }

            DB::commit();
            
            return redirect()->route('recorridos.show', $recorrido)
                ->with('success', 'Recorrido actualizado exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Error al actualizar el recorrido: ' . $e->getMessage());
        }
    }

    public function destroy(Recorrido $recorrido)
    {
        $recorrido->delete();
        
        return redirect()->route('recorridos.index')
            ->with('success', 'Recorrido eliminado exitosamente');
    }
}