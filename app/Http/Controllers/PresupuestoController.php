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

        // No necesitamos hacer cálculos aquí, la vista llamará a los métodos del modelo
        return view('presupuestos.index', compact('presupuestos'));
    }

    public function create()
    {
        // Solo permitimos crear presupuestos para categorías de GASTO
        $categorias = Categoria::where('tipo', 'gasto')->get();
        return view('presupuestos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'monto_limite' => 'required|numeric|min:0.01',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
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
        // Verificar que el presupuesto pertenece al usuario
        if ($presupuesto->id_usuario !== Auth::id()) {
            abort(403);
        }

        $presupuesto->load('categoria');
        
        // Obtener transacciones que afectan a este presupuesto
        $transacciones = Transaccion::whereHas('cuenta', function($query) {
                $query->where('id_usuario', Auth::user()->id_usuario);
            })
            ->where('id_categoria', $presupuesto->id_categoria)
            ->where('tipo', 'gasto')
            ->whereBetween('fecha', [$presupuesto->fecha_inicio, $presupuesto->fecha_fin])
            ->with('cuenta') // Traer cuenta para mostrar nombre
            ->orderBy('fecha', 'desc')
            ->get();

        return view('presupuestos.show', compact('presupuesto', 'transacciones'));
    }

    public function edit(Presupuesto $presupuesto)
    {
        if ($presupuesto->id_usuario !== Auth::id()) abort(403);
        
        $categorias = Categoria::where('tipo', 'gasto')->get();
        return view('presupuestos.edit', compact('presupuesto', 'categorias'));
    }

    public function update(Request $request, Presupuesto $presupuesto)
    {
        if ($presupuesto->id_usuario !== Auth::id()) abort(403);

        $request->validate([
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'monto_limite' => 'required|numeric|min:0.01',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
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
        if ($presupuesto->id_usuario !== Auth::id()) abort(403);
        
        $presupuesto->delete();

        return redirect()->route('presupuestos.index')
            ->with('success', 'Presupuesto eliminado exitosamente.');
    }
}