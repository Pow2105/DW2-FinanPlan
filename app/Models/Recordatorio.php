<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recordatorio extends Model
{
    protected $table = 'recordatorios';
    protected $primaryKey = 'id_recordatorio';

    protected $fillable = [
        'id_usuario', 
        'id_cuenta',      // Nuevo
        'id_categoria',   // Nuevo
        'tipo',           // Nuevo
        'descripcion', 
        'monto', 
        'fecha_vencimiento', 
        'frecuencia', 
        'estado'
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'monto' => 'decimal:2',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class, 'id_cuenta', 'id_cuenta');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }
}