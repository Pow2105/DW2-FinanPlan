@extends('layouts.app')

@section('title', 'Recordatorios - FinanPlan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-bell mr-2 text-blue-600"></i>
            Recordatorios de Pagos
        </h1>
        <a href="{{ route('recordatorios.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-lg">
            <i class="fas fa-plus mr-2"></i>Nuevo Recordatorio
        </a>
    </div>

    <!-- Lista de Recordatorios -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($recordatorios as $recordatorio)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-200">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-orange-100 p-3 rounded-full">
                                <i class="fas fa-bell text-orange-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $recordatorio->descripcion }}</h3>
                                <p class="text-sm text-gray-500 capitalize">
                                    {{ str_replace('_', ' ', $recordatorio->frecuencia) }}
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            {{ $recordatorio->estado == 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $recordatorio->estado == 'notificado' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $recordatorio->estado == 'completado' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst($recordatorio->estado) }}
                        </span>
                    </div>

                    <!-- Información -->
                    <div class="space-y-3 mb-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">
                                <i class="fas fa-dollar-sign mr-2"></i>Monto
                            </span>
                            <span class="text-lg font-bold text-gray-900">
                                ${{ number_format($recordatorio->monto, 2) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">
                                <i class="fas fa-calendar mr-2"></i>Vencimiento
                            </span>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $recordatorio->fecha_vencimiento->format('d/m/Y') }}
                            </span>
                        </div>
                        @php
                            $diasRestantes = now()->diffInDays($recordatorio->fecha_vencimiento, false);
                        @endphp
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">
                                <i class="fas fa-clock mr-2"></i>Tiempo
                            </span>
                            <span class="text-sm font-semibold 
                                {{ $diasRestantes < 0 ? 'text-red-600' : '' }}
                                {{ $diasRestantes >= 0 && $diasRestantes <= 7 ? 'text-orange-600' : '' }}
                                {{ $diasRestantes > 7 ? 'text-green-600' : '' }}">
                                @if($diasRestantes > 0)
                                    En {{ $diasRestantes }} días
                                @elseif($diasRestantes == 0)
                                    ¡Hoy vence!
                                @else
                                    Venció hace {{ abs($diasRestantes) }} días
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Alerta si está próximo a vencer -->
                    @if($diasRestantes >= 0 && $diasRestantes <= 3 && $recordatorio->estado != 'completado')
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-800 text-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                ¡Próximo a vencer!
                            </p>
                        </div>
                    @elseif($diasRestantes < 0 && $recordatorio->estado != 'completado')
                        <div class="mb-4 p-3 bg-red-100 border border-red-300 rounded-lg">
                            <p class="text-sm text-red-900 text-center font-semibold">
                                <i class="fas fa-times-circle mr-2"></i>
                                ¡Vencido!
                            </p>
                        </div>
                    @endif

                    <!-- Acciones -->
                    <div class="flex space-x-2">
                        <a href="{{ route('recordatorios.edit', $recordatorio->id_recordatorio) }}" 
                           class="flex-1 bg-yellow-100 text-yellow-700 px-4 py-2 rounded text-center text-sm font-semibold hover:bg-yellow-200 transition">
                            <i class="fas fa-edit mr-1"></i>Editar
                        </a>
                        <form action="{{ route('recordatorios.destroy', $recordatorio->id_recordatorio) }}" 
                              method="POST" 
                              onsubmit="return confirm('¿Estás seguro de eliminar este recordatorio?')"
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
                <i class="fas fa-bell-slash text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes recordatorios</h3>
                <p class="text-gray-500 mb-6">Crea recordatorios para no olvidar tus pagos importantes</p>
                <a href="{{ route('recordatorios.create') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>Crear Primer Recordatorio
                </a>
            </div>
        @endforelse
    </div>

    <!-- Resumen de Recordatorios -->
    @if($recordatorios->count() > 0)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-chart-pie mr-2 text-blue-600"></i>
                Resumen de Recordatorios
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                    <div class="text-sm text-yellow-600 mb-1">Pendientes</div>
                    <div class="text-2xl font-bold text-yellow-900">
                        {{ $recordatorios->where('estado', 'pendiente')->count() }}
                    </div>
                </div>
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="text-sm text-blue-600 mb-1">Notificados</div>
                    <div class="text-2xl font-bold text-blue-900">
                        {{ $recordatorios->where('estado', 'notificado')->count() }}
                    </div>
                </div>
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <div class="text-sm text-green-600 mb-1">Completados</div>
                    <div class="text-2xl font-bold text-green-900">
                        {{ $recordatorios->where('estado', 'completado')->count() }}
                    </div>
                </div>
                <div class="bg-orange-50 rounded-lg p-4 border border-orange-200">
                    <div class="text-sm text-orange-600 mb-1">Total a Pagar</div>
                    <div class="text-2xl font-bold text-orange-900">
                        ${{ number_format($recordatorios->where('estado', '!=', 'completado')->sum('monto'), 2) }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection