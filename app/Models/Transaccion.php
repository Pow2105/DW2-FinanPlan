<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; // Necesario para el cast de fecha

class Transaccion extends Model
{
    protected $table = 'transacciones';
    protected $primaryKey = 'id_transaccion'; 

    protected $fillable = [
        'id_cuenta', 
        'id_categoria', 
        'tipo', 
        'monto', 
        'fecha', 
        'descripcion'
    ]; 

    // Asegura que el campo 'fecha' se trate como un objeto Carbon/Date
    protected $casts = [
        'fecha' => 'date',
    ];

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class, 'id_cuenta', 'id_cuenta');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }
}