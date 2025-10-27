<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InformeController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();
        
        // Filtros
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', now()->endOfMonth()->format('Y-m-d'));
        
        // Resumen general
        $totalIngresos = Transaccion::whereHas('cuenta', function($query) use ($usuario) {
                $query->where('id_usuario', $usuario->id_usuario);
            })
            ->where('tipo', 'ingreso')
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->sum('monto');
            
        $totalGastos = Transaccion::whereHas('cuenta', function($query) use ($usuario) {
                $query->where('id_usuario', $usuario->id_usuario);
            })
            ->where('tipo', 'gasto')
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->sum('monto');
            
        $balance = $totalIngresos - $totalGastos;
        
        // Gastos por categoría
        $gastosPorCategoria = Transaccion::select('categorias.nombre', 'categorias.color', 'categorias.icono', DB::raw('SUM(transacciones.monto) as total'))
            ->join('cuentas', 'transacciones.id_cuenta', '=', 'cuentas.id_cuenta')
            ->join('categorias', 'transacciones.id_categoria', '=', 'categorias.id_categoria')
            ->where('cuentas.id_usuario', $usuario->id_usuario)
            ->where('transacciones.tipo', 'gasto')
            ->whereBetween('transacciones.fecha', [$fechaInicio, $fechaFin])
            ->groupBy('categorias.id_categoria', 'categorias.nombre', 'categorias.color', 'categorias.icono')
            ->orderBy('total', 'desc')
            ->get();
            
        // Ingresos por categoría
        $ingresosPorCategoria = Transaccion::select('categorias.nombre', 'categorias.color', 'categorias.icono', DB::raw('SUM(transacciones.monto) as total'))
            ->join('cuentas', 'transacciones.id_cuenta', '=', 'cuentas.id_cuenta')
            ->join('categorias', 'transacciones.id_categoria', '=', 'categorias.id_categoria')
            ->where('cuentas.id_usuario', $usuario->id_usuario)
            ->where('transacciones.tipo', 'ingreso')
            ->whereBetween('transacciones.fecha', [$fechaInicio, $fechaFin])
            ->groupBy('categorias.id_categoria', 'categorias.nombre', 'categorias.color', 'categorias.icono')
            ->orderBy('total', 'desc')
            ->get();
        
        // Transacciones por día
        $transaccionesPorDia = Transaccion::select(
                'fecha',
                DB::raw('SUM(CASE WHEN tipo = "ingreso" THEN monto ELSE 0 END) as ingresos'),
                DB::raw('SUM(CASE WHEN tipo = "gasto" THEN monto ELSE 0 END) as gastos')
            )
            ->join('cuentas', 'transacciones.id_cuenta', '=', 'cuentas.id_cuenta')
            ->where('cuentas.id_usuario', $usuario->id_usuario)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();
        
        return view('informes.index', compact(
            'fechaInicio',
            'fechaFin',
            'totalIngresos',
            'totalGastos',
            'balance',
            'gastosPorCategoria',
            'ingresosPorCategoria',
            'transaccionesPorDia'
        ));
    }
}