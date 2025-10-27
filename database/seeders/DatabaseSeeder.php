<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Cuenta;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Llamar al seeder de categorías
        $this->call(CategoriaSeeder::class);

        // Crear usuario de prueba
        $usuario = Usuario::create([
            'nombre' => 'Usuario Demo',
            'email' => 'demo@finanplan.com',
            'password' => Hash::make('12345678'),
            'fecha_creacion' => now(),
            'tipo_usuario' => 'premium',
        ]);

        // Crear cuentas de ejemplo para el usuario
        Cuenta::create([
            'id_usuario' => $usuario->id_usuario,
            'nombre' => 'Cuenta Principal',
            'tipo' => 'corriente',
            'saldo_inicial' => 5000.00,
            'saldo_actual' => 5000.00,
        ]);

        Cuenta::create([
            'id_usuario' => $usuario->id_usuario,
            'nombre' => 'Ahorros',
            'tipo' => 'ahorros',
            'saldo_inicial' => 10000.00,
            'saldo_actual' => 10000.00,
        ]);

        Cuenta::create([
            'id_usuario' => $usuario->id_usuario,
            'nombre' => 'Tarjeta Crédito',
            'tipo' => 'tarjeta_credito',
            'saldo_inicial' => 0.00,
            'saldo_actual' => 0.00,
        ]);
    }
}