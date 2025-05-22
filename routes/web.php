<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ChamadoController;
use App\Http\Controllers\Tecnico\ChamadoTecnicoController;

// Rota inicial de "boas-vindas" do Jetstream.
// Esta rota NÃO deve ter middlewares de autenticação ou perfil.
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome'); // Dê um nome para facilitar referências.


// Grupo de Rotas Autenticadas (Padrão do Jetstream)
// Contém rotas como o dashboard, que exigem apenas autenticação e verificação de e-mail.
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard principal que redireciona com base no perfil
    Route::get('/dashboard', function () {
        // Assumindo que isTecnico() e isColaborador() existem na Model User
        if (auth()->user()->isTecnico()) {
            return redirect()->route('tecnico.chamados.index');
        }
        return redirect()->route('chamados.index');
    })->name('dashboard');

    // Rotas do COLABORADOR - Protegidas por 'can:isColaborador'
    Route::middleware('can:isColaborador')->group(function () {
        Route::resource('chamados', ChamadoController::class);
    });

    // Rotas do TÉCNICO - Protegidas por 'can:isTecnico'
    Route::prefix('tecnico')->name('tecnico.')->middleware('can:isTecnico')->group(function () {
        Route::get('chamados', [ChamadoTecnicoController::class, 'index'])->name('chamados.index');
        Route::get('chamados/{chamado}', [ChamadoTecnicoController::class, 'show'])->name('chamados.show');
        Route::post('chamados/{chamado}/resposta', [ChamadoTecnicoController::class, 'responder'])->name('chamados.responder');
        Route::patch('chamados/{chamado}/status', [ChamadoTecnicoController::class, 'alterarStatus'])->name('chamados.status');
    });
});

