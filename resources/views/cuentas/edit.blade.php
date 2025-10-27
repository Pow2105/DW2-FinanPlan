@extends('layouts.app')

@section('title', 'Editar Cuenta - FinanPlan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-yellow-600 p-6">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-edit mr-2"></i>Editar Cuenta
            </h1>
        </div>

        <form action="{{ route('cuentas.update', $cuenta->id_cuenta) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Nombre de la Cuenta -->
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-signature mr-2"></i>Nombre de la Cuenta *
                </label>
                <input type="text" 
                       id="nombre" 
                       name="nombre" 
                       value="{{ old('nombre', $cuenta->nombre) }}"
                       required
                       placeholder="Ej: Cuenta Principal, Ahorros para Viaje"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
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
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    <option value="">Selecciona un tipo</option>
                    <option value="ahorros" {{ old('tipo', $cuenta->tipo) == 'ahorros' ? 'selected' : '' }}>Ahorros</option>
                    <option value="corriente" {{ old('tipo', $cuenta->tipo) == 'corriente' ? 'selected' : '' }}>Corriente</option>
                    <option value="tarjeta_credito" {{ old('tipo', $cuenta->tipo) == 'tarjeta_credito' ? 'selected' : '' }}>Tarjeta de Crédito</option>
                    <option value="efectivo" {{ old('tipo', $cuenta->tipo) == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                </select>
                @error('tipo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Información de Saldos (Solo lectura) -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-900 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Información de Saldos
                </h4>
                <div class="space-y-2 text-sm text-blue-800">
                    <p><strong>Saldo Inicial:</strong> ${{ number_format($cuenta->saldo_inicial, 2) }}</p>
                    <p><strong>Saldo Actual:</strong> ${{ number_format($cuenta->saldo_actual, 2) }}</p>
                    <p class="text-xs text-blue-600 mt-2">
                        <i class="fas fa-lock mr-1"></i>Los saldos no se pueden editar directamente. Se actualizan con las transacciones.
                    </p>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex space-x-4">
                <button type="submit" 
                        class="flex-1 bg-yellow-600 text-white py-3 rounded-lg font-semibold hover:bg-yellow-700 transition duration-200 shadow-lg">
                    <i class="fas fa-save mr-2"></i>Guardar Cambios
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