@extends('layouts.app')

@section('title', 'Dashboard - FinanPlan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-home mr-2 text-blue-600"></i>
                Bienvenido, {{ Auth::user()->nombre }}
            </h1>
            <p class="mt-2 text-gray-600">Aquí está el resumen de tus finanzas</p>
        </div>
    </div>

    <!-- Resumen de Cuentas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total en Cuentas -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow-lg rounded-lg">
            <div class="p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-wallet text-4xl opacity-80"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium truncate opacity-80">Total en Cuentas</dt>
                            <dd class="text-2xl font-bold">${{ number_format($totalCuentas, 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ingresos del Mes -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 overflow-hidden shadow-lg rounded-lg">
            <div class="p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-arrow-up text-4xl opacity-80"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium truncate opacity-80">Ingresos del Mes</dt>
                            <dd class="text-2xl font-bold">${{ number_format($ingresosDelMes, 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gastos del Mes -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 overflow-hidden shadow-lg rounded-lg">
            <div class="p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-arrow-down text-4xl opacity-80"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium truncate opacity-80">Gastos del Mes</dt>
                            <dd class="text-2xl font-bold">${{ number_format($gastosDelMes, 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 overflow-hidden shadow-lg rounded-lg">
            <div class="p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-balance-scale text-4xl opacity-80"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium truncate opacity-80">Balance del Mes</dt>
                            <dd class="text-2xl font-bold">${{ number_format($ingresosDelMes - $gastosDelMes, 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Gastos y Presupuestos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gastos por Categoría -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-chart-pie mr-2 text-blue-600"></i>
                    Gastos por Categoría (Este Mes)
                </h3>
                <div class="h-64">
                    <canvas id="gastosChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Presupuestos Activos -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-calculator mr-2 text-blue-600"></i>
                    Presupuestos Activos
                </h3>
                <div class="space-y-4">
                    @forelse($presupuestos->take(5) as $presupuesto)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium">{{ $presupuesto->categoria->nombre }}</span>
                                <span>${{ number_format($presupuesto->gasto_actual, 2) }} / ${{ number_format($presupuesto->monto_limite, 2) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($presupuesto->porcentaje, 100) }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $presupuesto->porcentaje }}%</span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No hay presupuestos activos</p>
                    @endforelse
                </div>
                @if($presupuestos->count() > 0)
                    <a href="{{ route('presupuestos.index') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">Ver todos →</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Metas de Ahorro y Últimas Transacciones -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Metas de Ahorro -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-bullseye mr-2 text-blue-600"></i>
                    Metas de Ahorro
                </h3>
                <div class="space-y-4">
                    @forelse($metas->take(3) as $meta)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium">{{ $meta->nombre_meta }}</span>
                                <span>${{ number_format($meta->monto_actual, 2) }} / ${{ number_format($meta->monto_objetivo, 2) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ min($meta->porcentaje, 100) }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $meta->porcentaje }}% completado</span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No hay metas de ahorro</p>
                    @endforelse
                </div>
                @if($metas->count() > 0)
                    <a href="{{ route('metas.index') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">Ver todas →</a>
                @endif
            </div>
        </div>

        <!-- Últimas Transacciones -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-exchange-alt mr-2 text-blue-600"></i>
                    Últimas Transacciones
                </h3>
                <div class="space-y-3">
                    @forelse($ultimasTransacciones->take(5) as $transaccion)
                        <div class="flex justify-between items-center py-2 border-b">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    @if($transaccion->tipo == 'ingreso')
                                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                                            <i class="fas fa-arrow-up text-green-600"></i>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-red-100">
                                            <i class="fas fa-arrow-down text-red-600"></i>
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $transaccion->categoria->nombre }}</p>
                                    <p class="text-xs text-gray-500">{{ $transaccion->fecha->format('d/m/Y') }} - {{ $transaccion->cuenta->nombre }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold {{ $transaccion->tipo == 'ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaccion->tipo == 'ingreso' ? '+' : '-' }}${{ number_format($transaccion->monto, 2) }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No hay transacciones recientes</p>
                    @endforelse
                </div>
                @if($ultimasTransacciones->count() > 0)
                    <a href="{{ route('transacciones.index') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">Ver todas →</a>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Gráfico de gastos por categoría
    const ctx = document.getElementById('gastosChart');
    const gastosData = {!! json_encode($gastosPorCategoria) !!};
    
    new Chart(ctx, {
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
</script>
@endpush
@endsection