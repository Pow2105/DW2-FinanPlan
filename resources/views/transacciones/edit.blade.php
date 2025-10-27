@extends('layouts.app')

@section('title', 'Editar Transacción - FinanPlan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-yellow-600 p-6">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-edit mr-2"></i>Editar Transacción
            </h1>
        </div>

        <form action="{{ route('transacciones.update', $transaccion->id_transaccion) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Tipo de Transacción -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-arrows-alt-h mr-2"></i>Tipo de Transacción *
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative">
                        <input type="radio" 
                               name="tipo" 
                               value="ingreso" 
                               {{ old('tipo', $transaccion->tipo) == 'ingreso' ? 'checked' : '' }}
                               required
                               class="peer sr-only">
                        <div class="p-4 border-2 border-gray-300 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 transition">
                            <div class="text-center">
                                <i class="fas fa-arrow-up text-3xl text-green-600 mb-2"></i>
                                <p class="font-semibold text-gray-900">Ingreso</p>
                            </div>
                        </div>
                    </label>
                    <label class="relative">
                        <input type="radio" 
                               name="tipo" 
                               value="gasto" 
                               {{ old('tipo', $transaccion->tipo) == 'gasto' ? 'checked' : '' }}
                               required
                               class="peer sr-only">
                        <div class="p-4 border-2 border-gray-300 rounded-lg cursor-pointer peer-checked:border-red-500 peer-checked:bg-red-50 transition">
                            <div class="text-center">
                                <i class="fas fa-arrow-down text-3xl text-red-600 mb-2"></i>
                                <p class="font-semibold text-gray-900">Gasto</p>
                            </div>
                        </div>
                    </label>
                </div>
                @error('tipo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cuenta -->
            <div>
                <label for="id_cuenta" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-wallet mr-2"></i>Cuenta *
                </label>
                <select id="id_cuenta" 
                        name="id_cuenta" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    <option value="">Selecciona una cuenta</option>
                    @foreach($cuentas as $cuenta)
                        <option value="{{ $cuenta->id_cuenta }}" {{ old('id_cuenta', $transaccion->id_cuenta) == $cuenta->id_cuenta ? 'selected' : '' }}>
                            {{ $cuenta->nombre }} (${{ number_format($cuenta->saldo_actual, 2) }})
                        </option>
                    @endforeach
                </select>
                @error('id_cuenta')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Categoría -->
            <div>
                <label for="id_categoria" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tags mr-2"></i>Categoría *
                </label>
                <select id="id_categoria" 
                        name="id_categoria" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    <option value="">Selecciona una categoría</option>
                    <optgroup label="Ingresos">
                        @foreach($categorias->where('tipo', 'ingreso') as $categoria)
                            <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria', $transaccion->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}>
                                {{ $categoria->icono }} {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Gastos">
                        @foreach($categorias->where('tipo', 'gasto') as $categoria)
                            <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria', $transaccion->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}>
                                {{ $categoria->icono }} {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </optgroup>
                </select>
                @error('id_categoria')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Monto -->
            <div>
                <label for="monto" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-dollar-sign mr-2"></i>Monto *
                </label>
                <input type="number" 
                       id="monto" 
                       name="monto" 
                       value="{{ old('monto', $transaccion->monto) }}"
                       step="0.01"
                       min="0.01"
                       required
                       placeholder="0.00"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                @error('monto')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fecha -->
            <div>
                <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar mr-2"></i>Fecha *
                </label>
                <input type="date" 
                       id="fecha" 
                       name="fecha" 
                       value="{{ old('fecha', $transaccion->fecha->format('Y-m-d')) }}"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                @error('fecha')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descripción -->
            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-comment mr-2"></i>Descripción (Opcional)
                </label>
                <textarea id="descripcion" 
                          name="descripcion" 
                          rows="3"
                          placeholder="Agrega una nota sobre esta transacción"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">{{ old('descripcion', $transaccion->descripcion) }}</textarea>
                @error('descripcion')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex space-x-4">
                <button type="submit" 
                        class="flex-1 bg-yellow-600 text-white py-3 rounded-lg font-semibold hover:bg-yellow-700 transition duration-200 shadow-lg">
                    <i class="fas fa-save mr-2"></i>Guardar Cambios
                </button>
                <a href="{{ route('transacciones.index') }}" 
                   class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition duration-200 text-center">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection