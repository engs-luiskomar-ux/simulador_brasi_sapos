<?php

namespace App\Services;

use App\Models\Aposta;
use App\Models\Partida;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApostaService
{
    public function registrar(User $usuario, int $partidaId, string $palpite, int $valor): void
    {
        abort_unless($usuario->isTorcedor(), 403);

        DB::transaction(function () use ($usuario, $partidaId, $palpite, $valor) {
            $partida = Partida::query()->lockForUpdate()->findOrFail($partidaId);
            $carteira = User::query()->lockForUpdate()->findOrFail($usuario->id);

            if ($partida->estaFinalizada()) {
                throw ValidationException::withMessages(['aposta' => 'Esta partida já foi encerrada.']);
            }
            if (! isset(Aposta::OPCOES[$palpite]) || $valor < 10 || $valor > 1000) {
                throw ValidationException::withMessages(['aposta' => 'Escolha um resultado e use entre 10 e 1.000 créditos inteiros.']);
            }
            if ($carteira->saldo_creditos < $valor) {
                throw ValidationException::withMessages(['aposta' => 'Saldo de créditos insuficiente.']);
            }

            $carteira->decrement('saldo_creditos', $valor);
            Aposta::create([
                'user_id' => $usuario->id,
                'partida_id' => $partida->id,
                'confronto' => $partida->mandante->nome.' × '.$partida->visitante->nome,
                'palpite' => $palpite,
                'valor' => $valor,
                'multiplicador' => Aposta::OPCOES[$palpite]['multiplicador'],
            ]);
        });
    }

    // Executado dentro da mesma transação que grava o resultado da partida.
    public function liquidar(Partida $partida): void
    {
        if (! $partida->estaFinalizada()) {
            return;
        }

        $resultado = $partida->gols_mandante === $partida->gols_visitante
            ? 'empate'
            : ($partida->gols_mandante > $partida->gols_visitante ? 'mandante' : 'visitante');

        foreach (Aposta::where('partida_id', $partida->id)->where('status', 'pendente')->lockForUpdate()->get() as $aposta) {
            $ganhou = $aposta->palpite === $resultado;
            $retorno = $ganhou ? $aposta->valor * $aposta->multiplicador : 0;
            $aposta->update([
                'status' => $ganhou ? 'ganha' : 'perdida',
                'retorno' => $retorno,
                'placar' => $partida->gols_mandante.' × '.$partida->gols_visitante,
            ]);
            if ($retorno > 0) {
                User::whereKey($aposta->user_id)->increment('saldo_creditos', $retorno);
            }
        }
    }

    public function cancelar(Aposta $aposta, User $usuario): void
    {
        abort_unless($aposta->user_id === $usuario->id, 403);

        DB::transaction(function () use ($aposta) {
            $partida = Partida::query()->lockForUpdate()->findOrFail($aposta->partida_id);
            $atual = Aposta::query()->lockForUpdate()->findOrFail($aposta->id);
            if ($partida->estaFinalizada() || $atual->status !== 'pendente') {
                throw ValidationException::withMessages(['aposta' => 'Só é possível cancelar um palpite pendente antes do resultado.']);
            }
            $this->devolver($atual);
        });
    }

    public function devolver(Aposta $aposta): void
    {
        $aposta->update(['status' => 'cancelada', 'retorno' => $aposta->valor]);
        User::whereKey($aposta->user_id)->increment('saldo_creditos', $aposta->valor);
    }
}
