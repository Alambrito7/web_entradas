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
        Schema::create('bloque_responsables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bloque_id')->constrained('bloques')->onDelete('cascade');
            $table->string('nombre');
            $table->string('telefono', 8)->nullable();
            $table->string('email')->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloque_responsables');
    }
};