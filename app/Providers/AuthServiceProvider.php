<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Definição dos Gates
        Gate::define('isTecnico', function (User $user) {
            return $user->role === 'tecnico';
        });

        Gate::define('isColaborador', function (User $user) {
            return $user->role === 'colaborador';
        });
    }
}