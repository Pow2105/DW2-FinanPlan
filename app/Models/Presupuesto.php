<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    protected $table = 'presupuestos';
    protected $primaryKey = 'id_presupuesto';

    protected $fillable = [
        'id_usuario', 
        'id_categoria', 
        'monto_limite', 
        'fecha_inicio', 
        'fecha_fin', 
        'estado'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'monto_limite' => 'decimal:2', // Aseguramos precisión
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    /**
     * Calcula cuánto se ha gastado en esta categoría dentro del periodo.
     * Esta es la función CLAVE que hace útil al presupuesto.
     */
    public function gastoActual()
    {
        return Transaccion::where('id_categoria', $this->id_categoria)
            ->whereHas('cuenta', function($query) {
                $query->where('id_usuario', $this->id_usuario);
            })
            ->where('tipo', 'gasto')
            // Filtramos estrictamente por las fechas del presupuesto
            ->whereBetween('fecha', [$this->fecha_inicio, $this->fecha_fin])
            ->sum('monto');
    }

    /**
     * Calcula el porcentaje de ejecución del presupuesto.
     */
    public function porcentaje()
    {
        $gasto = $this->gastoActual();
        if ($this->monto_limite > 0) {
            return round(($gasto / $this->monto_limite) * 100, 2);
        }
        return 0;
    }

    /**
     * Determina el estado real basado en el gasto.
     */
    public function getEstadoRealAttribute()
    {
        $porcentaje = $this->porcentaje();
        
        if ($porcentaje >= 100) return 'excedido';
        if ($porcentaje >= 80) return 'alerta';
        return 'activo';
    }
}