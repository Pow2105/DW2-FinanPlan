@extends('layouts.app')

@section('title', 'Informes - FinanPlan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
            {{ $tituloInforme }}
        </h1>
        
        <div class="bg-white rounded-lg shadow-sm p-2 flex items-center gap-2">
            <form method="GET" action="{{ route('informes.index') }}" class="flex items-center gap-2">
                <label for="mes" class="text-sm font-medium text-gray-600 hidden sm:block">Filtrar:</label>
                <input type="month" 
                       id="mes" 
                       name="mes" 
                       value="{{ $mesSeleccionado }}"
                       class="px-3 py-1.5 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500"
                       onchange="this.form.submit()">
            </form>
            
            @if($esInformeMensual)
                <div class="h-6 w-px bg-gray-300 mx-1"></div>
                <a href="{{ route('informes.index') }}" 
                   class="text-sm text-red-600 hover:text-red-800 font-semibold px-2 py-1.5 rounded hover:bg-red-50 transition">
                    <i class="fas fa-times mr-1"></i>Ver General
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm opacity-80">{{ $esInformeMensual ? 'Ingresos del Mes' : 'Ingresos Totales' }}</div>
                <i class="fas fa-arrow-up text-2xl opacity-80"></i>
            </div>
            <div class="text-3xl font-bold">${{ number_format($totalIngresos, 2) }}</div>
        </div>

        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm opacity-80">{{ $esInformeMensual ? 'Gastos del Mes' : 'Gastos Totales' }}</div>
                <i class="fas fa-arrow-down text-2xl opacity-80"></i>
            </div>
            <div class="text-3xl font-bold">${{ number_format($totalGastos, 2) }}</div>
        </div>

        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm opacity-80">{{ $esInformeMensual ? 'Balance del Mes' : 'Balance Global' }}</div>
                <i class="fas fa-balance-scale text-2xl opacity-80"></i>
            </div>
            <div class="text-3xl font-bold">
                ${{ number_format($balance, 2) }}
            </div>
        </div>
    </div>

    @if(count($gastosPorCategoria) > 0 || count($ingresosPorCategoria) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-chart-pie mr-2 text-blue-600"></i>
                Gastos por Categoría
            </h3>
            <div class="h-80">
                <canvas id="gastosChart"></canvas>
            </div>
        </div>

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
    
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-chart-line mr-2 text-blue-600"></i>
            {{ $esInformeMensual ? 'Flujo Diario' : 'Tendencia Mensual (Último Año)' }}
        </h3>
        <div class="h-80">
             <canvas id="flujoChart"></canvas>
        </div>
    </div>
    @else
        <div class="bg-white rounded-lg shadow-lg p-10 text-center text-gray-500">
            <i class="fas fa-chart-area text-6xl mb-4 opacity-20"></i>
            <p class="text-xl">No hay datos registrados para mostrar gráficas.</p>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg overflow-hidden mt-6">
        <div class="p-6 border-b bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-receipt mr-2 text-red-500"></i>
                {{ $esInformeMensual ? 'Detalle de Gastos del Mes' : 'Últimos Gastos Registrados' }}
            </h3>
            <span class="text-xs font-semibold px-2 py-1 bg-gray-200 rounded text-gray-700">
                {{ count($gastosDetallados) }} Registros
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Categoría</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Cuenta</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Monto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($gastosDetallados as $gasto)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ $gasto->fecha->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                      style="background-color: {{ $gasto->categoria->color }}20; color: {{ $gasto->categoria->color }}">
                                    {{ $gasto->categoria->icono }} {{ $gasto->categoria->nombre }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $gasto->descripcion ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $gasto->cuenta->nombre }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600 text-right">
                                - ${{ number_format($gasto->monto, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No se encontraron registros.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const gastosData = {!! json_encode($gastosPorCategoria) !!};
    const ingresosData = {!! json_encode($ingresosPorCategoria) !!};
    const flujoData = {!! json_encode($transaccionesPorDia) !!};
    const esMensual = {!! json_encode($esInformeMensual) !!};

    const emptyDoughnut = { datasets: [{ data: [1], backgroundColor: ['#f3f4f6'] }], labels: ['Sin datos'] };

    // Gráficos de Dona
    const createDoughnut = (ctx, data) => {
        if (!ctx) return;
        new Chart(ctx, {
            type: 'doughnut',
            data: data.length ? {
                labels: data.map(item => item.nombre),
                datasets: [{
                    data: data.map(item => item.total),
                    backgroundColor: data.map(item => item.color),
                    borderWidth: 2
                }]
            } : emptyDoughnut,
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });
    };

    createDoughnut(document.getElementById('gastosChart'), gastosData);
    createDoughnut(document.getElementById('ingresosChart'), ingresosData);

    // Gráfico de Línea (Inteligente: Días o Meses)
    const ctxFlujo = document.getElementById('flujoChart');
    if (ctxFlujo && flujoData.length > 0) {
        new Chart(ctxFlujo, {
            type: 'line',
            data: {
                labels: flujoData.map(item => {
                    // Si es mensual mostramos "Día X", si es general mostramos "Mes Año"
                    const date = new Date(item.fecha_grupo + (esMensual ? '' : '-02')); // Hack para parsear YYYY-MM
                    // Ajuste zona horaria simple
                    date.setMinutes(date.getMinutes() + date.getTimezoneOffset());
                    
                    if (esMensual) return date.getDate(); // Solo día
                    return date.toLocaleDateString('es-ES', { month: 'short', year: 'numeric' }); // "Ene 2025"
                }),
                datasets: [
                    {
                        label: 'Ingresos',
                        data: flujoData.map(item => item.ingresos),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.3, fill: true
                    },
                    {
                        label: 'Gastos',
                        data: flujoData.map(item => item.gastos),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.3, fill: true
                    }
                ]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { position: 'top' } }, 
                scales: { 
                    y: { beginAtZero: true },
                    x: { title: { display: true, text: esMensual ? 'Día del Mes' : 'Periodo' } }
                } 
            }
        });
    }
</script>
@endpush
@endsection