<?php

namespace App\Http\Controllers;

use App\Models\Recordatorio;
use App\Models\Cuenta;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // <--- Importar Rule

class RecordatorioController extends Controller
{
    public function index()
    {
        $recordatorios = Recordatorio::where('id_usuario', Auth::user()->id_usuario)
            ->with(['cuenta', 'categoria'])
            ->orderBy('fecha_vencimiento', 'asc')
            ->get();

        return view('recordatorios.index', compact('recordatorios'));
    }

    public function create()
    {
        $cuentas = Cuenta::where('id_usuario', Auth::user()->id_usuario)->get();
        $categorias = Categoria::all();
        return view('recordatorios.create', compact('cuentas', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0.01',
            'fecha_vencimiento' => 'required|date|after_or_equal:today',
            'frecuencia' => 'required|in:unica,mensual,anual',
            // SEGURIDAD: Solo cuentas del usuario
            'id_cuenta' => [
                'required',
                Rule::exists('cuentas', 'id_cuenta')->where(function ($query) {
                    return $query->where('id_usuario', Auth::id());
                }),
            ],
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'tipo' => 'required|in:ingreso,gasto',
        ]);

        Recordatorio::create([
            'id_usuario' => Auth::user()->id_usuario,
            'id_cuenta' => $request->id_cuenta,
            'id_categoria' => $request->id_categoria,
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'monto' => $request->monto,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'frecuencia' => $request->frecuencia,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('recordatorios.index')
            ->with('success', 'Recordatorio programado exitosamente.');
    }

    public function edit(Recordatorio $recordatorio)
    {
        if ($recordatorio->id_usuario !== Auth::id()) abort(403);
        
        $cuentas = Cuenta::where('id_usuario', Auth::user()->id_usuario)->get();
        $categorias = Categoria::all();
        return view('recordatorios.edit', compact('recordatorio', 'cuentas', 'categorias'));
    }

    // (El método update debería tener la misma validación de seguridad si lo implementas completo)

    public function destroy(Recordatorio $recordatorio)
    {
        if ($recordatorio->id_usuario !== Auth::id()) abort(403);
        
        $recordatorio->delete();
        return redirect()->route('recordatorios.index')->with('success', 'Recordatorio eliminado.');
    }
}