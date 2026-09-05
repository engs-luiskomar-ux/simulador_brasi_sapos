<?php

namespace Database\Seeders;

use App\Models\Time;
use Illuminate\Database\Seeder;

class TimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $times = [
            ['nome' => 'Athletico Paranaense', 'sigla' => 'CAP', 'cidade' => 'Curitiba', 'estado' => 'PR', 'estadio' => 'Ligga Arena', 'cor_primaria' => '#B91C1C'],
            ['nome' => 'Atlético Mineiro', 'sigla' => 'CAM', 'cidade' => 'Belo Horizonte', 'estado' => 'MG', 'estadio' => 'Arena MRV', 'cor_primaria' => '#111827'],
            ['nome' => 'Bahia', 'sigla' => 'BAH', 'cidade' => 'Salvador', 'estado' => 'BA', 'estadio' => 'Arena Fonte Nova', 'cor_primaria' => '#1D4ED8'],
            ['nome' => 'Botafogo', 'sigla' => 'BOT', 'cidade' => 'Rio de Janeiro', 'estado' => 'RJ', 'estadio' => 'Nilton Santos', 'cor_primaria' => '#111827'],
            ['nome' => 'Chapecoense', 'sigla' => 'CHA', 'cidade' => 'Chapecó', 'estado' => 'SC', 'estadio' => 'Arena Condá', 'cor_primaria' => '#15803D'],
            ['nome' => 'Corinthians', 'sigla' => 'COR', 'cidade' => 'São Paulo', 'estado' => 'SP', 'estadio' => 'Neo Química Arena', 'cor_primaria' => '#111827'],
            ['nome' => 'Coritiba SAF', 'sigla' => 'CFC', 'cidade' => 'Curitiba', 'estado' => 'PR', 'estadio' => 'Couto Pereira', 'cor_primaria' => '#166534'],
            ['nome' => 'Cruzeiro', 'sigla' => 'CRU', 'cidade' => 'Belo Horizonte', 'estado' => 'MG', 'estadio' => 'Mineirão', 'cor_primaria' => '#1D4ED8'],
            ['nome' => 'Flamengo', 'sigla' => 'FLA', 'cidade' => 'Rio de Janeiro', 'estado' => 'RJ', 'estadio' => 'Maracanã', 'cor_primaria' => '#B91C1C'],
            ['nome' => 'Fluminense', 'sigla' => 'FLU', 'cidade' => 'Rio de Janeiro', 'estado' => 'RJ', 'estadio' => 'Maracanã', 'cor_primaria' => '#7F1D1D'],
            ['nome' => 'Grêmio', 'sigla' => 'GRE', 'cidade' => 'Porto Alegre', 'estado' => 'RS', 'estadio' => 'Arena do Grêmio', 'cor_primaria' => '#0284C7'],
            ['nome' => 'Internacional', 'sigla' => 'INT', 'cidade' => 'Porto Alegre', 'estado' => 'RS', 'estadio' => 'Beira-Rio', 'cor_primaria' => '#DC2626'],
            ['nome' => 'Mirassol', 'sigla' => 'MIR', 'cidade' => 'Mirassol', 'estado' => 'SP', 'estadio' => 'José Maria de Campos Maia', 'cor_primaria' => '#CA8A04'],
            ['nome' => 'Palmeiras', 'sigla' => 'PAL', 'cidade' => 'São Paulo', 'estado' => 'SP', 'estadio' => 'Allianz Parque', 'cor_primaria' => '#166534'],
            ['nome' => 'Red Bull Bragantino', 'sigla' => 'RBB', 'cidade' => 'Bragança Paulista', 'estado' => 'SP', 'estadio' => 'Nabi Abi Chedid', 'cor_primaria' => '#DC2626'],
            ['nome' => 'Remo', 'sigla' => 'REM', 'cidade' => 'Belém', 'estado' => 'PA', 'estadio' => 'Mangueirão', 'cor_primaria' => '#1E3A8A'],
            ['nome' => 'Santos FC', 'sigla' => 'SAN', 'cidade' => 'Santos', 'estado' => 'SP', 'estadio' => 'Vila Belmiro', 'cor_primaria' => '#111827'],
            ['nome' => 'São Paulo', 'sigla' => 'SAO', 'cidade' => 'São Paulo', 'estado' => 'SP', 'estadio' => 'MorumBIS', 'cor_primaria' => '#DC2626'],
            ['nome' => 'Vasco da Gama SAF', 'sigla' => 'VAS', 'cidade' => 'Rio de Janeiro', 'estado' => 'RJ', 'estadio' => 'São Januário', 'cor_primaria' => '#111827'],
            ['nome' => 'Vitória', 'sigla' => 'VIT', 'cidade' => 'Salvador', 'estado' => 'BA', 'estadio' => 'Barradão', 'cor_primaria' => '#B91C1C'],
        ];

        foreach ($times as $time) {
            Time::create($time);
        }
    }
}
