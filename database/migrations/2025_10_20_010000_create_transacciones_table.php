<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id('id_transaccion');
            
            $table->foreignId('id_cuenta')->constrained('cuentas', 'id_cuenta')->onDelete('cascade');
            $table->foreignId('id_categoria')->constrained('categorias', 'id_categoria')->onDelete('restrict');

            $table->enum('tipo', ['ingreso', 'gasto']);
            $table->decimal('monto', 15, 2);
            $table->date('fecha');
            $table->string('descripcion', 255)->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transacciones');
    }
};