<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metas_ahorro', function (Blueprint $table) {
            $table->id('id_meta');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario')->onDelete('cascade');
            $table->string('nombre_meta', 100);
            $table->decimal('monto_objetivo', 15, 2);
            $table->decimal('monto_actual', 15, 2)->default(0);
            $table->date('fecha_limite');
            $table->enum('estado', ['en_progreso', 'completada', 'vencida'])->default('en_progreso');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metas_ahorro');
    }
};