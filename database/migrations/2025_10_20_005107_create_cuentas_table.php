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
        // Esto define la ESTRUCTURA de la tabla 'cuentas'
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id('id_cuenta');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario')->onDelete('cascade');
            
            $table->string('nombre', 100);
            $table->string('tipo', 50)->default('Efectivo'); // Ej: Efectivo, Ahorros, Corriente
            $table->decimal('saldo_inicial', 15, 2)->default(0);
            $table->decimal('saldo_actual', 15, 2)->default(0);
            $table->string('moneda', 10)->default('COP');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuentas');
    }
};