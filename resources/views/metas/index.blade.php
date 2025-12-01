@extends('layouts.app')

@section('title', 'Metas de Ahorro - FinanPlan')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-bullseye mr-2 text-blue-600"></i>
            Metas de Ahorro
        </h1>
        <a href="{{ route('metas.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-lg">
            <i class="fas fa-plus mr-2"></i>Nueva Meta
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($metas as $meta)
            @php
                $estadoReal = $meta->estado_real;
                $porcentaje = $meta->porcentajeProgreso();
            @endphp

            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-200 flex flex-col">
                <div class="p-6 flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-100 p-3 rounded-full shrink-0">
                                <i class="fas fa-flag text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 leading-tight">{{ $meta->nombre_meta }}</h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    Vence: {{ $meta->fecha_limite->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs font-bold rounded-full uppercase tracking-wide
                            {{ $estadoReal == 'en_progreso' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $estadoReal == 'completada' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $estadoReal == 'vencida' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ str_replace('_', ' ', $estadoReal) }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-medium text-gray-700">Progreso</span>
                            <span class="font-bold text-gray-900">
                                ${{ number_format($meta->monto_actual, 2) }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-3 rounded-full transition-all duration-500
                                {{ $estadoReal == 'vencida' ? 'bg-red-500' : 'bg-green-500' }}" 
                                 style="width: {{ min($porcentaje, 100) }}%">
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-xs font-bold {{ $estadoReal == 'vencida' ? 'text-red-600' : 'text-green-600' }}">
                                {{ $porcentaje }}%
                            </span>
                            <span class="text-xs text-gray-500">
                                Meta: ${{ number_format($meta->monto_objetivo, 2) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-4">
                        <a href="{{ route('metas.show', $meta->id_meta) }}" 
                           class="flex-1 bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-center text-sm font-semibold hover:bg-blue-100 transition border border-blue-200">
                            Ver Detalle
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-lg shadow-lg p-12 text-center">
                <i class="fas fa-bullseye text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes metas de ahorro</h3>
                <p class="text-gray-500 mb-6">Define objetivos y ahorra con propósito.</p>
                <a href="{{ route('metas.create') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    Crear Meta
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection