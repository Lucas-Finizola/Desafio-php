<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ChamadoController;
use App\Http\Controllers\Tecnico\ChamadoTecnicoController;

/**
 * Redireciona a raiz da aplicação diretamente para a tela de login
 */
Route::get('/', function () {
    return redirect()->route('login');
});

/**
 * Rota de teste de autenticação (acessível sem verificação de e-mail)
 */
Route::get('/test-auth', function() {
    if (auth()->check()) {
        return response()->json([
            'authenticated' => true,
            'user' => auth()->user()->only('id', 'name', 'email', 'role'),
            'is_tecnico' => auth()->user()->isTecnico(),
            'is_colaborador' => auth()->user()->isColaborador()
        ]);
    }
    return response()->json(['authenticated' => false]);
});

/**
 * Grupo de rotas protegidas por autenticação e verificação de e-mail
 */
Route::middleware([
    'auth:web',
    'verified',
])->group(function () {

    /**
     * Redirecionamento após login baseado no perfil
     */
    Route::get('/dashboard', function () {
        if (auth()->user()->isTecnico()) {
            return redirect()->route('tecnico.chamados.index');
        }
        return redirect()->route('chamados.index');
    })->name('dashboard');

    /**
     * Rotas do COLABORADOR
     * - Acessível apenas para usuários com role 'colaborador'
     */
    Route::middleware('can:isColaborador')->group(function () {
        Route::resource('chamados', ChamadoController::class)->except(['edit', 'update']);
        

        // Route::get('chamados/{chamado}/edit', [ChamadoController::class, 'edit'])->name('chamados.edit');
        // Route::patch('chamados/{chamado}', [ChamadoController::class, 'update'])->name('chamados.update');
    });

    /**
     * Rotas do TÉCNICO
     * - Acessível apenas para usuários com role 'tecnico'
     */
    Route::prefix('tecnico')->name('tecnico.')->middleware('can:isTecnico')->group(function () {
        Route::get('chamados', [ChamadoTecnicoController::class, 'index'])->name('chamados.index');
        Route::get('chamados/{chamado}', [ChamadoTecnicoController::class, 'show'])->name('chamados.show');
        Route::post('chamados/{chamado}/resposta', [ChamadoTecnicoController::class, 'responder'])->name('chamados.responder');
        Route::patch('chamados/{chamado}/status', [ChamadoTecnicoController::class, 'alterarStatus'])->name('chamados.status');
    });
});