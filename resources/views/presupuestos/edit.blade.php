@extends('layouts.app')

@section('title', 'Editar Presupuesto - FinanPlan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-yellow-600 p-6">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-edit mr-2"></i>Editar Presupuesto
            </h1>
        </div>

        <form action="{{ route('presupuestos.update', $presupuesto->id_presupuesto) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Categoría -->
            <div>
                <label for="id_categoria" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tags mr-2"></i>Categoría de Gasto *
                </label>
                <select id="id_categoria" 
                        name="id_categoria" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    <option value="">Selecciona una categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria', $presupuesto->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}>
                            {{ $categoria->icono }} {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('id_categoria')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Monto Límite -->
            <div>
                <label for="monto_limite" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-dollar-sign mr-2"></i>Monto Límite *
                </label>
                <input type="number" 
                       id="monto_limite" 
                       name="monto_limite" 
                       value="{{ old('monto_limite', $presupuesto->monto_limite) }}"
                       step="0.01"
                       min="0"
                       required
                       placeholder="0.00"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                @error('monto_limite')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fecha de Inicio -->
            <div>
                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt mr-2"></i>Fecha de Inicio *
                </label>
                <input type="date" 
                       id="fecha_inicio" 
                       name="fecha_inicio" 
                       value="{{ old('fecha_inicio', $presupuesto->fecha_inicio->format('Y-m-d')) }}"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                @error('fecha_inicio')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fecha de Fin -->
            <div>
                <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-check mr-2"></i>Fecha de Fin *
                </label>
                <input type="date" 
                       id="fecha_fin" 
                       name="fecha_fin" 
                       value="{{ old('fecha_fin', $presupuesto->fecha_fin->format('Y-m-d')) }}"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                @error('fecha_fin')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex space-x-4">
                <button type="submit" 
                        class="flex-1 bg-yellow-600 text-white py-3 rounded-lg font-semibold hover:bg-yellow-700 transition duration-200 shadow-lg">
                    <i class="fas fa-save mr-2"></i>Guardar Cambios
                </button>
                <a href="{{ route('presupuestos.index') }}" 
                   class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition duration-200 text-center">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection