<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabla principal de Danzas
        Schema::create('danzas', function (Blueprint $table) {
            $table->id();
            
            // Datos básicos
            $table->string('nombre');
            $table->enum('categoria', [
                'Pesada', 
                'Liviana', 
                'Autóctona', 
                'Mestiza', 
                'Ritual', 
                'Urbana', 
                'Oriental/Amazónica'
            ]);
            $table->enum('departamento_principal', [
                'La Paz', 
                'Oruro', 
                'Cochabamba', 
                'Potosí',
                'Chuquisaca', 
                'Tarija', 
                'Santa Cruz', 
                'Beni', 
                'Pando'
            ]);
            $table->string('region_origen');
            
            // Origen (flexible)
            $table->enum('tipo_fecha_origen', ['siglo', 'anio_aprox', 'rango', 'exacta'])->default('siglo');
            $table->integer('siglo_origen')->nullable();
            $table->integer('anio_aprox')->nullable();
            $table->integer('anio_inicio')->nullable();
            $table->integer('anio_fin')->nullable();
            $table->date('fecha_origen')->nullable();
            
            $table->enum('estado_ficha', ['Borrador', 'En revisión', 'Publicada'])->default('Borrador');
            $table->text('descripcion_corta')->nullable();
            
            // Historia y cultura
            $table->text('historia_origen')->nullable();
            $table->text('significado_cultural')->nullable();
            
            // Música y coreografía
            $table->string('instrumentos')->nullable();
            $table->string('ritmo_compas')->nullable();
            $table->text('pasos_basicos')->nullable();
            $table->text('formacion')->nullable();
            
            // Patrimonio
            $table->text('declaratorias')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });

        // Tabla de Personajes/Roles
        Schema::create('danza_personajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('danza_id')->constrained('danzas')->onDelete('cascade');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        // Tabla de Vestimentas (relacionada con Personajes)
        Schema::create('danza_vestimentas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personaje_id')->constrained('danza_personajes')->onDelete('cascade');
            $table->string('elemento');
            $table->text('descripcion')->nullable();
            $table->string('material')->nullable();
            $table->decimal('peso', 8, 2)->nullable();
            $table->decimal('costo', 10, 2)->nullable();
            $table->timestamps();
        });

        // Tabla de Multimedia (Fotos y Videos)
        Schema::create('danza_multimedia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('danza_id')->constrained('danzas')->onDelete('cascade');
            $table->enum('tipo', ['Foto', 'Video']);
            $table->string('archivo')->nullable();
            $table->string('url')->nullable();
            $table->string('titulo')->nullable();
            $table->string('creditos')->nullable();
            $table->timestamps();
        });

        // Tabla de Documentos de Patrimonio
        Schema::create('danza_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('danza_id')->constrained('danzas')->onDelete('cascade');
            $table->enum('tipo', ['PDF', 'Word', 'Libro', 'Resolución', 'Otro']);
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('archivo')->nullable();
            $table->string('fuente')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('danza_documentos');
        Schema::dropIfExists('danza_multimedia');
        Schema::dropIfExists('danza_vestimentas');
        Schema::dropIfExists('danza_personajes');
        Schema::dropIfExists('danzas');
    }
};