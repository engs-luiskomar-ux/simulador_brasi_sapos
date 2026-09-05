<?php

namespace App\Models;

use App\Enums\PartidaStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partida extends Model
{
    protected $fillable = [
        'rodada',
        'data_partida',
        'mandante_id',
        'visitante_id',
        'gols_mandante',
        'gols_visitante',
        'status',
        'simulada_por',
    ];

    protected function casts(): array
    {
        return [
            'rodada' => 'integer',
            'data_partida' => 'datetime',
            'gols_mandante' => 'integer',
            'gols_visitante' => 'integer',
            'status' => PartidaStatus::class,
        ];
    }

    public function mandante(): BelongsTo
    {
        return $this->belongsTo(Time::class, 'mandante_id');
    }

    public function visitante(): BelongsTo
    {
        return $this->belongsTo(Time::class, 'visitante_id');
    }

    public function simulador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'simulada_por');
    }

    public function scopeFinalizadas($query)
    {
        return $query->where('status', PartidaStatus::FINALIZADA->value);
    }

    public function scopeAgendadas($query)
    {
        return $query->where('status', PartidaStatus::AGENDADA->value);
    }

    public function estaFinalizada(): bool
    {
        return $this->status === PartidaStatus::FINALIZADA;
    }
}
