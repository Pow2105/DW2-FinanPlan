@extends('layouts.app')

@section('title', 'Presupuestos - FinanPlan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-calculator mr-2 text-blue-600"></i>
            Mis Presupuestos
        </h1>
        <a href="{{ route('presupuestos.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-lg flex items-center">
            <i class="fas fa-plus mr-2"></i>Nuevo Presupuesto
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($presupuestos as $presupuesto)
            @php
                $gastoActual = $presupuesto->gastoActual();
                $porcentaje = $presupuesto->porcentaje();
                $estado = $presupuesto->estado_real; // Usamos el atributo calculado
            @endphp

            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-200 flex flex-col h-full border border-gray-100">
                <div class="p-6 flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-2xl">
                                {{ $presupuesto->categoria->icono ?? '📊' }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">{{ $presupuesto->categoria->nombre }}</h3>
                                <p class="text-xs text-gray-500 font-medium">
                                    {{ $presupuesto->fecha_inicio->format('d M') }} - {{ $presupuesto->fecha_fin->format('d M, Y') }}
                                </p>
                            </div>
                        </div>
                        
                        <span class="px-3 py-1 text-xs font-bold rounded-full uppercase tracking-wide
                            {{ $estado == 'activo' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $estado == 'alerta' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $estado == 'excedido' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ $estado }}
                        </span>
                    </div>

                    <div class="mb-2">
                        <div class="flex justify-between text-sm mb-1 font-medium">
                            <span class="text-gray-600">Progreso</span>
                            <span class="{{ $porcentaje >= 100 ? 'text-red-600' : 'text-blue-600' }}">
                                {{ $porcentaje }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 ease-out
                                {{ $porcentaje < 80 ? 'bg-green-500' : '' }}
                                {{ $porcentaje >= 80 && $porcentaje < 100 ? 'bg-yellow-500' : '' }}
                                {{ $porcentaje >= 100 ? 'bg-red-500' : '' }}" 
                                style="width: {{ min($porcentaje, 100) }}%">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-end mt-4 pt-4 border-t border-gray-100">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">Gastado</p>
                            <p class="text-lg font-bold {{ $gastoActual > $presupuesto->monto_limite ? 'text-red-600' : 'text-gray-900' }}">
                                ${{ number_format($gastoActual, 2) }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 uppercase font-semibold">Límite</p>
                            <p class="text-lg font-bold text-gray-900">
                                ${{ number_format($presupuesto->monto_limite, 2) }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-2 text-right">
                        @if($presupuesto->monto_limite - $gastoActual > 0)
                            <p class="text-xs text-green-600 font-medium">
                                Restante: ${{ number_format($presupuesto->monto_limite - $gastoActual, 2) }}
                            </p>
                        @else
                            <p class="text-xs text-red-600 font-medium">
                                Excedido por: ${{ number_format($gastoActual - $presupuesto->monto_limite, 2) }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 p-4 border-t border-gray-100 flex gap-2">
                    <a href="{{ route('presupuestos.show', $presupuesto->id_presupuesto) }}" 
                       class="flex-1 bg-white border border-gray-300 text-gray-700 py-2 rounded-lg text-center text-sm font-semibold hover:bg-gray-50 hover:text-blue-600 transition shadow-sm">
                        <i class="fas fa-eye mr-1"></i> Detalles
                    </a>
                    <a href="{{ route('presupuestos.edit', $presupuesto->id_presupuesto) }}" 
                       class="w-10 flex items-center justify-center bg-white border border-gray-300 text-yellow-600 rounded-lg hover:bg-yellow-50 transition shadow-sm" title="Editar">
                        <i class="fas fa-pen"></i>
                    </a>
                    <form action="{{ route('presupuestos.destroy', $presupuesto->id_presupuesto) }}" 
                          method="POST" 
                          onsubmit="return confirm('¿Eliminar este presupuesto?')"
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-10 h-full flex items-center justify-center bg-white border border-gray-300 text-red-600 rounded-lg hover:bg-red-50 transition shadow-sm" title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 flex flex-col items-center justify-center text-center">
                <div class="bg-blue-50 p-6 rounded-full mb-4">
                    <i class="fas fa-wallet text-blue-400 text-5xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No tienes presupuestos activos</h3>
                <p class="text-gray-500 max-w-md mb-6">
                    Los presupuestos te ayudan a no gastar de más. Crea uno para una categoría (ej: "Comida") y te avisaremos si te acercas al límite.
                </p>
                <a href="{{ route('presupuestos.create') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition shadow-lg">
                    Crear mi primer presupuesto
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection