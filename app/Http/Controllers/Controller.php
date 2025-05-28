<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChamadoRequest;
use App\Models\Chamado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChamadoController extends Controller
{
    /*
     */
    public function store(StoreChamadoRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('anexo')) {
            $data['anexo'] = $request->file('anexo')->store('anexos', 'public');
        }

        $request->user()->chamados()->create($data);

        return redirect()->route('chamados.index')
               ->with('success', 'Chamado criado com sucesso!');
    }
    public function show(Chamado $chamado)
{
    $this->authorize('view', $chamado);

    return Inertia::render('Chamados/Show', [
        'chamado' => $chamado->load(['user', 'tecnico', 'respostas.user']),
        'can' => [
            'update' => auth()->user()->can('update', $chamado),
            'delete' => auth()->user()->can('delete', $chamado),
            'responder' => auth()->user()->isTecnico(),
        ]
    ]);
}
}