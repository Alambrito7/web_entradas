<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function trashed()
    {
        $users = User::onlyTrashed()->with('role')->latest()->paginate(10);
        return view('users.trashed', compact('users'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        
        return redirect()->route('users.trashed')
            ->with('success', 'Usuario restaurado exitosamente');
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();
        
        return redirect()->route('users.trashed')
            ->with('success', 'Usuario eliminado permanentemente');
    }

    public function create()
    {
        $departamentos = User::getDepartamentos();
        
        // Solo Superadmin y Administrador pueden asignar roles
        $roles = collect();
        if (auth()->user() && auth()->user()->isAdminOrAbove()) {
            $roles = Role::where('activo', true)->orderBy('nombre')->get();
        }
        
        return view('users.create', compact('departamentos', 'roles'));
    }

    public function store(Request $request)
    {
        // Validación base
        $rules = [
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'required|numeric|digits:8',
            'ci' => 'required|string|max:20',
            'departamento' => 'required|in:' . implode(',', User::getDepartamentos()),
        ];

        // Agregar validación de role_id si el usuario puede asignar roles
        if (auth()->user()->isAdminOrAbove()) {
            $rules['role_id'] = 'nullable|exists:roles,id';
        }

        $validated = $request->validate($rules);

        // Verificar permisos para asignar rol
        if (isset($validated['role_id']) && !auth()->user()->isAdminOrAbove()) {
            return back()->withInput()->with('error', 'No tienes permisos para asignar roles');
        }

        // Hash de la contraseña
        $validated['password'] = Hash::make($validated['password']);
        
        // Crear usuario
        User::create($validated);
        
        return redirect()->route('users.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    public function show(User $user)
    {
        $user->load('role');
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $departamentos = User::getDepartamentos();
        
        // Solo Superadmin y Administrador pueden asignar roles
        $roles = collect();
        if (auth()->user() && auth()->user()->isAdminOrAbove()) {
            $roles = Role::where('activo', true)->orderBy('nombre')->get();
        }
        
        return view('users.edit', compact('user', 'departamentos', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // Validación base
        $rules = [
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'telefono' => 'required|numeric|digits:8',
            'ci' => 'required|string|max:20',
            'departamento' => 'required|in:' . implode(',', User::getDepartamentos()),
        ];

        // Agregar validación de role_id si el usuario puede asignar roles
        if (auth()->user()->isAdminOrAbove()) {
            $rules['role_id'] = 'nullable|exists:roles,id';
        }

        $validated = $request->validate($rules);

        // Verificar permisos para asignar rol
        if (isset($validated['role_id']) && !auth()->user()->isAdminOrAbove()) {
            unset($validated['role_id']);
        }

        // Proteger al Superadmin: solo otro Superadmin puede cambiar su rol
        if ($user->isSuperadmin() && !auth()->user()->isSuperadmin()) {
            unset($validated['role_id']);
        }

        // Manejar la contraseña
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Actualizar usuario
        $user->update($validated);
        
        return redirect()->route('users.show', $user)
            ->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy(User $user)
    {
        // No se puede eliminar a sí mismo
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta');
        }

        // Solo Superadmin puede eliminar a otros Superadmins
        if ($user->isSuperadmin() && !auth()->user()->isSuperadmin()) {
            return back()->with('error', 'No tienes permisos para eliminar a un Superadmin');
        }

        $user->delete();
        
        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }
}