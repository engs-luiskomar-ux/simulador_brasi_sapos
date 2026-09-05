<?php

namespace App\Enums;

enum PartidaStatus: string
{
    case AGENDADA = 'agendada';
    case FINALIZADA = 'finalizada';

    public function label(): string
    {
        return match ($this) {
            self::AGENDADA => 'Agendada',
            self::FINALIZADA => 'Finalizada',
        };
    }
}
