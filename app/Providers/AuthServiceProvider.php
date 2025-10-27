<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Cuenta;
use App\Policies\CuentaPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Cuenta::class => CuentaPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}