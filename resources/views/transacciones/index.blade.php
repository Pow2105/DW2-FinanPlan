@extends('layouts.app')

@section('title', 'Transacciones - FinanPlan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-exchange-alt mr-2 text-blue-600"></i>
            Transacciones
        </h1>
        <a href="{{ route('transacciones.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-lg">
            <i class="fas fa-plus mr-2"></i>Nueva Transacción
        </a>
    </div>

    <!-- Lista de Transacciones -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cuenta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transacciones as $transaccion)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $transaccion->fecha->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-lg mr-2">{{ $transaccion->categoria->icono ?? '📦' }}</span>
                                    <span class="text-sm text-gray-900">{{ $transaccion->categoria->nombre }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $transaccion->cuenta->nombre }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $transaccion->descripcion ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold {{ $transaccion->tipo == 'ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaccion->tipo == 'ingreso' ? '+' : '-' }}${{ number_format($transaccion->monto, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('transacciones.edit', $transaccion->id_transaccion) }}" 
                                       class="text-yellow-600 hover:text-yellow-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('transacciones.destroy', $transaccion->id_transaccion) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta transacción?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i class="fas fa-exchange-alt text-gray-400 text-6xl mb-4"></i>
                                <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay transacciones registradas</h3>
                                <p class="text-gray-500 mb-6">Comienza a registrar tus ingresos y gastos</p>
                                <a href="{{ route('transacciones.create') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                                    <i class="fas fa-plus mr-2"></i>Crear Primera Transacción
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($transacciones->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $transacciones->links() }}
            </div>
        @endif
    </div>
</div>
@endsection