<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id('id_cuenta');
            $table->unsignedBigInteger('id_usuario'); // Relación con usuarios
            $table->string('nombre');
            $table->enum('tipo', ['corriente', 'ahorros', 'tarjeta_credito']);
            $table->decimal('saldo_inicial', 10, 2);
            $table->decimal('saldo_actual', 10, 2);
            $table->timestamps();

            // Clave foránea
            $table->foreign('id_usuario')
                  ->references('id_usuario')
                  ->on('usuarios')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuentas');
    }
};
