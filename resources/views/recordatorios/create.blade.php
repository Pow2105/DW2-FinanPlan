@extends('layouts.app')

@section('title', 'Nuevo Recordatorio - FinanPlan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-blue-600 p-6">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-plus-circle mr-2"></i>Nuevo Recordatorio
            </h1>
        </div>

        <form action="{{ route('recordatorios.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Descripción -->
            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-file-alt mr-2"></i>Descripción del Pago *
                </label>
                <input type="text" 
                       id="descripcion" 
                       name="descripcion" 
                       value="{{ old('descripcion') }}"
                       required
                       placeholder="Ej: Pago de electricidad, Cuota del préstamo"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('descripcion')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Monto -->
            <div>
                <label for="monto" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-dollar-sign mr-2"></i>Monto a Pagar *
                </label>
                <input type="number" 
                       id="monto" 
                       name="monto" 
                       value="{{ old('monto') }}"
                       step="0.01"
                       min="0"
                       required
                       placeholder="0.00"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('monto')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fecha de Vencimiento -->
            <div>
                <label for="fecha_vencimiento" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt mr-2"></i>Fecha de Vencimiento *
                </label>
                <input type="date" 
                       id="fecha_vencimiento" 
                       name="fecha_vencimiento" 
                       value="{{ old('fecha_vencimiento') }}"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('fecha_vencimiento')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Frecuencia -->
            <div>
                <label for="frecuencia" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-sync-alt mr-2"></i>Frecuencia *
                </label>
                <select id="frecuencia" 
                        name="frecuencia" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Selecciona la frecuencia</option>
                    <option value="unica" {{ old('frecuencia') == 'unica' ? 'selected' : '' }}>Única vez</option>
                    <option value="mensual" {{ old('frecuencia') == 'mensual' ? 'selected' : '' }}>Mensual</option>
                    <option value="anual" {{ old('frecuencia') == 'anual' ? 'selected' : '' }}>Anual</option>
                </select>
                @error('frecuencia')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">
                    Indica si este pago se repite periódicamente
                </p>
            </div>

            <!-- Ejemplos Comunes -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-900 mb-3">
                    <i class="fas fa-lightbulb mr-2"></i>Ejemplos de Recordatorios
                </h4>
                <div class="space-y-2 text-sm text-blue-800">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-blue-600 mr-2 mt-1"></i>
                        <div>
                            <strong>Servicios:</strong> Luz, agua, gas, internet, teléfono
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-blue-600 mr-2 mt-1"></i>
                        <div>
                            <strong>Préstamos:</strong> Cuotas de préstamos bancarios, tarjetas de crédito
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-blue-600 mr-2 mt-1"></i>
                        <div>
                            <strong>Suscripciones:</strong> Netflix, Spotify, gimnasio
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-blue-600 mr-2 mt-1"></i>
                        <div>
                            <strong>Seguros:</strong> Seguro de auto, vida, hogar
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consejos -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h4 class="font-semibold text-yellow-900 mb-2">
                    <i class="fas fa-star mr-2"></i>Consejos
                </h4>
                <ul class="text-sm text-yellow-800 space-y-1">
                    <li>• Configura recordatorios unos días antes del vencimiento</li>
                    <li>• Revisa tus recordatorios regularmente</li>
                    <li>• Marca como completados los pagos realizados</li>
                </ul>
            </div>

            <!-- Botones -->
            <div class="flex space-x-4">
                <button type="submit" 
                        class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-lg">
                    <i class="fas fa-save mr-2"></i>Crear Recordatorio
                </button>
                <a href="{{ route('recordatorios.index') }}" 
                   class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition duration-200 text-center">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection