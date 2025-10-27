@extends('layouts.app')

@section('title', 'Detalle de Presupuesto - FinanPlan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">
            <span class="text-3xl mr-2">{{ $presupuesto->categoria->icono ?? '📊' }}</span>
            {{ $presupuesto->categoria->nombre }}
        </h1>
        <div class="flex space-x-2">
            <a href="{{ route('presupuestos.edit', $presupuesto->id_presupuesto) }}" 
               class="bg-yellow-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-yellow-700 transition">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('presupuestos.index') }}" 
               class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </div>

    <!-- Información del Presupuesto -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Período -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="text-sm text-gray-600 mb-2">
                <i class="fas fa-calendar-alt mr-2"></i>Período
            </div>
            <div class="text-lg font-bold text-gray-900">
                {{ $presupuesto->fecha_inicio->format('d/m/Y') }}
            </div>
            <div class="text-sm text-gray-500">hasta</div>
            <div class="text-lg font-bold text-gray-900">
                {{ $presupuesto->fecha_fin->format('d/m/Y') }}
            </div>
        </div>

        <!-- Límite -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="text-sm opacity-80 mb-2">
                <i class="fas fa-tag mr-2"></i>Monto Límite
            </div>
            <div class="text-3xl font-bold">
                ${{ number_format($presupuesto->monto_limite, 2) }}
            </div>
        </div>

        <!-- Gastado -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="text-sm opacity-80 mb-2">
                <i class="fas fa-shopping-cart mr-2"></i>Total Gastado
            </div>
            <div class="text-3xl font-bold">
                ${{ number_format($presupuesto->gasto_actual, 2) }}
            </div>
        </div>

        <!-- Disponible -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="text-sm opacity-80 mb-2">
                <i class="fas fa-wallet mr-2"></i>Disponible
            </div>
            <div class="text-3xl font-bold">
                ${{ number_format(max($presupuesto->monto_limite - $presupuesto->gasto_actual, 0), 2) }}
            </div>
        </div>
    </div>

    <!-- Progreso del Presupuesto -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-chart-line mr-2 text-blue-600"></i>
            Progreso del Presupuesto
        </h3>
        
        @php
            $porcentaje = ($presupuesto->monto_limite > 0) 
                ? round(($presupuesto->gasto_actual / $presupuesto->monto_limite) * 100, 2) 
                : 0;
        @endphp

        <div class="mb-4">
            <div class="flex justify-between text-sm mb-2">
                <span class="font-medium text-gray-700">Utilización</span>
                <span class="font-semibold">{{ $porcentaje }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-6">
                <div class="h-6 rounded-full transition-all duration-300 flex items-center justify-end pr-2
                    {{ $porcentaje < 80 ? 'bg-green-500' : '' }}
                    {{ $porcentaje >= 80 && $porcentaje < 100 ? 'bg-yellow-500' : '' }}
                    {{ $porcentaje >= 100 ? 'bg-red-500' : '' }}" 
                    style="width: {{ min($porcentaje, 100) }}%">
                    @if($porcentaje > 10)
                        <span class="text-white text-xs font-bold">{{ $porcentaje }}%</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Alertas -->
        @if($porcentaje >= 100)
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-red-900 mb-1">¡Presupuesto Excedido!</h4>
                        <p class="text-sm text-red-800">
                            Has gastado ${{ number_format($presupuesto->gasto_actual - $presupuesto->monto_limite, 2) }} 
                            por encima de tu límite. Considera reducir gastos en esta categoría.
                        </p>
                    </div>
                </div>
            </div>
        @elseif($porcentaje >= 80)
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-yellow-600 text-xl mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-yellow-900 mb-1">Acercándose al Límite</h4>
                        <p class="text-sm text-yellow-800">
                            Te quedan ${{ number_format($presupuesto->monto_limite - $presupuesto->gasto_actual, 2) }} 
                            antes de alcanzar tu límite. ¡Controla tus gastos!
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-600 text-xl mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-green-900 mb-1">¡Vas Muy Bien!</h4>
                        <p class="text-sm text-green-800">
                            Tu presupuesto está controlado. Sigues teniendo 
                            ${{ number_format($presupuesto->monto_limite - $presupuesto->gasto_actual, 2) }} disponibles.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Transacciones Relacionadas -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-list mr-2 text-blue-600"></i>
                Transacciones de este Presupuesto
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cuenta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transacciones as $transaccion)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $transaccion->fecha->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $transaccion->cuenta->nombre }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $transaccion->descripcion ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-red-600">
                                    -${{ number_format($transaccion->monto, 2) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                No hay transacciones en este período para esta categoría
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection