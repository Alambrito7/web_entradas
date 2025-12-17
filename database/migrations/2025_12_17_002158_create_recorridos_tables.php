<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabla principal de Recorridos
        Schema::create('recorridos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrada_id')->constrained('entradas')->onDelete('cascade');
            $table->string('nombre')->nullable();
            $table->text('descripcion')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Tabla de Puntos del Recorrido (lugares/waypoints)
        Schema::create('recorrido_puntos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recorrido_id')->constrained('recorridos')->onDelete('cascade');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('latitud', 10, 7);
            $table->decimal('longitud', 10, 7);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recorrido_puntos');
        Schema::dropIfExists('recorridos');
    }
};