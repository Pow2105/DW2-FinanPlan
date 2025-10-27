<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use App\Models\Transaccion;
use App\Models\Presupuesto;
use App\Models\MetaAhorro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        
        // Resumen de cuentas
        $cuentas = Cuenta::where('id_usuario', $usuario->id_usuario)->get();
        $totalCuentas = $cuentas->sum('saldo_actual');
        
        // Transacciones del mes actual
        $mesActual = now()->format('Y-m');
        
        $ingresosDelMes = Transaccion::whereHas('cuenta', function($query) use ($usuario) {
                $query->where('id_usuario', $usuario->id_usuario);
            })
            ->where('tipo', 'ingreso')
            ->whereRaw("DATE_FORMAT(fecha, '%Y-%m') = ?", [$mesActual])
            ->sum('monto');
            
        $gastosDelMes = Transaccion::whereHas('cuenta', function($query) use ($usuario) {
                $query->where('id_usuario', $usuario->id_usuario);
            })
            ->where('tipo', 'gasto')
            ->whereRaw("DATE_FORMAT(fecha, '%Y-%m') = ?", [$mesActual])
            ->sum('monto');
        
        // Gastos por categoría (mes actual)
        $gastosPorCategoria = Transaccion::select('categorias.nombre', 'categorias.color', DB::raw('SUM(transacciones.monto) as total'))
            ->join('cuentas', 'transacciones.id_cuenta', '=', 'cuentas.id_cuenta')
            ->join('categorias', 'transacciones.id_categoria', '=', 'categorias.id_categoria')
            ->where('cuentas.id_usuario', $usuario->id_usuario)
            ->where('transacciones.tipo', 'gasto')
            ->whereRaw("DATE_FORMAT(transacciones.fecha, '%Y-%m') = ?", [$mesActual])
            ->groupBy('categorias.id_categoria', 'categorias.nombre', 'categorias.color')
            ->get();
        
        // Presupuestos activos
        $presupuestos = Presupuesto::where('id_usuario', $usuario->id_usuario)
            ->where('estado', 'activo')
            ->with('categoria')
            ->get();
            
        foreach ($presupuestos as $presupuesto) {
            $presupuesto->gasto_actual = $presupuesto->gastoActual();
            $presupuesto->porcentaje = ($presupuesto->monto_limite > 0) 
                ? round(($presupuesto->gasto_actual / $presupuesto->monto_limite) * 100, 2) 
                : 0;
        }
        
        // Metas de ahorro
        $metas = MetaAhorro::where('id_usuario', $usuario->id_usuario)
            ->where('estado', 'en_progreso')
            ->get();
            
        foreach ($metas as $meta) {
            $meta->porcentaje = $meta->porcentajeProgreso();
        }
        
        // Últimas transacciones
        $ultimasTransacciones = Transaccion::whereHas('cuenta', function($query) use ($usuario) {
                $query->where('id_usuario', $usuario->id_usuario);
            })
            ->with(['cuenta', 'categoria'])
            ->orderBy('fecha', 'desc')
            ->limit(10)
            ->get();
        
        return view('dashboard', compact(
            'cuentas',
            'totalCuentas',
            'ingresosDelMes',
            'gastosDelMes',
            'gastosPorCategoria',
            'presupuestos',
            'metas',
            'ultimasTransacciones'
        ));
    }
}