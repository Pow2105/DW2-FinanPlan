<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CuentaController extends Controller
{
    public function index()
    {
        $cuentas = Cuenta::where('id_usuario', Auth::user()->id_usuario)->get();
        return view('cuentas.index', compact('cuentas'));
    }

    public function create()
    {
        return view('cuentas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo' => 'required|in:ahorros,corriente,tarjeta_credito,efectivo',
            'saldo_inicial' => 'required|numeric|min:0',
        ]);

        Cuenta::create([
            'id_usuario' => Auth::user()->id_usuario,
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'saldo_inicial' => $request->saldo_inicial,
            'saldo_actual' => $request->saldo_inicial,
        ]);

        return redirect()->route('cuentas.index')
            ->with('success', 'Cuenta creada exitosamente.');
    }

    public function show(Cuenta $cuenta)
    {
        $this->authorize('view', $cuenta);
        $transacciones = $cuenta->transacciones()->orderBy('fecha', 'desc')->get();
        return view('cuentas.show', compact('cuenta', 'transacciones'));
    }

    public function edit(Cuenta $cuenta)
    {
        $this->authorize('update', $cuenta);
        return view('cuentas.edit', compact('cuenta'));
    }

    public function update(Request $request, Cuenta $cuenta)
    {
        $this->authorize('update', $cuenta);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo' => 'required|in:ahorros,corriente,tarjeta_credito,efectivo',
        ]);

        $cuenta->update([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
        ]);

        return redirect()->route('cuentas.index')
            ->with('success', 'Cuenta actualizada exitosamente.');
    }

    public function destroy(Cuenta $cuenta)
    {
        $this->authorize('delete', $cuenta);
        $cuenta->delete();

        return redirect()->route('cuentas.index')
            ->with('success', 'Cuenta eliminada exitosamente.');
    }
}