<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'id_categoria';

    protected $fillable = [
        'nombre', 
        'tipo', 
        'color',
        'icono'
    ];

    public function transacciones()
    {
        return $this->hasMany(Transaccion::class, 'id_categoria', 'id_categoria');
    }
    
    public function presupuestos()
    {
        return $this->hasMany(Presupuesto::class, 'id_categoria', 'id_categoria');
    }
}