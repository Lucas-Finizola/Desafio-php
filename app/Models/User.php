<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasTeams;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasProfilePhoto, TwoFactorAuthenticatable, HasTeams;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // importante
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Verifica se é técnico
    public function isTecnico()
    {
        return $this->role === 'tecnico';
    }

    // Verifica se é colaborador
    public function isColaborador()
    {
        return $this->role === 'colaborador';
    }
}
