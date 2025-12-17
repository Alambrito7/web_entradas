<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar campo name si existe
            $table->dropColumn('name');
        });

        Schema::table('users', function (Blueprint $table) {
            // Agregar nuevos campos después de id
            $table->after('id', function ($table) {
                $table->string('nombre', 100);
                $table->string('apellido_paterno', 100);
                $table->string('apellido_materno', 100)->nullable();
                $table->string('ci', 8)->unique();
                $table->enum('departamento', [
                    'La Paz',
                    'Cochabamba',
                    'Santa Cruz',
                    'Oruro',
                    'Potosí',
                    'Chuquisaca',
                    'Tarija',
                    'Beni',
                    'Pando'
                ]);
                $table->string('telefono', 8);
            });
            
            // Agregar soft deletes
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nombre',
                'apellido_paterno',
                'apellido_materno',
                'ci',
                'departamento',
                'telefono'
            ]);
            $table->dropSoftDeletes();
            $table->string('name')->after('id');
        });
    }
};