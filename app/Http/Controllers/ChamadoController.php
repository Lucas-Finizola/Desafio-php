<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChamadoController extends Controller
{
    public function index()
    {
        $chamados = auth()->user()->chamados()->latest()->get();

        return Inertia::render('Chamados/Index', compact('chamados'));
    }

    public function create()
    {
        return Inertia::render('Chamados/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required',
            'categoria' => 'required|string',
            'prioridade' => 'required|in:Baixa,Média,Alta',
            'anexo' => 'nullable|file|max:2048',
        ]);

        if ($request->hasFile('anexo')) {
            $data['anexo'] = $request->file('anexo')->store('anexos', 'public');
        }

        $request->user()->chamados()->create($data);

        return redirect()->route('chamados.index')->with('success', 'Chamado criado com sucesso.');
    }

    public function show(Chamado $chamado)
{
    return Inertia::render('Chamados/Show', [
        'chamado' => $chamado->load('user', 'respostas.user')
    ]);
}
}

