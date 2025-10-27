@extends('layouts.app')

@section('title', 'Editar Recordatorio - FinanPlan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-yellow-600 p-6">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-edit mr-2"></i>Editar Recordatorio
            </h1>
        </div>

        <form action="{{ route('recordatorios.update', $recordatorio->id_recordatorio) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Descripción -->
            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-file-alt mr-2"></i>Descripción del Pago *
                </label>
                <input type="text" 
                       id="descripcion" 
                       name="descripcion" 
                       value="{{ old('descripcion', $recordatorio->descripcion) }}"
                       required
                       placeholder="Ej: Pago de electricidad, Cuota del préstamo"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
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
                       value="{{ old('monto', $recordatorio->monto) }}"
                       step="0.01"
                       min="0"
                       required
                       placeholder="0.00"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
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
                       value="{{ old('fecha_vencimiento', $recordatorio->fecha_vencimiento->format('Y-m-d')) }}"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
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
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    <option value="">Selecciona la frecuencia</option>
                    <option value="unica" {{ old('frecuencia', $recordatorio->frecuencia) == 'unica' ? 'selected' : '' }}>Única vez</option>
                    <option value="mensual" {{ old('frecuencia', $recordatorio->frecuencia) == 'mensual' ? 'selected' : '' }}>Mensual</option>
                    <option value="anual" {{ old('frecuencia', $recordatorio->frecuencia) == 'anual' ? 'selected' : '' }}>Anual</option>
                </select>
                @error('frecuencia')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Estado -->
            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-flag mr-2"></i>Estado *
                </label>
                <select id="estado" 
                        name="estado" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    <option value="pendiente" {{ old('estado', $recordatorio->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="notificado" {{ old('estado', $recordatorio->estado) == 'notificado' ? 'selected' : '' }}>Notificado</option>
                    <option value="completado" {{ old('estado', $recordatorio->estado) == 'completado' ? 'selected' : '' }}>Completado</option>
                </select>
                @error('estado')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">
                    Marca como "Completado" cuando hayas realizado el pago
                </p>
            </div>

            <!-- Botones -->
            <div class="flex space-x-4">
                <button type="submit" 
                        class="flex-1 bg-yellow-600 text-white py-3 rounded-lg font-semibold hover:bg-yellow-700 transition duration-200 shadow-lg">
                    <i class="fas fa-save mr-2"></i>Guardar Cambios
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