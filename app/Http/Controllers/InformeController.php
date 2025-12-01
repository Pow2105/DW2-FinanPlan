<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InformeController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();
        
        // 1. Determinar el Modo (General vs Mensual)
        $esInformeMensual = false;
        $mesSeleccionado = '';
        $tituloInforme = 'Reporte Histórico General';
        
        // Query base (siempre filtra por usuario)
        $baseQuery = Transaccion::whereHas('cuenta', function($query) use ($usuario) {
            $query->where('id_usuario', $usuario->id_usuario);
        });

        // 2. Aplicar Filtro de Fechas (Solo si se seleccionó un mes)
        if ($request->filled('mes')) {
            try {
                $fechaBase = Carbon::createFromFormat('Y-m', $request->mes);
                $fechaInicio = $fechaBase->copy()->startOfMonth()->format('Y-m-d');
                $fechaFin = $fechaBase->copy()->endOfMonth()->format('Y-m-d');
                
                $esInformeMensual = true;
                $mesSeleccionado = $request->mes;
                $tituloInforme = 'Reporte Mensual: ' . ucfirst($fechaBase->translatedFormat('F Y'));
            } catch (\Exception $e) {
                // Si la fecha es inválida, ignoramos y mostramos general
            }
        }

        // --- CONSULTAS DE TOTALES ---
        
        // Ingresos
        $qIngresos = clone $baseQuery;
        if ($esInformeMensual) $qIngresos->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        $totalIngresos = $qIngresos->where('tipo', 'ingreso')->sum('monto');

        // Gastos
        $qGastos = clone $baseQuery;
        if ($esInformeMensual) $qGastos->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        $totalGastos = $qGastos->where('tipo', 'gasto')->sum('monto');

        // Balance (Del mes o Histórico según el caso)
        $balance = $totalIngresos - $totalGastos;

        // --- GRÁFICOS DE DONA (Categorías) ---
        
        $qCatGastos = clone $baseQuery;
        if ($esInformeMensual) $qCatGastos->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        $gastosPorCategoria = $qCatGastos->select('categorias.nombre', 'categorias.color', 'categorias.icono', DB::raw('SUM(transacciones.monto) as total'))
            ->join('categorias', 'transacciones.id_categoria', '=', 'categorias.id_categoria')
            ->where('transacciones.tipo', 'gasto')
            ->groupBy('categorias.id_categoria', 'categorias.nombre', 'categorias.color', 'categorias.icono')
            ->orderBy('total', 'desc')
            ->get();

        $qCatIngresos = clone $baseQuery;
        if ($esInformeMensual) $qCatIngresos->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        $ingresosPorCategoria = $qCatIngresos->select('categorias.nombre', 'categorias.color', 'categorias.icono', DB::raw('SUM(transacciones.monto) as total'))
            ->join('categorias', 'transacciones.id_categoria', '=', 'categorias.id_categoria')
            ->where('transacciones.tipo', 'ingreso')
            ->groupBy('categorias.id_categoria', 'categorias.nombre', 'categorias.color', 'categorias.icono')
            ->orderBy('total', 'desc')
            ->get();

        // --- GRÁFICO DE FLUJO (Línea de tiempo) ---
        // Lógica inteligente: Si es Mensual -> Agrupa por DÍA. Si es General -> Agrupa por MES.
        
        $qFlujo = clone $baseQuery;
        if ($esInformeMensual) {
            // Modo Mensual: Agrupar por día exacto
            $qFlujo->whereBetween('fecha', [$fechaInicio, $fechaFin]);
            $groupByFormat = '%Y-%m-%d'; // MySQL Date Format
            $selectFecha = 'fecha';
        } else {
            // Modo General: Agrupar por Año-Mes (Para no saturar la gráfica)
            $groupByFormat = '%Y-%m'; 
            $selectFecha = DB::raw("DATE_FORMAT(fecha, '%Y-%m') as fecha");
        }

        $transaccionesPorDia = $qFlujo->select(
                DB::raw("DATE_FORMAT(fecha, '$groupByFormat') as fecha_grupo"), // Para agrupar
                DB::raw("MAX(fecha) as fecha"), // Para ordenar/mostrar
                DB::raw('SUM(CASE WHEN tipo = "ingreso" THEN monto ELSE 0 END) as ingresos'),
                DB::raw('SUM(CASE WHEN tipo = "gasto" THEN monto ELSE 0 END) as gastos')
            )
            ->groupBy('fecha_grupo')
            ->orderBy('fecha_grupo')
            ->limit($esInformeMensual ? 31 : 12) // Si es general, limitamos a últimos 12 meses para limpieza
            ->get();

        $qDetalle = clone $baseQuery;
        if ($esInformeMensual) $qDetalle->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        $gastosDetallados = $qDetalle->where('tipo', 'gasto')
            ->with(['cuenta', 'categoria'])
            ->orderBy('fecha', 'desc')
            ->limit($esInformeMensual ? 500 : 50) // En general limitamos a 50 recientes
            ->get();
        
        return view('informes.index', compact(
            'esInformeMensual',
            'mesSeleccionado',
            'tituloInforme',
            'totalIngresos',
            'totalGastos',
            'balance',
            'gastosPorCategoria',
            'ingresosPorCategoria',
            'transaccionesPorDia',
            'gastosDetallados'
        ));
    }
}