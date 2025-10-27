@extends('layouts.app')

@section('title', 'Editar Meta - FinanPlan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-yellow-600 p-6">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-edit mr-2"></i>Editar Meta de Ahorro
            </h1>
        </div>

        <form action="{{ route('metas.update', $meta->id_meta) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Nombre de la Meta -->
            <div>
                <label for="nombre_meta" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-flag mr-2"></i>Nombre de la Meta *
                </label>
                <input type="text" 
                       id="nombre_meta" 
                       name="nombre_meta" 
                       value="{{ old('nombre_meta', $meta->nombre_meta) }}"
                       required
                       placeholder="Ej: Viaje a Europa, Fondo de Emergencia"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                @error('nombre_meta')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Monto Objetivo -->
            <div>
                <label for="monto_objetivo" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-dollar-sign mr-2"></i>Monto Objetivo *
                </label>
                <input type="number" 
                       id="monto_objetivo" 
                       name="monto_objetivo" 
                       value="{{ old('monto_objetivo', $meta->monto_objetivo) }}"
                       step="0.01"
                       min="0"
                       required
                       placeholder="0.00"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                @error('monto_objetivo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fecha Límite -->
            <div>
                <label for="fecha_limite" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-check mr-2"></i>Fecha Límite *
                </label>
                <input type="date" 
                       id="fecha_limite" 
                       name="fecha_limite" 
                       value="{{ old('fecha_limite', $meta->fecha_limite->format('Y-m-d')) }}"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                @error('fecha_limite')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Información del Progreso -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-900 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Progreso Actual
                </h4>
                <div class="space-y-2 text-sm text-blue-800">
                    <p><strong>Monto Ahorrado:</strong> ${{ number_format($meta->monto_actual, 2) }}</p>
                    <p><strong>Progreso:</strong> {{ round(($meta->monto_actual / $meta->monto_objetivo) * 100, 2) }}%</p>
                    <p class="text-xs text-blue-600 mt-2">
                        <i class="fas fa-lock mr-1"></i>El monto ahorrado se actualiza desde la vista de detalle
                    </p>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex space-x-4">
                <button type="submit" 
                        class="flex-1 bg-yellow-600 text-white py-3 rounded-lg font-semibold hover:bg-yellow-700 transition duration-200 shadow-lg">
                    <i class="fas fa-save mr-2"></i>Guardar Cambios
                </button>
                <a href="{{ route('metas.index') }}" 
                   class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition duration-200 text-center">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection