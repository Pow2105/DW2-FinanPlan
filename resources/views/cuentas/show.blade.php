@extends('layouts.app')

@section('title', 'Detalle de Cuenta - FinanPlan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">
            @switch($cuenta->tipo)
                @case('ahorros')
                    <i class="fas fa-piggy-bank mr-2 text-green-600"></i>
                    @break
                @case('corriente')
                    <i class="fas fa-university mr-2 text-blue-600"></i>
                    @break
                @case('tarjeta_credito')
                    <i class="fas fa-credit-card mr-2 text-purple-600"></i>
                    @break
                @case('efectivo')
                    <i class="fas fa-money-bill-wave mr-2 text-yellow-600"></i>
                    @break
            @endswitch
            {{ $cuenta->nombre }}
        </h1>
        <div class="flex space-x-2">
            <a href="{{ route('cuentas.edit', $cuenta->id_cuenta) }}" 
               class="bg-yellow-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-yellow-700 transition">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('cuentas.index') }}" 
               class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </div>

    <!-- Información de la Cuenta -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Tipo -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="text-sm text-gray-600 mb-2">Tipo de Cuenta</div>
            <div class="text-xl font-bold text-gray-900 capitalize">
                {{ str_replace('_', ' ', $cuenta->tipo) }}
            </div>
        </div>

        <!-- Saldo Inicial -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="text-sm opacity-80 mb-2">Saldo Inicial</div>
            <div class="text-2xl font-bold">
                ${{ number_format($cuenta->saldo_inicial, 2) }}
            </div>
        </div>

        <!-- Saldo Actual -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="text-sm opacity-80 mb-2">Saldo Actual</div>
            <div class="text-2xl font-bold">
                ${{ number_format($cuenta->saldo_actual, 2) }}
            </div>
        </div>
    </div>

    <!-- Diferencia de Saldo -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Movimiento de Saldo</h3>
        @php
            $diferencia = $cuenta->saldo_actual - $cuenta->saldo_inicial;
        @endphp
        <div class="flex items-center space-x-4">
            @if($diferencia > 0)
                <div class="bg-green-100 p-4 rounded-full">
                    <i class="fas fa-arrow-up text-green-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Incremento</div>
                    <div class="text-2xl font-bold text-green-600">+${{ number_format($diferencia, 2) }}</div>
                </div>
            @elseif($diferencia < 0)
                <div class="bg-red-100 p-4 rounded-full">
                    <i class="fas fa-arrow-down text-red-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Disminución</div>
                    <div class="text-2xl font-bold text-red-600">${{ number_format($diferencia, 2) }}</div>
                </div>
            @else
                <div class="bg-gray-100 p-4 rounded-full">
                    <i class="fas fa-minus text-gray-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Sin cambios</div>
                    <div class="text-2xl font-bold text-gray-600">$0.00</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Transacciones Recientes -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-history mr-2 text-blue-600"></i>
                    Transacciones Recientes
                </h3>
                <a href="{{ route('transacciones.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>Nueva Transacción
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-lg mr-2">{{ $transaccion->categoria->icono ?? '📦' }}</span>
                                    <span class="text-sm text-gray-900">{{ $transaccion->categoria->nombre }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $transaccion->descripcion ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold {{ $transaccion->tipo == 'ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaccion->tipo == 'ingreso' ? '+' : '-' }}${{ number_format($transaccion->monto, 2) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                No hay transacciones en esta cuenta
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection