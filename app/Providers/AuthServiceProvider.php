<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Chamado; // Adicionar import do Chamado
use App\Policies\ChamadoPolicy; // Adicionar import da Policy
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Chamado::class => ChamadoPolicy::class, 
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isTecnico', function (User $user) {
            return $user->role === 'tecnico';
        });

        Gate::define('isColaborador', function (User $user) {
            return $user->role === 'colaborador';
        });

    }
}