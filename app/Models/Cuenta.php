<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Cuenta extends Model
{
    protected $table = 'cuentas';
    protected $primaryKey = 'id_cuenta';

    protected $fillable = [
        'id_usuario', 
        'nombre',
        'tipo',
        'saldo_inicial',
        'saldo_actual', 
        'moneda'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function transacciones()
    {
        return $this->hasMany(Transaccion::class, 'id_cuenta', 'id_cuenta');
    }
}