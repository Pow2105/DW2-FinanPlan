@extends('layouts.app')

@section('title', 'Presupuestos - FinanPlan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-calculator mr-2 text-blue-600"></i>
            Presupuestos
        </h1>
        <a href="{{ route('presupuestos.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-lg">
            <i class="fas fa-plus mr-2"></i>Nuevo Presupuesto
        </a>
    </div>

    <!-- Lista de Presupuestos -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($presupuestos as $presupuesto)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-200">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <span class="text-2xl">{{ $presupuesto->categoria->icono ?? '📊' }}</span>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $presupuesto->categoria->nombre }}</h3>
                                <p class="text-sm text-gray-500">
                                    {{ $presupuesto->fecha_inicio->format('d/m/Y') }} - {{ $presupuesto->fecha_fin->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            {{ $presupuesto->estado == 'activo' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $presupuesto->estado == 'completado' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $presupuesto->estado == 'excedido' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($presupuesto->estado) }}
                        </span>
                    </div>

                    <!-- Progreso -->
                    <div class="mb-4">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-medium text-gray-700">Gastado</span>
                            <span class="font-semibold">
                                ${{ number_format($presupuesto->gasto_actual, 2) }} / ${{ number_format($presupuesto->monto_limite, 2) }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="h-4 rounded-full transition-all duration-300
                                {{ $presupuesto->porcentaje < 80 ? 'bg-green-500' : '' }}
                                {{ $presupuesto->porcentaje >= 80 && $presupuesto->porcentaje < 100 ? 'bg-yellow-500' : '' }}
                                {{ $presupuesto->porcentaje >= 100 ? 'bg-red-500' : '' }}" 
                                style="width: {{ min($presupuesto->porcentaje, 100) }}%">
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-xs font-semibold 
                                {{ $presupuesto->porcentaje < 80 ? 'text-green-600' : '' }}
                                {{ $presupuesto->porcentaje >= 80 && $presupuesto->porcentaje < 100 ? 'text-yellow-600' : '' }}
                                {{ $presupuesto->porcentaje >= 100 ? 'text-red-600' : '' }}">
                                {{ $presupuesto->porcentaje }}% utilizado
                            </span>
                            <span class="text-xs text-gray-600">
                                Disponible: ${{ number_format(max($presupuesto->monto_limite - $presupuesto->gasto_actual, 0), 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Alertas -->
                    @if($presupuesto->porcentaje >= 100)
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-800">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                ¡Has excedido tu presupuesto!
                            </p>
                        </div>
                    @elseif($presupuesto->porcentaje >= 80)
                        <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                Te estás acercando al límite de tu presupuesto
                            </p>
                        </div>
                    @endif

                    <!-- Acciones -->
                    <div class="flex space-x-2">
                        <a href="{{ route('presupuestos.show', $presupuesto->id_presupuesto) }}" 
                           class="flex-1 bg-blue-100 text-blue-700 px-4 py-2 rounded text-center text-sm font-semibold hover:bg-blue-200 transition">
                            <i class="fas fa-eye mr-1"></i>Ver Detalle
                        </a>
                        <a href="{{ route('presupuestos.edit', $presupuesto->id_presupuesto) }}" 
                           class="flex-1 bg-yellow-100 text-yellow-700 px-4 py-2 rounded text-center text-sm font-semibold hover:bg-yellow-200 transition">
                            <i class="fas fa-edit mr-1"></i>Editar
                        </a>
                        <form action="{{ route('presupuestos.destroy', $presupuesto->id_presupuesto) }}" 
                              method="POST" 
                              onsubmit="return confirm('¿Estás seguro de eliminar este presupuesto?')"
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
                <i class="fas fa-calculator text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes presupuestos configurados</h3>
                <p class="text-gray-500 mb-6">Crea presupuestos para controlar tus gastos por categoría</p>
                <a href="{{ route('presupuestos.create') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>Crear Primer Presupuesto
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection