<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/'
            ],
            'apellido_paterno' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/'
            ],
            'apellido_materno' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/'
            ],
            'ci' => [
                'required',
                'numeric',
                'digits_between:1,8',
                'unique:users,ci,' . $userId
            ],
            'departamento' => [
                'required',
                'in:La Paz,Cochabamba,Santa Cruz,Oruro,Potosí,Chuquisaca,Tarija,Beni,Pando'
            ],
            'telefono' => [
                'required',
                'numeric',
                'digits:8'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $userId
            ],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.regex' => 'El nombre solo puede contener letras',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio',
            'apellido_paterno.regex' => 'El apellido paterno solo puede contener letras',
            'apellido_materno.regex' => 'El apellido materno solo puede contener letras',
            'ci.required' => 'El CI es obligatorio',
            'ci.numeric' => 'El CI debe ser numérico',
            'ci.digits_between' => 'El CI debe tener máximo 8 dígitos',
            'ci.unique' => 'Este CI ya está registrado',
            'departamento.required' => 'Debe seleccionar un departamento',
            'departamento.in' => 'El departamento seleccionado no es válido',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.numeric' => 'El teléfono debe ser numérico',
            'telefono.digits' => 'El teléfono debe tener exactamente 8 dígitos',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico no es válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
        ];
    }
}