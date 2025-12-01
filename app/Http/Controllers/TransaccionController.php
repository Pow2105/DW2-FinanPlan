<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use App\Models\Cuenta;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            'id_cuenta' => 'required|exists:cuentas,id_cuenta',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'tipo' => 'required|in:ingreso,gasto',
            'descripcion' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request) {
            $transaccion = Transaccion::create([
                'id_cuenta' => $request->id_cuenta,
                'id_categoria' => $request->id_categoria,
                'monto' => $request->monto,
                'fecha' => $request->fecha,
                'tipo' => $request->tipo,
                'descripcion' => $request->descripcion,
            ]);

            // Actualizar saldo de la cuenta
            $cuenta = Cuenta::find($request->id_cuenta);
            if ($request->tipo == 'ingreso') {
                $cuenta->saldo_actual += $request->monto;
            } else {
                $cuenta->saldo_actual -= $request->monto;
            }
            $cuenta->save();
        });

        return redirect()->route('transacciones.index')
            ->with('success', 'Transacción registrada exitosamente.');
    }

    public function edit(Transaccion $transaccion)
    {
        $cuentas = Cuenta::where('id_usuario', Auth::user()->id_usuario)->get();
        $categorias = Categoria::all();
        return view('transacciones.edit', compact('transaccion', 'cuentas', 'categorias'));
    }

    public function update(Request $request, Transaccion $transaccion)
    {
        $request->validate([
            'id_cuenta' => 'required|exists:cuentas,id_cuenta',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'tipo' => 'required|in:ingreso,gasto',
            'descripcion' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $transaccion) {
            // 1. Revertir el saldo en la cuenta ORIGINAL
            $cuentaOriginal = Cuenta::find($transaccion->id_cuenta);
            if ($transaccion->tipo == 'ingreso') {
                $cuentaOriginal->saldo_actual -= $transaccion->monto;
            } else {
                $cuentaOriginal->saldo_actual += $transaccion->monto;
            }
            $cuentaOriginal->save(); // Guardamos el revertido inmediatamente

            // 2. Actualizar transacción con los nuevos datos
            $transaccion->update([
                'id_cuenta' => $request->id_cuenta,
                'id_categoria' => $request->id_categoria,
                'monto' => $request->monto,
                'fecha' => $request->fecha,
                'tipo' => $request->tipo,
                'descripcion' => $request->descripcion,
            ]);

            // 3. Aplicar nuevo saldo a la cuenta NUEVA (o la misma si no cambió)
            // Buscamos la cuenta fresca usando el ID que viene del formulario
            $cuentaDestino = Cuenta::find($request->id_cuenta);
            
            if ($request->tipo == 'ingreso') {
                $cuentaDestino->saldo_actual += $request->monto;
            } else {
                $cuentaDestino->saldo_actual -= $request->monto;
            }
            $cuentaDestino->save();
        });

        return redirect()->route('transacciones.index')
            ->with('success', 'Transacción actualizada exitosamente.');
    }

    public function destroy(Transaccion $transaccion)
    {
        DB::transaction(function () use ($transaccion) {
            // Revertir el saldo
            $cuenta = Cuenta::find($transaccion->id_cuenta);
            if ($transaccion->tipo == 'ingreso') {
                $cuenta->saldo_actual -= $transaccion->monto;
            } else {
                $cuenta->saldo_actual += $transaccion->monto;
            }
            $cuenta->save();

            $transaccion->delete();
        });

        return redirect()->route('transacciones.index')
            ->with('success', 'Transacción eliminada exitosamente.');
    }
}