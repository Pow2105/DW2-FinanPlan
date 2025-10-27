<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'fecha_creacion',
        'tipo_usuario',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'fecha_creacion' => 'date',
    ];

    // Relaciones
    public function cuentas()
    {
        return $this->hasMany(Cuenta::class, 'id_usuario', 'id_usuario');
    }

    public function presupuestos()
    {
        return $this->hasMany(Presupuesto::class, 'id_usuario', 'id_usuario');
    }

    public function metasAhorro()
    {
        return $this->hasMany(MetaAhorro::class, 'id_usuario', 'id_usuario');
    }

    public function recordatorios()
    {
        return $this->hasMany(Recordatorio::class, 'id_usuario', 'id_usuario');
    }
}