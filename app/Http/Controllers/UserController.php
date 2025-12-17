<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    // NUEVO: Mostrar usuarios eliminados
    public function trashed()
    {
        $users = User::onlyTrashed()->latest()->paginate(10);
        return view('users.trashed', compact('users'));
    }

    // NUEVO: Restaurar usuario eliminado
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        
        return redirect()->route('users.trashed')
            ->with('success', 'Usuario restaurado exitosamente');
    }

    // NUEVO: Eliminar permanentemente
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
        return view('users.create', compact('departamentos'));
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        
        User::create($data);
        
        return redirect()->route('users.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $departamentos = User::getDepartamentos();
        return view('users.edit', compact('user', 'departamentos'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        
        $user->update($data);
        
        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy(User $user)
    {
        $user->delete();
        
        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }
}