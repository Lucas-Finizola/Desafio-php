<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChamadoRequest;
use App\Http\Requests\UpdateChamadoRequest;
use App\Models\Chamado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChamadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chamados = Auth::user()->chamados()
                      ->with(['user', 'tecnico'])
                      ->latest()
                      ->get();

        return view('chamados.index', compact('chamados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('chamados.create')->with('info', 'Preencha os dados do novo chamado');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChamadoRequest $request)
    {
        try {
            $data = $request->validated();
            
            if ($request->hasFile('anexo')) {
                $data['anexo'] = $request->file('anexo')->store('anexos', 'public');
            }

            $request->user()->chamados()->create($data);

            return redirect()->route('chamados.index')
                   ->with('success', 'Chamado criado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao criar chamado: '.$e->getMessage())
                   ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Chamado $chamado)
    {
        $this->authorize('view', $chamado);

        return view('chamados.show', [
            'chamado' => $chamado->load(['user', 'tecnico', 'respostas.user'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chamado $chamado)
    {
        $this->authorize('update', $chamado);

        return view('chamados.edit', compact('chamado'))
               ->with('warning', 'Editando chamado - todas as alterações serão registradas');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChamadoRequest $request, Chamado $chamado)
    {
        $this->authorize('update', $chamado);

        try {
            $data = $request->validated();
            
            if ($request->hasFile('anexo')) {
                // Remove anexo antigo se existir
                if ($chamado->anexo) {
                    Storage::disk('public')->delete($chamado->anexo);
                }
                $data['anexo'] = $request->file('anexo')->store('anexos', 'public');
            }

            $chamado->update($data);

            return redirect()->route('chamados.show', $chamado)
                   ->with('success', 'Chamado atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar chamado: '.$e->getMessage())
                   ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chamado $chamado)
    {
        $this->authorize('delete', $chamado);

        try {
            // Remove anexo se existir
            if ($chamado->anexo) {
                Storage::disk('public')->delete($chamado->anexo);
            }

            $chamado->delete();

            return redirect()->route('chamados.index')
                   ->with('success', 'Chamado excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir chamado: '.$e->getMessage());
        }
    }
}