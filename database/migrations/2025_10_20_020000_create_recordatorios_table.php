<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recordatorios', function (Blueprint $table) {
            $table->id('id_recordatorio');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario')->onDelete('cascade');
            
            // --- NUEVOS CAMPOS NECESARIOS PARA LA AUTOMATIZACIÓN ---
            $table->foreignId('id_cuenta')->constrained('cuentas', 'id_cuenta')->onDelete('cascade');
            $table->foreignId('id_categoria')->constrained('categorias', 'id_categoria')->onDelete('cascade');
            $table->enum('tipo', ['ingreso', 'gasto']);
            // -------------------------------------------------------

            $table->string('descripcion', 255);
            $table->decimal('monto', 15, 2);
            $table->date('fecha_vencimiento');
            $table->enum('frecuencia', ['unica', 'mensual', 'anual']);
            $table->enum('estado', ['pendiente', 'procesado', 'cancelado'])->default('pendiente');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recordatorios');
    }
};