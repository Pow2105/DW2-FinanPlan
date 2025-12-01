<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Recordatorio;
use App\Models\Transaccion;
use App\Models\Cuenta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcesarRecordatorios extends Command
{
    /**
     * El nombre y firma del comando.
     */
    protected $signature = 'recordatorios:procesar';

    /**
     * Descripción del comando.
     */
    protected $description = 'Busca recordatorios vencidos y crea las transacciones automáticamente';

    /**
     * Ejecutar el comando.
     */
    public function handle()
    {
        $hoy = now()->toDateString();
        
        $this->info("Buscando recordatorios pendientes para hoy ($hoy)...");

        // Buscar recordatorios pendientes que vencen hoy o antes
        $recordatorios = Recordatorio::whereDate('fecha_vencimiento', '<=', $hoy)
            ->where('estado', 'pendiente')
            ->get();

        if ($recordatorios->isEmpty()) {
            $this->info('No hay recordatorios pendientes para procesar.');
            return;
        }

        foreach ($recordatorios as $recordatorio) {
            DB::transaction(function () use ($recordatorio) {
                // 1. Crear la Transacción Real
                Transaccion::create([
                    'id_cuenta' => $recordatorio->id_cuenta,
                    'id_categoria' => $recordatorio->id_categoria,
                    'tipo' => $recordatorio->tipo,
                    'monto' => $recordatorio->monto,
                    'fecha' => now(), // Se ejecuta hoy
                    'descripcion' => 'Auto: ' . $recordatorio->descripcion,
                ]);

                // 2. Actualizar Saldo de la Cuenta
                $cuenta = Cuenta::find($recordatorio->id_cuenta);
                if ($recordatorio->tipo == 'ingreso') {
                    $cuenta->saldo_actual += $recordatorio->monto;
                } else {
                    $cuenta->saldo_actual -= $recordatorio->monto;
                }
                $cuenta->save();

                // 3. Actualizar el Recordatorio actual a "Procesado"
                $recordatorio->estado = 'procesado';
                $recordatorio->save();

                // 4. (Opcional) Si es recurrente, crear el del próximo mes
                if ($recordatorio->frecuencia == 'mensual') {
                    $nuevoRecordatorio = $recordatorio->replicate();
                    $nuevoRecordatorio->fecha_vencimiento = $recordatorio->fecha_vencimiento->addMonth();
                    $nuevoRecordatorio->estado = 'pendiente';
                    $nuevoRecordatorio->save();
                }
            });

            $this->info("Recordatorio procesado: {$recordatorio->descripcion}");
        }

        $this->info('¡Proceso completado con éxito!');
    }
}