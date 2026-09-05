<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case ORGANIZADOR = 'organizador';
    case TORCEDOR = 'torcedor';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::ORGANIZADOR => 'Organizador',
            self::TORCEDOR => 'Torcedor',
        };
    }
}
