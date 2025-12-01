<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use App\Models\Cuenta;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; // <--- IMPORTANTE: Importar Rule

class TransaccionController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $transacciones = Transaccion::whereHas('cuenta', function($query) use ($usuario) {
            $query->where('id_usuario', $usuario->id_usuario);
        })->with(['cuenta', 'categoria'])
          ->orderBy('fecha', 'desc')
          ->paginate(20);

        return view('transacciones.index', compact('transacciones'));
    }

    public function create()
    {
        $cuentas = Cuenta::where('id_usuario', Auth::user()->id_usuario)->get();
        $categorias = Categoria::all();
        return view('transacciones.create', compact('cuentas', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // SEGURIDAD: Verificamos que la cuenta exista Y pertenezca al usuario actual
            'id_cuenta' => [
                'required',
                Rule::exists('cuentas', 'id_cuenta')->where(function ($query) {
                    return $query->where('id_usuario', Auth::id());
                }),
            ],
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'tipo' => 'required|in:ingreso,gasto',
            'descripcion' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request) {
            $monto = round($request->monto, 2);

            $transaccion = Transaccion::create([
                'id_cuenta' => $request->id_cuenta,
                'id_categoria' => $request->id_categoria,
                'monto' => $monto,
                'fecha' => $request->fecha,
                'tipo' => $request->tipo,
                'descripcion' => $request->descripcion,
            ]);

            $cuenta = Cuenta::find($request->id_cuenta);
            if ($request->tipo == 'ingreso') {
                $cuenta->saldo_actual = round($cuenta->saldo_actual + $monto, 2);
            } else {
                $cuenta->saldo_actual = round($cuenta->saldo_actual - $monto, 2);
            }
            $cuenta->save();
        });

        return redirect()->route('transacciones.index')
            ->with('success', 'Transacción registrada exitosamente.');
    }

    public function edit(Transaccion $transaccion)
    {
        // SEGURIDAD: Verificar que la transacción pertenezca a una cuenta del usuario
        if ($transaccion->cuenta->id_usuario !== Auth::id()) {
            abort(403);
        }

        $cuentas = Cuenta::where('id_usuario', Auth::user()->id_usuario)->get();
        $categorias = Categoria::all();
        return view('transacciones.edit', compact('transaccion', 'cuentas', 'categorias'));
    }

    public function update(Request $request, Transaccion $transaccion)
    {
        if ($transaccion->cuenta->id_usuario !== Auth::id()) abort(403);

        $request->validate([
            'id_cuenta' => [
                'required',
                Rule::exists('cuentas', 'id_cuenta')->where(function ($query) {
                    return $query->where('id_usuario', Auth::id());
                }),
            ],
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'tipo' => 'required|in:ingreso,gasto',
            'descripcion' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $transaccion) {
            $nuevoMonto = round($request->monto, 2);

            // 1. Revertir saldo en cuenta original
            $cuentaOriginal = Cuenta::find($transaccion->id_cuenta);
            $montoOriginal = round($transaccion->monto, 2);

            if ($transaccion->tipo == 'ingreso') {
                $cuentaOriginal->saldo_actual = round($cuentaOriginal->saldo_actual - $montoOriginal, 2);
            } else {
                $cuentaOriginal->saldo_actual = round($cuentaOriginal->saldo_actual + $montoOriginal, 2);
            }
            $cuentaOriginal->save();

            // 2. Actualizar transacción
            $transaccion->update([
                'id_cuenta' => $request->id_cuenta,
                'id_categoria' => $request->id_categoria,
                'monto' => $nuevoMonto,
                'fecha' => $request->fecha,
                'tipo' => $request->tipo,
                'descripcion' => $request->descripcion,
            ]);

            // 3. Aplicar saldo a cuenta nueva/actual
            $cuentaDestino = Cuenta::find($request->id_cuenta);
            
            if ($request->tipo == 'ingreso') {
                $cuentaDestino->saldo_actual = round($cuentaDestino->saldo_actual + $nuevoMonto, 2);
            } else {
                $cuentaDestino->saldo_actual = round($cuentaDestino->saldo_actual - $nuevoMonto, 2);
            }
            $cuentaDestino->save();
        });

        return redirect()->route('transacciones.index')
            ->with('success', 'Transacción actualizada exitosamente.');
    }

    public function destroy(Transaccion $transaccion)
    {
        if ($transaccion->cuenta->id_usuario !== Auth::id()) abort(403);

        DB::transaction(function () use ($transaccion) {
            $monto = round($transaccion->monto, 2);
            $cuenta = Cuenta::find($transaccion->id_cuenta);
            
            if ($transaccion->tipo == 'ingreso') {
                $cuenta->saldo_actual = round($cuenta->saldo_actual - $monto, 2);
            } else {
                $cuenta->saldo_actual = round($cuenta->saldo_actual + $monto, 2);
            }
            $cuenta->save();

            $transaccion->delete();
        });

        return redirect()->route('transacciones.index')
            ->with('success', 'Transacción eliminada exitosamente.');
    }
}