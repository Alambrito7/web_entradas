<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nombre' => ['required', 'string', 'max:100', 'regex:/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/'],
            'apellido_paterno' => ['required', 'string', 'max:100', 'regex:/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/'],
            'apellido_materno' => ['nullable', 'string', 'max:100', 'regex:/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/'],
            'ci' => ['required', 'numeric', 'digits_between:1,8', 'unique:users'],
            'departamento' => ['required', 'in:La Paz,Cochabamba,Santa Cruz,Oruro,Potosí,Chuquisaca,Tarija,Beni,Pando'],
            'telefono' => ['required', 'numeric', 'digits:8'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'nombre' => $data['nombre'],
            'apellido_paterno' => $data['apellido_paterno'],
            'apellido_materno' => $data['apellido_materno'] ?? null,
            'ci' => $data['ci'],
            'departamento' => $data['departamento'],
            'telefono' => $data['telefono'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}