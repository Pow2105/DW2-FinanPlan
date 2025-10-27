<?php

namespace App\Http\Controllers;

use App\Models\MetaAhorro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MetaAhorroController extends Controller
{
    public function index()
    {
        $metas = MetaAhorro::where('id_usuario', Auth::user()->id_usuario)
            ->orderBy('fecha_limite', 'asc')
            ->get();

        foreach ($metas as $meta) {
            $meta->porcentaje = $meta->porcentajeProgreso();
        }

        return view('metas.index', compact('metas'));
    }

    public function create()
    {
        return view('metas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_meta' => 'required|string|max:100',
            'monto_objetivo' => 'required|numeric|min:0',
            'fecha_limite' => 'required|date|after:today',
        ]);

        MetaAhorro::create([
            'id_usuario' => Auth::user()->id_usuario,
            'nombre_meta' => $request->nombre_meta,
            'monto_objetivo' => $request->monto_objetivo,
            'monto_actual' => 0,
            'fecha_limite' => $request->fecha_limite,
            'estado' => 'en_progreso',
        ]);

        return redirect()->route('metas.index')
            ->with('success', 'Meta de ahorro creada exitosamente.');
    }

    public function show(MetaAhorro $meta)
    {
        $meta->porcentaje = $meta->porcentajeProgreso();
        return view('metas.show', compact('meta'));
    }

    public function edit(MetaAhorro $meta)
    {
        return view('metas.edit', compact('meta'));
    }

    public function update(Request $request, MetaAhorro $meta)
    {
        $request->validate([
            'nombre_meta' => 'required|string|max:100',
            'monto_objetivo' => 'required|numeric|min:0',
            'fecha_limite' => 'required|date',
        ]);

        $meta->update([
            'nombre_meta' => $request->nombre_meta,
            'monto_objetivo' => $request->monto_objetivo,
            'fecha_limite' => $request->fecha_limite,
        ]);

        return redirect()->route('metas.index')
            ->with('success', 'Meta actualizada exitosamente.');
    }

    public function addFunds(Request $request, MetaAhorro $meta)
    {
        $request->validate([
            'monto' => 'required|numeric|min:0.01',
        ]);

        $meta->monto_actual += $request->monto;
        
        // Verificar si completó la meta
        if ($meta->monto_actual >= $meta->monto_objetivo) {
            $meta->estado = 'completada';
        }

        $meta->save();

        return redirect()->route('metas.show', $meta->id_meta)
            ->with('success', 'Ahorro añadido exitosamente.');
    }

    public function destroy(MetaAhorro $meta)
    {
        $meta->delete();

        return redirect()->route('metas.index')
            ->with('success', 'Meta eliminada exitosamente.');
    }
}