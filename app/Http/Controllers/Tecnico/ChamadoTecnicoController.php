<?php

namespace App\Http\Controllers\Tecnico;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRespostaRequest;
use App\Http\Requests\UpdateChamadoStatusRequest;
use App\Models\Chamado;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class ChamadoTecnicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Chamado::query()
            ->with(['user', 'tecnico', 'respostas' => function ($query) {
                $query->latest()->limit(1);
            }]);

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('prioridade')) {
            $query->where('prioridade', $request->prioridade);
        }

        // Paginação com 15 itens por página
        $chamados = $query->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Tecnico/Chamados/Index', [
            'chamados' => $chamados,
            'filters' => $request->only(['status', 'prioridade'])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Chamado $chamado)
    {
        // Autorização
        Gate::authorize('view-tecnico', $chamado);

        return Inertia::render('Tecnico/Chamados/Show', [
            'chamado' => $chamado->load(['user', 'tecnico', 'respostas.user']),
            'statusOptions' => ['Aberto', 'Em atendimento', 'Resolvido', 'Fechado']
        ]);
    }

    /**
     * Store a newly created response in storage.
     */
    public function responder(StoreRespostaRequest $request, Chamado $chamado)
    {
        // Autorização
        Gate::authorize('responder', $chamado);

        try {
            $chamado->respostas()->create([
                'user_id' => auth()->id(),
                'mensagem' => $request->validated()['mensagem'],
            ]);

            // Atualiza status para "Em atendimento" se estiver "Aberto"
            if ($chamado->status === 'Aberto') {
                $chamado->update(['status' => 'Em atendimento']);
            }

            return back()->with('success', 'Resposta enviada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao enviar resposta: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource's status.
     */
    public function alterarStatus(UpdateChamadoStatusRequest $request, Chamado $chamado)
    {
        // Autorização
        Gate::authorize('update-status', $chamado);

        try {
            $chamado->update([
                'status' => $request->validated()['status'],
                // Atribui o técnico se estiver sendo atendido
                'tecnico_id' => $request->status === 'Em atendimento' ? auth()->id() : $chamado->tecnico_id
            ]);

            return back()->with('success', 'Status atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar status: ' . $e->getMessage());
        }
    }
}