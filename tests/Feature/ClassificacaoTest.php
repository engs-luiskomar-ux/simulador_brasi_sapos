<?php

namespace Tests\Feature;

use App\Enums\PartidaStatus;
use App\Models\Partida;
use App\Models\Time;
use App\Services\ClassificacaoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClassificacaoTest extends TestCase
{
    use RefreshDatabase;

    public function test_calcula_pontos_estatisticas_e_ignora_partidas_agendadas(): void
    {
        $timeA = $this->criarTime('TMA');
        $timeB = $this->criarTime('TMB');
        $timeC = $this->criarTime('TMC');
        $timeD = $this->criarTime('TMD');

        $this->criarPartidaFinalizada(1, $timeA, $timeB, 2, 0);
        $this->criarPartidaFinalizada(1, $timeC, $timeD, 1, 1);
        $this->criarPartidaFinalizada(2, $timeA, $timeC, 0, 1);
        $this->criarPartidaFinalizada(2, $timeB, $timeD, 3, 0);

        Partida::query()->create([
            'rodada' => 3,
            'data_partida' => now()->addDay(),
            'mandante_id' => $timeA->id,
            'visitante_id' => $timeD->id,
            'gols_mandante' => null,
            'gols_visitante' => null,
            'status' => PartidaStatus::AGENDADA,
            'simulada_por' => null,
        ]);

        $classificacao = collect(app(ClassificacaoService::class)->calcular());

        $this->assertCount(4, $classificacao);
        $this->assertSame(
            ['TMC', 'TMB', 'TMA', 'TMD'],
            $classificacao->map(fn (mixed $linha): string => $this->sigla($linha))->values()->all(),
        );

        $this->assertEstatisticas($this->linha($classificacao, 'TMC'), [4, 2, 1, 1, 0, 2, 1, 1]);
        $this->assertEstatisticas($this->linha($classificacao, 'TMB'), [3, 2, 1, 0, 1, 3, 2, 1]);
        $this->assertEstatisticas($this->linha($classificacao, 'TMA'), [3, 2, 1, 0, 1, 2, 1, 1]);
        $this->assertEstatisticas($this->linha($classificacao, 'TMD'), [1, 2, 0, 1, 1, 1, 4, -3]);
    }

    public function test_vitorias_desempatam_antes_do_saldo_de_gols(): void
    {
        $timeA = $this->criarTime('TMA');
        $timeB = $this->criarTime('TMB');
        $timeC = $this->criarTime('TMC');
        $timeD = $this->criarTime('TMD');
        $timeE = $this->criarTime('TME');
        $timeF = $this->criarTime('TMF');

        // TMA: 6 pontos, duas vitórias e saldo -8.
        $this->criarPartidaFinalizada(1, $timeA, $timeC, 1, 0);
        $this->criarPartidaFinalizada(2, $timeA, $timeD, 1, 0);
        $this->criarPartidaFinalizada(3, $timeE, $timeA, 10, 0);

        // TMB: 6 pontos, uma vitória e saldo +10.
        $this->criarPartidaFinalizada(5, $timeB, $timeC, 10, 0);
        $this->criarPartidaFinalizada(2, $timeB, $timeD, 0, 0);
        $this->criarPartidaFinalizada(3, $timeB, $timeE, 0, 0);
        $this->criarPartidaFinalizada(4, $timeB, $timeF, 0, 0);

        $classificacao = collect(app(ClassificacaoService::class)->calcular());
        $posicaoA = $classificacao->search(fn (mixed $linha): bool => $this->sigla($linha) === 'TMA');
        $posicaoB = $classificacao->search(fn (mixed $linha): bool => $this->sigla($linha) === 'TMB');

        $this->assertIsInt($posicaoA);
        $this->assertIsInt($posicaoB);
        $this->assertLessThan($posicaoB, $posicaoA, 'Duas vitórias devem superar uma vitória, mesmo com saldo inferior.');
    }

    private function criarTime(string $sigla): Time
    {
        return Time::query()->create([
            'nome' => "Time {$sigla}",
            'sigla' => $sigla,
            'cidade' => 'Cidade Teste',
            'estado' => 'SC',
            'estadio' => "Estádio {$sigla}",
            'cor_primaria' => '#123456',
        ]);
    }

    private function criarPartidaFinalizada(
        int $rodada,
        Time $mandante,
        Time $visitante,
        int $golsMandante,
        int $golsVisitante,
    ): Partida {
        return Partida::query()->create([
            'rodada' => $rodada,
            'data_partida' => now()->subDays(10 - $rodada),
            'mandante_id' => $mandante->id,
            'visitante_id' => $visitante->id,
            'gols_mandante' => $golsMandante,
            'gols_visitante' => $golsVisitante,
            'status' => PartidaStatus::FINALIZADA,
            'simulada_por' => null,
        ]);
    }

    private function linha(mixed $classificacao, string $sigla): mixed
    {
        $linha = collect($classificacao)->first(fn (mixed $item): bool => $this->sigla($item) === $sigla);

        $this->assertNotNull($linha, "O time {$sigla} não foi encontrado na classificação.");

        return $linha;
    }

    private function sigla(mixed $linha): string
    {
        return (string) (data_get($linha, 'time.sigla') ?? data_get($linha, 'sigla'));
    }

    /**
     * @param  array{int, int, int, int, int, int, int, int}  $esperado
     */
    private function assertEstatisticas(mixed $linha, array $esperado): void
    {
        $atual = [
            (int) data_get($linha, 'pontos'),
            (int) data_get($linha, 'jogos'),
            (int) data_get($linha, 'vitorias'),
            (int) data_get($linha, 'empates'),
            (int) data_get($linha, 'derrotas'),
            (int) data_get($linha, 'gols_pro'),
            (int) data_get($linha, 'gols_contra'),
            (int) (data_get($linha, 'saldo_gols') ?? data_get($linha, 'saldo')),
        ];

        $this->assertSame($esperado, $atual);
    }
}
