@extends('layouts.app')

@section('title', 'Metas de Ahorro - FinanPlan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-bullseye mr-2 text-blue-600"></i>
            Metas de Ahorro
        </h1>
        <a href="{{ route('metas.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-lg">
            <i class="fas fa-plus mr-2"></i>Nueva Meta
        </a>
    </div>

    <!-- Lista de Metas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($metas as $meta)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-200">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-flag text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $meta->nombre_meta }}</h3>
                                <p class="text-sm text-gray-500">
                                    Vence: {{ $meta->fecha_limite->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            {{ $meta->estado == 'en_progreso' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $meta->estado == 'completada' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $meta->estado == 'vencida' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ $meta->estado == 'en_progreso' ? 'En Progreso' : '' }}
                            {{ $meta->estado == 'completada' ? 'Completada' : '' }}
                            {{ $meta->estado == 'vencida' ? 'Vencida' : '' }}
                        </span>
                    </div>

                    <!-- Montos -->
                    <div class="mb-4">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-medium text-gray-700">Ahorrado</span>
                            <span class="font-semibold">
                                ${{ number_format($meta->monto_actual, 2) }} / ${{ number_format($meta->monto_objetivo, 2) }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-green-500 h-4 rounded-full transition-all duration-300" 
                                 style="width: {{ min($meta->porcentaje, 100) }}%">
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-xs font-semibold text-green-600">
                                {{ $meta->porcentaje }}% alcanzado
                            </span>
                            <span class="text-xs text-gray-600">
                                Faltan: ${{ number_format(max($meta->monto_objetivo - $meta->monto_actual, 0), 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Estado Visual -->
                    @if($meta->estado == 'completada')
                        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm text-green-800 text-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                ¡Meta Completada! 🎉
                            </p>
                        </div>
                    @elseif($meta->porcentaje >= 75)
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800 text-center">
                                <i class="fas fa-trophy mr-2"></i>
                                ¡Casi lo logras! Sigue así
                            </p>
                        </div>
                    @endif

                    <!-- Acciones -->
                    <div class="flex space-x-2">
                        <a href="{{ route('metas.show', $meta->id_meta) }}" 
                           class="flex-1 bg-blue-100 text-blue-700 px-4 py-2 rounded text-center text-sm font-semibold hover:bg-blue-200 transition">
                            <i class="fas fa-eye mr-1"></i>Ver
                        </a>
                        <a href="{{ route('metas.edit', $meta->id_meta) }}" 
                           class="flex-1 bg-yellow-100 text-yellow-700 px-4 py-2 rounded text-center text-sm font-semibold hover:bg-yellow-200 transition">
                            <i class="fas fa-edit mr-1"></i>Editar
                        </a>
                        <form action="{{ route('metas.destroy', $meta->id_meta) }}" 
                              method="POST" 
                              onsubmit="return confirm('¿Estás seguro de eliminar esta meta?')"
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
                <i class="fas fa-bullseye text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes metas de ahorro</h3>
                <p class="text-gray-500 mb-6">Crea tus primeras metas para comenzar a ahorrar con objetivos claros</p>
                <a href="{{ route('metas.create') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>Crear Primera Meta
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection