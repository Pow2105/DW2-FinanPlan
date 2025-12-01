<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use App\Models\Usuario; // Descomenta si quieres crear un usuario admin por defecto
// use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cargar Categorías (Vital para que la app funcione)
        $this->call(CategoriaSeeder::class);

        // 2. (Opcional) Usuario Admin para Producción
        // Si quieres tener un usuario fijo al desplegar, descomenta esto:
        /*
        Usuario::create([
            'nombre' => 'Admin',
            'email' => 'admin@finanplan.com',
            'password' => Hash::make('TuContraseñaSegura123'),
            'fecha_creacion' => now(),
            'tipo_usuario' => 'admin',
        ]);
        */
    }
}