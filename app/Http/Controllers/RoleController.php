<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Verificar permiso
        if (!auth()->user()->hasPermission('roles', 'read')) {
            abort(403, 'No tienes permiso para ver roles');
        }

        $roles = Role::withCount('users')->latest()->paginate(10);
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Solo Superadmin puede crear roles
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Solo el Superadmin puede crear roles');
        }

        $modulos = Role::getModulosDisponibles();
        $acciones = Role::getAccionesDisponibles();
        
        return view('roles.create', compact('modulos', 'acciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Solo Superadmin puede crear roles
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Solo el Superadmin puede crear roles');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:roles,nombre',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
            'permisos' => 'nullable|array',
        ]);

        // Procesar permisos
        $permisos = [];
        if ($request->has('permisos')) {
            foreach ($request->permisos as $modulo => $acciones) {
                if (is_array($acciones) && !empty($acciones)) {
                    $permisos[$modulo] = $acciones;
                }
            }
        }

        $validated['permisos'] = json_encode($permisos);
        $validated['activo'] = $request->has('activo');

        Role::create($validated);

        return redirect()->route('roles.index')
            ->with('success', 'Rol creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        // Verificar permiso
        if (!auth()->user()->hasPermission('roles', 'read')) {
            abort(403, 'No tienes permiso para ver roles');
        }

        $role->loadCount('users');
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        // Solo Superadmin puede editar roles
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Solo el Superadmin puede editar roles');
        }

        $modulos = Role::getModulosDisponibles();
        $acciones = Role::getAccionesDisponibles();
        
        return view('roles.edit', compact('role', 'modulos', 'acciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        // Solo Superadmin puede editar roles
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Solo el Superadmin puede editar roles');
        }

        // Proteger rol Superadmin
        if ($role->nombre === 'Superadmin' && $request->nombre !== 'Superadmin') {
            return back()->with('error', 'No se puede cambiar el nombre del rol Superadmin');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:roles,nombre,' . $role->id,
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
            'permisos' => 'nullable|array',
        ]);

        // Procesar permisos
        $permisos = [];
        if ($request->has('permisos')) {
            foreach ($request->permisos as $modulo => $acciones) {
                if (is_array($acciones) && !empty($acciones)) {
                    $permisos[$modulo] = $acciones;
                }
            }
        }

        $validated['permisos'] = json_encode($permisos);
        $validated['activo'] = $request->has('activo');

        $role->update($validated);

        return redirect()->route('roles.show', $role)
            ->with('success', 'Rol actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Solo Superadmin puede eliminar roles
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Solo el Superadmin puede eliminar roles');
        }

        // No se puede eliminar el rol Superadmin
        if ($role->nombre === 'Superadmin') {
            return back()->with('error', 'No se puede eliminar el rol Superadmin');
        }

        // Verificar si hay usuarios con este rol
        if ($role->users()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un rol que tiene usuarios asignados');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Rol eliminado exitosamente');
    }

    /**
     * Show trashed roles
     */
    public function trashed()
    {
        // Solo Superadmin
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Solo el Superadmin puede ver roles eliminados');
        }

        $roles = Role::onlyTrashed()->withCount('users')->latest()->paginate(10);
        return view('roles.trashed', compact('roles'));
    }

    /**
     * Restore a trashed role
     */
    public function restore($id)
    {
        // Solo Superadmin
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Solo el Superadmin puede restaurar roles');
        }

        $role = Role::onlyTrashed()->findOrFail($id);
        $role->restore();

        return redirect()->route('roles.trashed')
            ->with('success', 'Rol restaurado exitosamente');
    }

    /**
     * Force delete a role
     */
    public function forceDelete($id)
    {
        // Solo Superadmin
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Solo el Superadmin puede eliminar roles permanentemente');
        }

        $role = Role::onlyTrashed()->findOrFail($id);

        // No se puede eliminar permanentemente el rol Superadmin
        if ($role->nombre === 'Superadmin') {
            return back()->with('error', 'No se puede eliminar permanentemente el rol Superadmin');
        }

        $role->forceDelete();

        return redirect()->route('roles.trashed')
            ->with('success', 'Rol eliminado permanentemente');
    }
}