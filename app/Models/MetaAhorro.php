<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'monto_objetivo' => 'decimal:2',
        'monto_actual' => 'decimal:2',
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

    /**
     * Calcula el estado dinámico de la meta.
     * Prioriza si ya está completada, luego verifica fechas.
     */
    public function getEstadoRealAttribute()
    {
        // Si ya se alcanzó el monto, está completada
        if ($this->monto_actual >= $this->monto_objetivo) {
            return 'completada';
        }

        // Si la fecha ya pasó (ayer o antes) y no se ha completado
        if ($this->fecha_limite->isPast() && !$this->fecha_limite->isToday()) {
            return 'vencida';
        }

        return 'en_progreso';
    }
}