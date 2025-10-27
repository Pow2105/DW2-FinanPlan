<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // Necesario para consultas raw

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
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    // Método para calcular el gasto actual dentro del período del presupuesto
    public function gastoActual()
    {
        return Transaccion::where('id_categoria', $this->id_categoria)
            ->whereHas('cuenta', function($query) {
                $query->where('id_usuario', $this->id_usuario);
            })
            ->where('tipo', 'gasto')
            ->whereBetween('fecha', [$this->fecha_inicio, $this->fecha_fin])
            ->sum('monto');
    }
}