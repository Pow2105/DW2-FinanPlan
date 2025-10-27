<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recordatorio extends Model
{
    protected $table = 'recordatorios';
    protected $primaryKey = 'id_recordatorio';

    protected $fillable = [
        'id_usuario', 
        'descripcion', 
        'monto', 
        'fecha_vencimiento', 
        'frecuencia', 
        'estado'
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}