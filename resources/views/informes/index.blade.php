@extends('layouts.app')

@section('title', 'Informes - FinanPlan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
            Informes Financieros
        </h1>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form method="GET" action="{{ route('informes.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt mr-2"></i>Fecha Inicio
                </label>
                <input type="date" 
                       id="fecha_inicio" 
                       name="fecha_inicio" 
                       value="{{ $fechaInicio }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-check mr-2"></i>Fecha Fin
                </label>
                <input type="date" 
                       id="fecha_fin" 
                       name="fecha_fin" 
                       value="{{ $fechaFin }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                    <i class="fas fa-filter mr-2"></i>Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Resumen General -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Ingresos -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm opacity-80">Total Ingresos</div>
                <i class="fas fa-arrow-up text-2xl opacity-80"></i>
            </div>
            <div class="text-3xl font-bold">${{ number_format($totalIngresos, 2) }}</div>
        </div>

        <!-- Gastos -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm opacity-80">Total Gastos</div>
                <i class="fas fa-arrow-down text-2xl opacity-80"></i>
            </div>
            <div class="text-3xl font-bold">${{ number_format($totalGastos, 2) }}</div>
        </div>

        <!-- Balance -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm opacity-80">Balance</div>
                <i class="fas fa-balance-scale text-2xl opacity-80"></i>
            </div>
            <div class="text-3xl font-bold">
                ${{ number_format($balance, 2) }}
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gastos por Categoría -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-chart-pie mr-2 text-blue-600"></i>
                Gastos por Categoría
            </h3>
            <div class="h-80">
                <canvas id="gastosChart"></canvas>
            </div>
        </div>

        <!-- Ingresos por Categoría -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-chart-pie mr-2 text-green-600"></i>
                Ingresos por Categoría
            </h3>
            <div class="h-80">
                <canvas id="ingresosChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Flujo de Efectivo -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-chart-line mr-2 text-blue-600"></i>
            Flujo de Efectivo
        </h3>
        <div class="h-80">
            <canvas id="flujoChart"></canvas>
        </div>
    </div>

    <!-- Detalle por Categorías -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Tabla de Gastos -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 border-b bg-red-50">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-list mr-2 text-red-600"></i>
                    Detalle de Gastos
                </h3>
            </div>
            <div class="p-6">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Categoría</th>
                            <th class="text-right py-2">Monto</th>
                            <th class="text-right py-2">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gastosPorCategoria as $gasto)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3">
                                    <span class="text-lg mr-2">{{ $gasto->icono }}</span>
                                    {{ $gasto->nombre }}
                                </td>
                                <td class="text-right font-semibold text-red-600">
                                    ${{ number_format($gasto->total, 2) }}
                                </td>
                                <td class="text-right text-gray-600">
                                    {{ $totalGastos > 0 ? round(($gasto->total / $totalGastos) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-gray-500">
                                    No hay gastos en este período
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabla de Ingresos -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 border-b bg-green-50">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-list mr-2 text-green-600"></i>
                    Detalle de Ingresos
                </h3>
            </div>
            <div class="p-6">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Categoría</th>
                            <th class="text-right py-2">Monto</th>
                            <th class="text-right py-2">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ingresosPorCategoria as $ingreso)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3">
                                    <span class="text-lg mr-2">{{ $ingreso->icono }}</span>
                                    {{ $ingreso->nombre }}
                                </td>
                                <td class="text-right font-semibold text-green-600">
                                    ${{ number_format($ingreso->total, 2) }}
                                </td>
                                <td class="text-right text-gray-600">
                                    {{ $totalIngresos > 0 ? round(($ingreso->total / $totalIngresos) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-gray-500">
                                    No hay ingresos en este período
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Datos para los gráficos
    const gastosData = {!! json_encode($gastosPorCategoria) !!};
    const ingresosData = {!! json_encode($ingresosPorCategoria) !!};
    const flujoData = {!! json_encode($transaccionesPorDia) !!};

    // Gráfico de Gastos
    const ctxGastos = document.getElementById('gastosChart');
    new Chart(ctxGastos, {
        type: 'doughnut',
        data: {
            labels: gastosData.map(item => item.nombre),
            datasets: [{
                data: gastosData.map(item => item.total),
                backgroundColor: gastosData.map(item => item.color),
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfico de Ingresos
    const ctxIngresos = document.getElementById('ingresosChart');
    new Chart(ctxIngresos, {
        type: 'doughnut',
        data: {
            labels: ingresosData.map(item => item.nombre),
            datasets: [{
                data: ingresosData.map(item => item.total),
                backgroundColor: ingresosData.map(item => item.color),
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfico de Flujo
    const ctxFlujo = document.getElementById('flujoChart');
    new Chart(ctxFlujo, {
        type: 'line',
        data: {
            labels: flujoData.map(item => new Date(item.fecha).toLocaleDateString()),
            datasets: [
                {
                    label: 'Ingresos',
                    data: flujoData.map(item => item.ingresos),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Gastos',
                    data: flujoData.map(item => item.gastos),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
@endsection