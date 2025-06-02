<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // Mantenha se usar verificação de e-mail
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasTeams; // Se estiver usando a funcionalidade de Times do Jetstream

// Certifique-se de importar Chamado se não estiver usando o namespace completo no método
// use App\Models\Chamado;

class User extends Authenticatable implements MustVerifyEmail // Implemente se 'verified' middleware está ativo
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasProfilePhoto, TwoFactorAuthenticatable, HasTeams;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', 
    ];

    // RELACIONAMENTO FALTANTE ADICIONADO AQUI
    public function chamados()
    {
        return $this->hasMany(Chamado::class); 
    }

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