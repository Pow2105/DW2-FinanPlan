@extends('layouts.app')

@section('title', 'Nuevo Presupuesto - FinanPlan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-blue-600 p-6">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-plus-circle mr-2"></i>Nuevo Presupuesto
            </h1>
        </div>

        <form action="{{ route('presupuestos.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Categoría -->
            <div>
                <label for="id_categoria" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tags mr-2"></i>Categoría de Gasto *
                </label>
                <select id="id_categoria" 
                        name="id_categoria" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Selecciona una categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria') == $categoria->id_categoria ? 'selected' : '' }}>
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
                       value="{{ old('monto_limite') }}"
                       step="0.01"
                       min="0"
                       required
                       placeholder="0.00"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('monto_limite')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Cantidad máxima que deseas gastar en esta categoría</p>
            </div>

            <!-- Fecha de Inicio -->
            <div>
                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt mr-2"></i>Fecha de Inicio *
                </label>
                <input type="date" 
                       id="fecha_inicio" 
                       name="fecha_inicio" 
                       value="{{ old('fecha_inicio', date('Y-m-01')) }}"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                       value="{{ old('fecha_fin', date('Y-m-t')) }}"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('fecha_fin')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Consejos -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-900 mb-2">
                    <i class="fas fa-lightbulb mr-2"></i>Consejos para presupuestar
                </h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Revisa tus gastos anteriores en esta categoría</li>
                    <li>• Establece un límite realista pero desafiante</li>
                    <li>• Considera gastos recurrentes y ocasionales</li>
                    <li>• Deja un margen para imprevistos</li>
                </ul>
            </div>

            <!-- Botones -->
            <div class="flex space-x-4">
                <button type="submit" 
                        class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-lg">
                    <i class="fas fa-save mr-2"></i>Crear Presupuesto
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