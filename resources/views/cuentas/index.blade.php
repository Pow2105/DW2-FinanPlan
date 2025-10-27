@extends('layouts.app')

@section('title', 'Mis Cuentas - FinanPlan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-wallet mr-2 text-blue-600"></i>
            Mis Cuentas
        </h1>
        <a href="{{ route('cuentas.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-lg">
            <i class="fas fa-plus mr-2"></i>Nueva Cuenta
        </a>
    </div>

    <!-- Resumen Total -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <h2 class="text-lg font-semibold mb-2">Balance Total</h2>
        <p class="text-4xl font-bold">${{ number_format($cuentas->sum('saldo_actual'), 2) }}</p>
    </div>

    <!-- Lista de Cuentas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($cuentas as $cuenta)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-200">
                <div class="p-6">
                    <!-- Header de la Cuenta -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            @switch($cuenta->tipo)
                                @case('ahorros')
                                    <div class="bg-green-100 p-3 rounded-full">
                                        <i class="fas fa-piggy-bank text-green-600 text-xl"></i>
                                    </div>
                                    @break
                                @case('corriente')
                                    <div class="bg-blue-100 p-3 rounded-full">
                                        <i class="fas fa-university text-blue-600 text-xl"></i>
                                    </div>
                                    @break
                                @case('tarjeta_credito')
                                    <div class="bg-purple-100 p-3 rounded-full">
                                        <i class="fas fa-credit-card text-purple-600 text-xl"></i>
                                    </div>
                                    @break
                                @case('efectivo')
                                    <div class="bg-yellow-100 p-3 rounded-full">
                                        <i class="fas fa-money-bill-wave text-yellow-600 text-xl"></i>
                                    </div>
                                    @break
                            @endswitch
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $cuenta->nombre }}</h3>
                                <p class="text-sm text-gray-500 capitalize">{{ str_replace('_', ' ', $cuenta->tipo) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Saldo -->
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-1">Saldo Actual</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($cuenta->saldo_actual, 2) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Inicial: ${{ number_format($cuenta->saldo_inicial, 2) }}</p>
                    </div>

                    <!-- Acciones -->
                    <div class="flex space-x-2">
                        <a href="{{ route('cuentas.show', $cuenta->id_cuenta) }}" 
                           class="flex-1 bg-blue-100 text-blue-700 px-4 py-2 rounded text-center text-sm font-semibold hover:bg-blue-200 transition">
                            <i class="fas fa-eye mr-1"></i>Ver
                        </a>
                        <a href="{{ route('cuentas.edit', $cuenta->id_cuenta) }}" 
                           class="flex-1 bg-yellow-100 text-yellow-700 px-4 py-2 rounded text-center text-sm font-semibold hover:bg-yellow-200 transition">
                            <i class="fas fa-edit mr-1"></i>Editar
                        </a>
                        <form action="{{ route('cuentas.destroy', $cuenta->id_cuenta) }}" 
                              method="POST" 
                              onsubmit="return confirm('¿Estás seguro de eliminar esta cuenta?')"
                              class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-100 text-red-700 px-4 py-2 rounded text-sm font-semibold hover:bg-red-200 transition">
                                <i class="fas fa-trash mr-1"></i>Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-lg shadow-lg p-12 text-center">
                <i class="fas fa-wallet text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes cuentas registradas</h3>
                <p class="text-gray-500 mb-6">Crea tu primera cuenta para comenzar a gestionar tus finanzas</p>
                <a href="{{ route('cuentas.create') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>Crear Primera Cuenta
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection