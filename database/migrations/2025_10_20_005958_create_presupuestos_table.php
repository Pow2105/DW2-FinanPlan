<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id('id_presupuesto');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_categoria');
            $table->decimal('monto_limite', 10, 2);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', ['activo', 'completado', 'excedido'])->default('activo');
            $table->timestamps();

            $table->foreign('id_usuario')
                  ->references('id_usuario')
                  ->on('usuarios')
                  ->onDelete('cascade');
            
            $table->foreign('id_categoria')
                  ->references('id_categoria')
                  ->on('categorias')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
    }
};