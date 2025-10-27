<?php

namespace App\Http\Controllers;

use App\Models\Presupuesto;
use App\Models\Categoria;
use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresupuestoController extends Controller
{
    public function index()
    {
        $presupuestos = Presupuesto::where('id_usuario', Auth::user()->id_usuario)
            ->with('categoria')
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        // Calcular progreso de cada presupuesto
        foreach ($presupuestos as $presupuesto) {
            $presupuesto->gasto_actual = $presupuesto->gastoActual();
            $presupuesto->porcentaje = ($presupuesto->monto_limite > 0) 
                ? round(($presupuesto->gasto_actual / $presupuesto->monto_limite) * 100, 2) 
                : 0;
        }

        return view('presupuestos.index', compact('presupuestos'));
    }

    public function create()
    {
        $categorias = Categoria::where('tipo', 'gasto')->get();
        return view('presupuestos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'monto_limite' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        Presupuesto::create([
            'id_usuario' => Auth::user()->id_usuario,
            'id_categoria' => $request->id_categoria,
            'monto_limite' => $request->monto_limite,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'estado' => 'activo',
        ]);

        return redirect()->route('presupuestos.index')
            ->with('success', 'Presupuesto creado exitosamente.');
    }

    public function show(Presupuesto $presupuesto)
    {
        $presupuesto->load('categoria');
        $presupuesto->gasto_actual = $presupuesto->gastoActual();
        
        // Obtener transacciones relacionadas
        $transacciones = Transaccion::whereHas('cuenta', function($query) {
                $query->where('id_usuario', Auth::user()->id_usuario);
            })
            ->where('id_categoria', $presupuesto->id_categoria)
            ->where('tipo', 'gasto')
            ->whereBetween('fecha', [$presupuesto->fecha_inicio, $presupuesto->fecha_fin])
            ->orderBy('fecha', 'desc')
            ->get();

        return view('presupuestos.show', compact('presupuesto', 'transacciones'));
    }

    public function edit(Presupuesto $presupuesto)
    {
        $categorias = Categoria::where('tipo', 'gasto')->get();
        return view('presupuestos.edit', compact('presupuesto', 'categorias'));
    }

    public function update(Request $request, Presupuesto $presupuesto)
    {
        $request->validate([
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'monto_limite' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        $presupuesto->update([
            'id_categoria' => $request->id_categoria,
            'monto_limite' => $request->monto_limite,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        return redirect()->route('presupuestos.index')
            ->with('success', 'Presupuesto actualizado exitosamente.');
    }

    public function destroy(Presupuesto $presupuesto)
    {
        $presupuesto->delete();

        return redirect()->route('presupuestos.index')
            ->with('success', 'Presupuesto eliminado exitosamente.');
    }
}