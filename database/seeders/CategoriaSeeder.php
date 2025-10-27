<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            // Ingresos
            ['nombre' => 'Salario', 'tipo' => 'ingreso', 'icono' => '💼', 'color' => '#10B981'],
            ['nombre' => 'Freelance', 'tipo' => 'ingreso', 'icono' => '💻', 'color' => '#3B82F6'],
            ['nombre' => 'Inversiones', 'tipo' => 'ingreso', 'icono' => '📈', 'color' => '#8B5CF6'],
            ['nombre' => 'Otros Ingresos', 'tipo' => 'ingreso', 'icono' => '💰', 'color' => '#F59E0B'],
            
            // Gastos
            ['nombre' => 'Alimentación', 'tipo' => 'gasto', 'icono' => '🍕', 'color' => '#EF4444'],
            ['nombre' => 'Transporte', 'tipo' => 'gasto', 'icono' => '🚗', 'color' => '#F97316'],
            ['nombre' => 'Vivienda', 'tipo' => 'gasto', 'icono' => '🏠', 'color' => '#EC4899'],
            ['nombre' => 'Servicios', 'tipo' => 'gasto', 'icono' => '💡', 'color' => '#14B8A6'],
            ['nombre' => 'Entretenimiento', 'tipo' => 'gasto', 'icono' => '🎬', 'color' => '#6366F1'],
            ['nombre' => 'Salud', 'tipo' => 'gasto', 'icono' => '⚕️', 'color' => '#10B981'],
            ['nombre' => 'Educación', 'tipo' => 'gasto', 'icono' => '📚', 'color' => '#3B82F6'],
            ['nombre' => 'Ropa', 'tipo' => 'gasto', 'icono' => '👕', 'color' => '#8B5CF6'],
            ['nombre' => 'Otros Gastos', 'tipo' => 'gasto', 'icono' => '📦', 'color' => '#6B7280'],
        ];

        DB::table('categorias')->insert($categorias);
    }
}