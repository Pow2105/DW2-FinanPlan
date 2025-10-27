<?php

namespace App\Http\Controllers;

use App\Models\Recordatorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecordatorioController extends Controller
{
    public function index()
    {
        $recordatorios = Recordatorio::where('id_usuario', Auth::user()->id_usuario)
            ->orderBy('fecha_vencimiento', 'asc')
            ->get();

        return view('recordatorios.index', compact('recordatorios'));
    }

    public function create()
    {
        return view('recordatorios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'fecha_vencimiento' => 'required|date',
            'frecuencia' => 'required|in:unica,mensual,anual',
        ]);

        Recordatorio::create([
            'id_usuario' => Auth::user()->id_usuario,
            'descripcion' => $request->descripcion,
            'monto' => $request->monto,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'frecuencia' => $request->frecuencia,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('recordatorios.index')
            ->with('success', 'Recordatorio creado exitosamente.');
    }

    public function edit(Recordatorio $recordatorio)
    {
        return view('recordatorios.edit', compact('recordatorio'));
    }

    public function update(Request $request, Recordatorio $recordatorio)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'fecha_vencimiento' => 'required|date',
            'frecuencia' => 'required|in:unica,mensual,anual',
            'estado' => 'required|in:pendiente,notificado,completado',
        ]);

        $recordatorio->update($request->all());

        return redirect()->route('recordatorios.index')
            ->with('success', 'Recordatorio actualizado exitosamente.');
    }

    public function destroy(Recordatorio $recordatorio)
    {
        $recordatorio->delete();

        return redirect()->route('recordatorios.index')
            ->with('success', 'Recordatorio eliminado exitosamente.');
    }
}