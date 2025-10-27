@extends('layouts.app')

@section('title', 'Nueva Cuenta - FinanPlan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-blue-600 p-6">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-plus-circle mr-2"></i>Nueva Cuenta
            </h1>
        </div>

        <form action="{{ route('cuentas.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Nombre de la Cuenta -->
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-signature mr-2"></i>Nombre de la Cuenta *
                </label>
                <input type="text" 
                       id="nombre" 
                       name="nombre" 
                       value="{{ old('nombre') }}"
                       required
                       placeholder="Ej: Cuenta Principal, Ahorros para Viaje"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('nombre')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipo de Cuenta -->
            <div>
                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-list mr-2"></i>Tipo de Cuenta *
                </label>
                <select id="tipo" 
                        name="tipo" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Selecciona un tipo</option>
                    <option value="ahorros" {{ old('tipo') == 'ahorros' ? 'selected' : '' }}>Ahorros</option>
                    <option value="corriente" {{ old('tipo') == 'corriente' ? 'selected' : '' }}>Corriente</option>
                    <option value="tarjeta_credito" {{ old('tipo') == 'tarjeta_credito' ? 'selected' : '' }}>Tarjeta de Crédito</option>
                    <option value="efectivo" {{ old('tipo') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                </select>
                @error('tipo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Saldo Inicial -->
            <div>
                <label for="saldo_inicial" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-dollar-sign mr-2"></i>Saldo Inicial *
                </label>
                <input type="number" 
                       id="saldo_inicial" 
                       name="saldo_inicial" 
                       value="{{ old('saldo_inicial', '0') }}"
                       step="0.01"
                       min="0"
                       required
                       placeholder="0.00"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('saldo_inicial')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Ingresa el saldo actual de esta cuenta</p>
            </div>

            <!-- Botones -->
            <div class="flex space-x-4">
                <button type="submit" 
                        class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-lg">
                    <i class="fas fa-save mr-2"></i>Crear Cuenta
                </button>
                <a href="{{ route('cuentas.index') }}" 
                   class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition duration-200 text-center">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection