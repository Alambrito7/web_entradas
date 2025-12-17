<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EntradaController extends Controller
{
    public function index()
    {
        $entradas = Entrada::latest()->paginate(12);
        return view('entradas.index', compact('entradas'));
    }

    public function trashed()
    {
        $entradas = Entrada::onlyTrashed()->latest()->paginate(12);
        return view('entradas.trashed', compact('entradas'));
    }

    public function restore($id)
    {
        $entrada = Entrada::onlyTrashed()->findOrFail($id);
        $entrada->restore();
        
        return redirect()->route('entradas.trashed')
            ->with('success', 'Entrada restaurada exitosamente');
    }

    public function forceDelete($id)
    {
        $entrada = Entrada::onlyTrashed()->findOrFail($id);
        
        if ($entrada->imagen) {
            Storage::disk('public')->delete($entrada->imagen);
        }
        
        $entrada->forceDelete();
        
        return redirect()->route('entradas.trashed')
            ->with('success', 'Entrada eliminada permanentemente');
    }

    public function create()
    {
        $departamentos = Entrada::getDepartamentos();
        $estados = Entrada::getEstados();
        
        return view('entradas.create', compact('departamentos', 'estados'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_fundacion' => 'nullable|date',
            'santo' => 'nullable|string|max:255',
            'historia' => 'nullable|string',
            'departamento' => 'required|in:' . implode(',', Entrada::getDepartamentos()),
            'fecha_evento' => 'required|date',
            'status' => 'required|in:planificada,en_curso,finalizada',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('entradas', 'public');
        }

        Entrada::create($validated);

        return redirect()->route('entradas.index')
            ->with('success', 'Entrada creada exitosamente');
    }

    public function show(Entrada $entrada)
    {
        return view('entradas.show', compact('entrada'));
    }

    public function edit(Entrada $entrada)
    {
        $departamentos = Entrada::getDepartamentos();
        $estados = Entrada::getEstados();
        
        return view('entradas.edit', compact('entrada', 'departamentos', 'estados'));
    }

    public function update(Request $request, Entrada $entrada)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_fundacion' => 'nullable|date',
            'santo' => 'nullable|string|max:255',
            'historia' => 'nullable|string',
            'departamento' => 'required|in:' . implode(',', Entrada::getDepartamentos()),
            'fecha_evento' => 'required|date',
            'status' => 'required|in:planificada,en_curso,finalizada',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            if ($entrada->imagen) {
                Storage::disk('public')->delete($entrada->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('entradas', 'public');
        }

        $entrada->update($validated);

        return redirect()->route('entradas.show', $entrada)
            ->with('success', 'Entrada actualizada exitosamente');
    }

    public function destroy(Entrada $entrada)
    {
        $entrada->delete();
        
        return redirect()->route('entradas.index')
            ->with('success', 'Entrada eliminada exitosamente');
    }
}