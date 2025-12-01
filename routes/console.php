<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// --- AGREGAR ESTA LÍNEA ---
// Ejecuta el procesador de recordatorios todos los días a las 00:00 (Medianoche)
Schedule::command('recordatorios:procesar')->daily();