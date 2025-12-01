@extends('layouts.app')

@section('title', 'Detalle de Meta - FinanPlan')

@section('content')
<div class="space-y-6">
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

    @php
        $estadoReal = $meta->estado_real; // Usamos el atributo dinámico nuevo
        $porcentaje = $meta->porcentajeProgreso();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="text-sm opacity-80 mb-2">
                <i class="fas fa-bullseye mr-2"></i>Objetivo
            </div>
            <div class="text-3xl font-bold">
                ${{ number_format($meta->monto_objetivo, 2) }}
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="text-sm opacity-80 mb-2">
                <i class="fas fa-piggy-bank mr-2"></i>Ahorrado
            </div>
            <div class="text-3xl font-bold">
                ${{ number_format($meta->monto_actual, 2) }}
            </div>
        </div>

        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="text-sm opacity-80 mb-2">
                <i class="fas fa-chart-line mr-2"></i>Falta
            </div>
            <div class="text-3xl font-bold">
                ${{ number_format(max($meta->monto_objetivo - $meta->monto_actual, 0), 2) }}
            </div>
        </div>

        <div class="bg-gradient-to-r {{ $estadoReal == 'vencida' ? 'from-red-500 to-red-600' : 'from-purple-500 to-purple-600' }} rounded-lg shadow-lg p-6 text-white">
            <div class="text-sm opacity-80 mb-2">
                <i class="fas fa-calendar-alt mr-2"></i>{{ $estadoReal == 'vencida' ? 'Vencida' : 'Fecha Límite' }}
            </div>
            <div class="text-xl font-bold">
                {{ $meta->fecha_limite->format('d/m/Y') }}
            </div>
            @php
                $diasRestantes = now()->diffInDays($meta->fecha_limite, false);
            @endphp
            <div class="text-sm opacity-80 mt-1">
                @if($estadoReal == 'vencida')
                    Venció hace {{ abs((int)$diasRestantes) }} días
                @elseif($diasRestantes == 0)
                    ¡Hoy vence!
                @else
                    {{ $diasRestantes }} días restantes
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
            Progreso hacia tu Meta
        </h3>

        <div class="mb-6">
            <div class="flex justify-between text-sm mb-2">
                <span class="font-medium text-gray-700">{{ $porcentaje }}% completado</span>
                <span class="font-semibold text-gray-900">
                    ${{ number_format($meta->monto_actual, 2) }} / ${{ number_format($meta->monto_objetivo, 2) }}
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-8 overflow-hidden">
                <div class="h-8 rounded-full transition-all duration-500 flex items-center justify-end pr-3
                    {{ $estadoReal == 'vencida' ? 'bg-red-500' : 'bg-gradient-to-r from-green-400 to-green-600' }}" 
                     style="width: {{ min($porcentaje, 100) }}%">
                    @if($porcentaje > 15)
                        <span class="text-white text-sm font-bold">{{ $porcentaje }}%</span>
                    @endif
                </div>
            </div>
        </div>

        @if($estadoReal == 'completada')
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
        @elseif($estadoReal == 'vencida')
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-times-circle text-red-600 text-3xl mr-4"></i>
                    <div>
                        <h4 class="font-semibold text-red-900 mb-1">Meta Vencida</h4>
                        <p class="text-sm text-red-800">
                            Se acabó el tiempo y no alcanzaste el objetivo. ¡Inténtalo de nuevo o extiende la fecha!
                        </p>
                    </div>
                </div>
            </div>
        @elseif($porcentaje >= 75)
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

    @if($estadoReal != 'completada')
        <div class="bg-white rounded-lg shadow-lg p-6 border-t-4 border-green-500">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-plus-circle mr-2 text-green-600"></i>
                Registrar Nuevo Ahorro
            </h3>

            <form action="{{ route('metas.addFunds', $meta->id_meta) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Monto a Ahorrar</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" 
                               name="monto" 
                               step="0.01"
                               min="0.01"
                               required
                               placeholder="0.00"
                               class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Desde la Cuenta</label>
                    <select name="id_cuenta" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500">
                        @foreach($cuentas as $cuenta)
                            <option value="{{ $cuenta->id_cuenta }}">
                                {{ $cuenta->nombre }} (${{ number_format($cuenta->saldo_actual, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                    <select name="id_categoria" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500">
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id_categoria }}" {{ str_contains(strtolower($cat->nombre), 'ahorro') ? 'selected' : '' }}>
                                {{ $cat->icono }} {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" 
                        class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition shadow-md h-10">
                    <i class="fas fa-save mr-2"></i>Guardar
                </button>
            </form>

            <p class="text-xs text-gray-500 mt-2 bg-green-50 p-2 rounded">
                <i class="fas fa-info-circle mr-1"></i>
                Este monto se descontará de tu cuenta seleccionada y se creará una transacción de gasto automáticamente.
            </p>
        </div>
    @endif

    @if($estadoReal != 'completada' && $estadoReal != 'vencida')
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-calculator mr-2 text-blue-600"></i>
            Para llegar a la meta necesitas ahorrar:
        </h3>

        @php
            $diasRestantes = max(now()->diffInDays($meta->fecha_limite, false), 1);
            $faltante = max($meta->monto_objetivo - $meta->monto_actual, 0);
            $ahorroDiario = $faltante / $diasRestantes;
            $ahorroSemanal = $faltante / ($diasRestantes / 7);
            $ahorroMensual = $diasRestantes > 30 ? $faltante / ($diasRestantes / 30) : $faltante;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <div class="text-sm text-blue-600 mb-1">Diariamente</div>
                <div class="text-2xl font-bold text-blue-900">${{ number_format($ahorroDiario, 2) }}</div>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <div class="text-sm text-green-600 mb-1">Semanalmente</div>
                <div class="text-2xl font-bold text-green-900">${{ number_format($ahorroSemanal, 2) }}</div>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 text-center">
                <div class="text-sm text-purple-600 mb-1">Mensualmente</div>
                <div class="text-2xl font-bold text-purple-900">${{ number_format($ahorroMensual, 2) }}</div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection