<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChamadoRequest;
use App\Http\Requests\UpdateChamadoRequest; // Certifique-se que este arquivo existe
use App\Models\Chamado;
// Removido: use Illuminate\Http\Request; (UpdateChamadoRequest é usado para update)
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia; // IMPORTAR INERTIA

class ChamadoController extends Controller
{
    public function index()
    {
        $chamados = Auth::user()->chamados()
                          ->with(['user', 'tecnico'])
                          ->latest()
                          ->get();
        // CORRIGIDO: Usar Inertia::render
        return Inertia::render('Chamados/Index', ['chamados' => $chamados]);
    }

    public function create()
    {
        // CORRIGIDO: Usar Inertia::render
        return Inertia::render('Chamados/Create', ['info' => 'Preencha os dados do novo chamado']);
    }

    public function store(StoreChamadoRequest $request)
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('anexo')) {
                $data['anexo'] = $request->file('anexo')->store('anexos', 'public');
            }
            $chamado = $request->user()->chamados()->create($data);
            return redirect()->route('chamados.index')
                   ->with('success', 'Chamado #' . $chamado->id . ' criado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao criar chamado: '.$e->getMessage())->withInput();
        }
    }

    public function show(Chamado $chamado)
    {
        $this->authorize('view', $chamado); // Requer ChamadoPolicy
        // CORRIGIDO: Usar Inertia::render e passar 'can' como nos seus commits
        return Inertia::render('Chamados/Show', [
            'chamado' => $chamado->load(['user', 'tecnico', 'respostas.user']),
            'can' => [
                'update' => Auth::user()->can('update', $chamado),
                'delete' => Auth::user()->can('delete', $chamado),
                'responder' => Auth::user()->isTecnico(),
            ]
        ]);
    }

    public function edit(Chamado $chamado)
    {
        $this->authorize('update', $chamado); // Requer ChamadoPolicy
        // CORRIGIDO: Usar Inertia::render
        return Inertia::render('Chamados/Edit', [ // Assumindo que Pages/Chamados/Edit.vue existe
            'chamado' => $chamado,
            'warning' => 'Editando chamado - todas as alterações serão registradas'
        ]);
    }

    public function update(UpdateChamadoRequest $request, Chamado $chamado)
    {
        $this->authorize('update', $chamado); // Requer ChamadoPolicy
        try {
            $data = $request->validated();
            if ($request->hasFile('anexo')) {
                if ($chamado->anexo) Storage::disk('public')->delete($chamado->anexo);
                $data['anexo'] = $request->file('anexo')->store('anexos', 'public');
            }
            $chamado->update($data);
            return redirect()->route('chamados.show', $chamado) // Ou chamados.index
                   ->with('success', 'Chamado atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar chamado: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(Chamado $chamado)
    {
        $this->authorize('delete', $chamado); // Requer ChamadoPolicy
        try {
            if ($chamado->anexo) Storage::disk('public')->delete($chamado->anexo);
            $chamado->delete();
            return redirect()->route('chamados.index')
                   ->with('success', 'Chamado excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir chamado: '.$e->getMessage());
        }
    }
}