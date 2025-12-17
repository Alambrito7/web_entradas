<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabla principal de Fraternidades
        Schema::create('fraternidades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->date('fecha_fundacion')->nullable();
            $table->text('descripcion')->nullable();
            $table->foreignId('danza_id')->nullable()->constrained('danzas')->onDelete('set null');
            $table->string('lema')->nullable();
            $table->string('telefono', 8)->nullable();
            $table->string('correo_electronico')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Tabla para Pasantes Actuales (muchos pasantes por fraternidad)
        Schema::create('fraternidad_pasantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fraternidad_id')->constrained('fraternidades')->onDelete('cascade');
            $table->string('nombre');
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        // Tabla pivot para relación muchos a muchos entre Fraternidades y Entradas
        Schema::create('entrada_fraternidad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fraternidad_id')->constrained('fraternidades')->onDelete('cascade');
            $table->foreignId('entrada_id')->constrained('entradas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('entrada_fraternidad');
        Schema::dropIfExists('fraternidad_pasantes');
        Schema::dropIfExists('fraternidades');
    }
};