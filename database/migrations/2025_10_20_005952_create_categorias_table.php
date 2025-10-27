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
        // Esto solo define la ESTRUCTURA de la tabla 'categorias'
        Schema::create('categorias', function (Blueprint $table) {
            $table->id('id_categoria');
            $table->string('nombre', 100);
            $table->enum('tipo', ['ingreso', 'gasto']);
            $table->string('color', 7)->nullable(); // Ej: #FFFFFF
            $table->string('icono', 50)->nullable(); // Ej: 'fa-car'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};