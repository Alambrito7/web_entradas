<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bloques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fraternidad_id')->nullable()->constrained('fraternidades')->onDelete('set null');
            $table->string('nombre');
            $table->date('fecha_fundacion')->nullable();
            $table->string('lema')->nullable();
            $table->integer('integrantes_aproximados')->default(0);
            $table->text('historia')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('foto_principal')->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloques');
    }
};