<?php

namespace App\Policies;

use App\Models\Cuenta;
use App\Models\Usuario;

class CuentaPolicy
{
    public function viewAny(Usuario $usuario): bool
    {
        return true;
    }

    public function view(Usuario $usuario, Cuenta $cuenta): bool
    {
        return $usuario->id_usuario === $cuenta->id_usuario;
    }

    public function create(Usuario $usuario): bool
    {
        return true;
    }

    public function update(Usuario $usuario, Cuenta $cuenta): bool
    {
        return $usuario->id_usuario === $cuenta->id_usuario;
    }

    public function delete(Usuario $usuario, Cuenta $cuenta): bool
    {
        return $usuario->id_usuario === $cuenta->id_usuario;
    }
}