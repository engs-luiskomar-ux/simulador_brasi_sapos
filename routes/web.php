<?php

use App\Http\Controllers\ClassificacaoController;
use App\Http\Controllers\ApostaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PartidaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SimulacaoController;
use App\Http\Controllers\TimeController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'bet-home')->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/apostas', [ApostaController::class, 'index'])->name('apostas.index');
    Route::get('/meus-palpites', [ApostaController::class, 'historico'])->name('apostas.historico');
    Route::post('/apostas', [ApostaController::class, 'store'])->middleware('role:torcedor')->name('apostas.store');
    Route::post('/apostas/{aposta}/cancelar', [ApostaController::class, 'cancelar'])->name('apostas.cancelar');
    Route::get('/classificacao', [ClassificacaoController::class, 'index'])
        ->name('classificacao.index');

    Route::middleware('role:admin,organizador')->group(function () {
        Route::resource('partidas', PartidaController::class)
            ->only(['create', 'store', 'edit', 'update']);
        Route::post('/partidas/{partida}/simular', [SimulacaoController::class, 'partida'])
            ->name('partidas.simular');
        Route::post('/simulacao/proxima-rodada', [SimulacaoController::class, 'proximaRodada'])
            ->name('simulacao.proxima');
    });

    Route::middleware('role:admin')->group(function () {
        Route::resource('times', TimeController::class)
            ->only(['create', 'store', 'edit', 'update']);
        Route::post('/simulacao/reiniciar', [SimulacaoController::class, 'reiniciar'])
            ->name('simulacao.reiniciar');

        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/{usuario}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
    });

    // As rotas fixas de criação/edição ficam acima das rotas com parâmetros.
    Route::resource('times', TimeController::class)->only(['index', 'show']);
    Route::resource('partidas', PartidaController::class)->only(['index', 'show']);
    Route::delete('/times/{time}', [TimeController::class, 'destroy'])
        ->name('times.destroy');
    Route::delete('/partidas/{partida}', [PartidaController::class, 'destroy'])
        ->name('partidas.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
