<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Rule;
use App\Models\MetaAhorro;
use App\Models\Cuenta;
use App\Models\Transaccion;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MetaAhorroController extends Controller
{
    public function index()
    {
        $metas = MetaAhorro::where('id_usuario', Auth::user()->id_usuario)
            ->orderBy('fecha_limite', 'asc')
            ->get();

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
            'monto_objetivo' => 'required|numeric|min:0.01',
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
        // Pasamos cuentas y categorías para el formulario de "Añadir Fondos"
        $cuentas = Cuenta::where('id_usuario', Auth::user()->id_usuario)->get();
        // Buscamos categorías de tipo 'gasto' para clasificar el ahorro
        $categorias = Categoria::where('tipo', 'gasto')->get();
        
        return view('metas.show', compact('meta', 'cuentas', 'categorias'));
    }

    public function edit(MetaAhorro $meta)
    {
        return view('metas.edit', compact('meta'));
    }

    public function update(Request $request, MetaAhorro $meta)
    {
        $request->validate([
            'nombre_meta' => 'required|string|max:100',
            'monto_objetivo' => 'required|numeric|min:0.01',
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

    /**
     * MEJORA PRINCIPAL: Ahorro Real conectado a Cuentas
     */
    public function addFunds(Request $request, MetaAhorro $meta)
    {
        if ($meta->id_usuario !== Auth::id()) abort(403);

        $request->validate([
            'monto' => 'required|numeric|min:0.01',
            // SEGURIDAD AQUÍ TAMBIÉN
            'id_cuenta' => [
                'required',
                Rule::exists('cuentas', 'id_cuenta')->where(function ($query) {
                    return $query->where('id_usuario', Auth::id());
                }),
            ],
            'id_categoria' => 'required|exists:categorias,id_categoria',
        ]);

        DB::transaction(function () use ($request, $meta) {
            $monto = round($request->monto, 2);

            // 1. Descontar de la Cuenta (Dinero real sale)
            $cuenta = Cuenta::find($request->id_cuenta);
            $cuenta->saldo_actual = round($cuenta->saldo_actual - $monto, 2);
            $cuenta->save();

            // 2. Sumar a la Meta (Ahorro aumenta)
            $meta->monto_actual = round($meta->monto_actual + $monto, 2);
            
            // Actualizar estado si se completó
            if ($meta->monto_actual >= $meta->monto_objetivo) {
                $meta->estado = 'completada';
            }
            $meta->save();

            // 3. Registrar Transacción (Para el historial)
            Transaccion::create([
                'id_cuenta' => $cuenta->id_cuenta,
                'id_categoria' => $request->id_categoria,
                'tipo' => 'gasto', // Se considera gasto porque sale de la cuenta disponible
                'monto' => $monto,
                'fecha' => now(),
                'descripcion' => "Ahorro para meta: " . $meta->nombre_meta,
            ]);
        });

        return redirect()->route('metas.show', $meta->id_meta)
            ->with('success', '¡Ahorro registrado! Se descontó de tu cuenta correctamente.');
    }

    public function destroy(MetaAhorro $meta)
    {
        $meta->delete();

        return redirect()->route('metas.index')
            ->with('success', 'Meta eliminada exitosamente.');
    }
}