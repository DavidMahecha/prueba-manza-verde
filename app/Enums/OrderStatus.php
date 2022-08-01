<?php

namespace App\Enums;

enum OrderStatus: int
{
    case Solicitado = 1;
    case Completado = 2;
    case Cancelado = 3;

    public static function columnns(): array
    {
        return array_column(self::cases(), 'value');
    }
}