<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetaAhorro extends Model
{
    protected $table = 'metas_ahorro';
    protected $primaryKey = 'id_meta';

    protected $fillable = [
        'id_usuario', 
        'nombre_meta', 
        'monto_objetivo', 
        'monto_actual', 
        'fecha_limite', 
        'estado'
    ];

    protected $casts = [
        'fecha_limite' => 'date',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function porcentajeProgreso()
    {
        if ($this->monto_objetivo > 0) {
            return round(($this->monto_actual / $this->monto_objetivo) * 100, 2);
        }
        return 0;
    }
}