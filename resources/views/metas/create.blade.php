@extends('layouts.app')

@section('title', 'Nueva Meta de Ahorro - FinanPlan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-blue-600 p-6">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-plus-circle mr-2"></i>Nueva Meta de Ahorro
            </h1>
        </div>

        <form action="{{ route('metas.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Nombre de la Meta -->
            <div>
                <label for="nombre_meta" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-flag mr-2"></i>Nombre de la Meta *
                </label>
                <input type="text" 
                       id="nombre_meta" 
                       name="nombre_meta" 
                       value="{{ old('nombre_meta') }}"
                       required
                       placeholder="Ej: Viaje a Europa, Fondo de Emergencia, Nuevo Auto"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                       value="{{ old('monto_objetivo') }}"
                       step="0.01"
                       min="0"
                       required
                       placeholder="0.00"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('monto_objetivo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">¿Cuánto dinero necesitas ahorrar para esta meta?</p>
            </div>

            <!-- Fecha Límite -->
            <div>
                <label for="fecha_limite" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-check mr-2"></i>Fecha Límite *
                </label>
                <input type="date" 
                       id="fecha_limite" 
                       name="fecha_limite" 
                       value="{{ old('fecha_limite') }}"
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('fecha_limite')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">¿Cuándo quieres alcanzar esta meta?</p>
            </div>

            <!-- Calculadora de Ahorro -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-900 mb-3">
                    <i class="fas fa-calculator mr-2"></i>Calculadora de Ahorro
                </h4>
                <div class="space-y-2 text-sm text-blue-800">
                    <div id="calc-result" class="hidden">
                        <p class="font-semibold">Para alcanzar tu meta necesitas ahorrar:</p>
                        <div class="mt-2 space-y-1">
                            <p><strong>Por día:</strong> <span id="ahorro-diario">$0.00</span></p>
                            <p><strong>Por semana:</strong> <span id="ahorro-semanal">$0.00</span></p>
                            <p><strong>Por mes:</strong> <span id="ahorro-mensual">$0.00</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consejos -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h4 class="font-semibold text-green-900 mb-2">
                    <i class="fas fa-lightbulb mr-2"></i>Consejos para alcanzar tus metas
                </h4>
                <ul class="text-sm text-green-800 space-y-1">
                    <li>• Establece metas realistas y alcanzables</li>
                    <li>• Automatiza tus ahorros cuando recibas ingresos</li>
                    <li>• Revisa tu progreso regularmente</li>
                    <li>• Reduce gastos innecesarios para ahorrar más</li>
                    <li>• Celebra los pequeños logros en el camino</li>
                </ul>
            </div>

            <!-- Botones -->
            <div class="flex space-x-4">
                <button type="submit" 
                        class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-lg">
                    <i class="fas fa-save mr-2"></i>Crear Meta
                </button>
                <a href="{{ route('metas.index') }}" 
                   class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition duration-200 text-center">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Calculadora de ahorro
    const montoInput = document.getElementById('monto_objetivo');
    const fechaInput = document.getElementById('fecha_limite');
    const calcResult = document.getElementById('calc-result');

    function calcularAhorro() {
        const monto = parseFloat(montoInput.value) || 0;
        const fecha = new Date(fechaInput.value);
        const hoy = new Date();
        
        if (monto > 0 && fecha > hoy) {
            const dias = Math.ceil((fecha - hoy) / (1000 * 60 * 60 * 24));
            const semanas = dias / 7;
            const meses = dias / 30;
            
            document.getElementById('ahorro-diario').textContent = '$' + (monto / dias).toFixed(2);
            document.getElementById('ahorro-semanal').textContent = '$' + (monto / semanas).toFixed(2);
            document.getElementById('ahorro-mensual').textContent = '$' + (monto / meses).toFixed(2);
            
            calcResult.classList.remove('hidden');
        } else {
            calcResult.classList.add('hidden');
        }
    }

    montoInput.addEventListener('input', calcularAhorro);
    fechaInput.addEventListener('change', calcularAhorro);
</script>
@endpush
@endsection