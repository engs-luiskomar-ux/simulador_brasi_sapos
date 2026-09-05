<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Time extends Model
{
    protected $fillable = [
        'nome',
        'sigla',
        'cidade',
        'estado',
        'estadio',
        'cor_primaria',
    ];

    public function partidasComoMandante(): HasMany
    {
        return $this->hasMany(Partida::class, 'mandante_id');
    }

    public function partidasComoVisitante(): HasMany
    {
        return $this->hasMany(Partida::class, 'visitante_id');
    }
}
