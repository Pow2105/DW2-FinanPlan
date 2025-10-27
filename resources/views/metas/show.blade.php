@extends('layouts.app')

@section('title', 'Detalle de Meta - FinanPlan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-flag mr-2 text-blue-600"></i>
            {{ $meta->nombre_meta }}
        </h1>
        <div class="flex space-x-2">
            <a href="{{ route('metas.edit', $meta->id_meta) }}" 
               class="bg-yellow-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-yellow-700 transition">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('metas.index') }}" 
               class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </div>

    <!-- Información de la Meta -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Objetivo -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="text-sm opacity-80 mb-2">
                <i class="fas fa-bullseye mr-2"></i>Objetivo
            </div>
            <div class="text-3xl font-bold">
                ${{ number_format($meta->monto_objetivo, 2) }}
            </div>
        </div>

        <!-- Ahorrado -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="text-sm opacity-80 mb-2">
                <i class="fas fa-piggy-bank mr-2"></i>Ahorrado
            </div>
            <div class="text-3xl font-bold">
                ${{ number_format($meta->monto_actual, 2) }}
            </div>
        </div>

        <!-- Falta -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="text-sm opacity-80 mb-2">
                <i class="fas fa-chart-line mr-2"></i>Falta
            </div>
            <div class="text-3xl font-bold">
                ${{ number_format(max($meta->monto_objetivo - $meta->monto_actual, 0), 2) }}
            </div>
        </div>

        <!-- Fecha Límite -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="text-sm opacity-80 mb-2">
                <i class="fas fa-calendar-alt mr-2"></i>Fecha Límite
            </div>
            <div class="text-xl font-bold">
                {{ $meta->fecha_limite->format('d/m/Y') }}
            </div>
            @php
                $diasRestantes = now()->diffInDays($meta->fecha_limite, false);
            @endphp
            <div class="text-sm opacity-80 mt-1">
                @if($diasRestantes > 0)
                    {{ $diasRestantes }} días restantes
                @elseif($diasRestantes == 0)
                    ¡Hoy vence!
                @else
                    Venció hace {{ abs($diasRestantes) }} días
                @endif
            </div>
        </div>
    </div>

    <!-- Progreso Visual -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
            Progreso hacia tu Meta
        </h3>

        <div class="mb-6">
            <div class="flex justify-between text-sm mb-2">
                <span class="font-medium text-gray-700">{{ $meta->porcentaje }}% completado</span>
                <span class="font-semibold text-gray-900">
                    ${{ number_format($meta->monto_actual, 2) }} / ${{ number_format($meta->monto_objetivo, 2) }}
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-8">
                <div class="bg-gradient-to-r from-green-400 to-green-600 h-8 rounded-full transition-all duration-500 flex items-center justify-end pr-3" 
                     style="width: {{ min($meta->porcentaje, 100) }}%">
                    @if($meta->porcentaje > 15)
                        <span class="text-white text-sm font-bold">{{ $meta->porcentaje }}%</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Estado de la Meta -->
        @if($meta->estado == 'completada')
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-trophy text-green-600 text-3xl mr-4"></i>
                    <div>
                        <h4 class="font-semibold text-green-900 mb-1">¡Felicitaciones! 🎉</h4>
                        <p class="text-sm text-green-800">
                            Has alcanzado tu meta de ahorro. ¡Excelente trabajo!
                        </p>
                    </div>
                </div>
            </div>
        @elseif($meta->porcentaje >= 75)
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-rocket text-blue-600 text-3xl mr-4"></i>
                    <div>
                        <h4 class="font-semibold text-blue-900 mb-1">¡Muy cerca! 🚀</h4>
                        <p class="text-sm text-blue-800">
                            Solo te falta ${{ number_format($meta->monto_objetivo - $meta->monto_actual, 2) }} para alcanzar tu meta.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-chart-line text-yellow-600 text-3xl mr-4"></i>
                    <div>
                        <h4 class="font-semibold text-yellow-900 mb-1">Sigue adelante 💪</h4>
                        <p class="text-sm text-yellow-800">
                            Continúa ahorrando constantemente para alcanzar tu objetivo.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Añadir Fondos -->
    @if($meta->estado != 'completada')
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-plus-circle mr-2 text-green-600"></i>
                Añadir Ahorro a esta Meta
            </h3>

            <form action="{{ route('metas.addFunds', $meta->id_meta) }}" method="POST" class="flex space-x-4">
                @csrf
                <div class="flex-1">
                    <input type="number" 
                           name="monto" 
                           step="0.01"
                           min="0.01"
                           required
                           placeholder="0.00"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <button type="submit" 
                        class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition shadow-lg">
                    <i class="fas fa-piggy-bank mr-2"></i>Añadir Ahorro
                </button>
            </form>

            <p class="text-sm text-gray-500 mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Registra cada aporte que hagas para alcanzar esta meta
            </p>
        </div>
    @endif

    <!-- Proyección de Ahorro -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-calculator mr-2 text-blue-600"></i>
            Proyección de Ahorro
        </h3>

        @php
            $diasRestantes = max(now()->diffInDays($meta->fecha_limite, false), 1);
            $faltante = max($meta->monto_objetivo - $meta->monto_actual, 0);
            $ahorroDiario = $faltante / $diasRestantes;
            $ahorroSemanal = $faltante / ($diasRestantes / 7);
            $ahorroMensual = $faltante / ($diasRestantes / 30);
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="text-sm text-blue-600 mb-1">Por Día</div>
                <div class="text-2xl font-bold text-blue-900">${{ number_format($ahorroDiario, 2) }}</div>
            </div>
            <div class="bg-green-50 rounded-lg p-4">
                <div class="text-sm text-green-600 mb-1">Por Semana</div>
                <div class="text-2xl font-bold text-green-900">${{ number_format($ahorroSemanal, 2) }}</div>
            </div>
            <div class="bg-purple-50 rounded-lg p-4">
                <div class="text-sm text-purple-600 mb-1">Por Mes</div>
                <div class="text-2xl font-bold text-purple-900">${{ number_format($ahorroMensual, 2) }}</div>
            </div>
        </div>

        <p class="text-sm text-gray-600 mt-4">
            <i class="fas fa-lightbulb mr-1 text-yellow-500"></i>
            Para alcanzar tu meta a tiempo, necesitas ahorrar estas cantidades regularmente.
        </p>
    </div>
</div>
@endsection