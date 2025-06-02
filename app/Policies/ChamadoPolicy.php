<?php

namespace App\Policies;

use App\Models\Chamado;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization; // Laravel 9+
// Para Laravel mais antigo, pode ser: use Illuminate\Auth\Access\Response;

class ChamadoPolicy
{
    // Para Laravel 9+, use HandlesAuthorization.
    // Para versões mais antigas, você pode não ter este trait por padrão no arquivo gerado.
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     * (Quem pode ver a lista de todos os chamados - geralmente técnicos ou admin)
     */
    public function viewAny(User $user): bool
    {
        return $user->isTecnico(); // Exemplo: Só técnicos podem ver todos
    }

    /**
     * Determine whether the user can view the model.
     * (Quem pode ver um chamado específico)
     */
    public function view(User $user, Chamado $chamado): bool
    {
        // Exemplo: Colaborador pode ver seus próprios chamados, técnico pode ver qualquer um.
        if ($user->isTecnico()) {
            return true;
        }
        return $user->id === $chamado->user_id; // Colaborador só vê os seus
    }

    /**
     * Determine whether the user can create models.
     * (Quem pode criar chamados - geralmente colaboradores)
     */
    public function create(User $user): bool
    {
        return $user->isColaborador(); // Exemplo: Só colaboradores criam
    }

    /**
     * Determine whether the user can update the model.
     * (Quem pode atualizar um chamado)
     */
    public function update(User $user, Chamado $chamado): bool
    {
        // Exemplo: Colaborador pode atualizar seus próprios chamados SE AINDA ESTIVEREM ABERTOS,
        // e técnicos podem atualizar qualquer chamado.
        if ($user->isTecnico()) {
            return true;
        }
        // Colaboradores não podem editar/atualizar conforme suas rotas: ->except(['edit', 'update'])
        // Mas se fossem poder, seria algo como:
        // return $user->id === $chamado->user_id && $chamado->status === 'Aberto';
        return false; // No seu caso, colaboradores não atualizam pelos controllers
    }

    /**
     * Determine whether the user can delete the model.
     * (Quem pode deletar um chamado)
     */
    public function delete(User $user, Chamado $chamado): bool
    {
        // Exemplo: Colaborador pode deletar os seus, técnico pode deletar qualquer um.
        if ($user->isTecnico()) {
            return true;
        }
        return $user->id === $chamado->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     * (Se você usar Soft Deletes)
     */
    public function restore(User $user, Chamado $chamado): bool
    {
        return $user->isTecnico(); // Exemplo
    }

    /**
     * Determine whether the user can permanently delete the model.
     * (Se você usar Soft Deletes e deleção permanente)
     */
    public function forceDelete(User $user, Chamado $chamado): bool
    {
        return $user->isTecnico(); // Exemplo
    }
}