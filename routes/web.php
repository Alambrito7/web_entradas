<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DanzaController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\FraternidadController;
use App\Http\Controllers\RecorridoController;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);
    
    // Rutas para usuarios eliminados
    Route::get('users-trashed', [UserController::class, 'trashed'])->name('users.trashed');
    Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');


     // Rutas de danzas
    Route::resource('danzas', DanzaController::class);
    Route::get('danzas-trashed', [DanzaController::class, 'trashed'])->name('danzas.trashed');
    Route::post('danzas/{id}/restore', [DanzaController::class, 'restore'])->name('danzas.restore');
    Route::delete('danzas/{id}/force-delete', [DanzaController::class, 'forceDelete'])->name('danzas.force-delete');


     // Rutas de entradas
    Route::resource('entradas', EntradaController::class);
    Route::get('entradas-trashed', [EntradaController::class, 'trashed'])->name('entradas.trashed');
    Route::post('entradas/{id}/restore', [EntradaController::class, 'restore'])->name('entradas.restore');
    Route::delete('entradas/{id}/force-delete', [EntradaController::class, 'forceDelete'])->name('entradas.force-delete');

    // Rutas de fraternidades
    Route::resource('fraternidades', FraternidadController::class);
    Route::get('fraternidades-trashed', [FraternidadController::class, 'trashed'])->name('fraternidades.trashed');
    Route::post('fraternidades/{id}/restore', [FraternidadController::class, 'restore'])->name('fraternidades.restore');
    Route::delete('fraternidades/{id}/force-delete', [FraternidadController::class, 'forceDelete'])->name('fraternidades.force-delete');


    // Rutas de recorridos
    Route::resource('recorridos', RecorridoController::class);
    Route::get('recorridos-trashed', [RecorridoController::class, 'trashed'])->name('recorridos.trashed');
    Route::post('recorridos/{id}/restore', [RecorridoController::class, 'restore'])->name('recorridos.restore');
    Route::delete('recorridos/{id}/force-delete', [RecorridoController::class, 'forceDelete'])->name('recorridos.force-delete');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
