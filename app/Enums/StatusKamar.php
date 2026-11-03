<?php

namespace App\Enums;

enum StatusKamar: string
{
    case Tersedia = 'tersedia';
    case Nonaktif = 'nonaktif';

    public function label(): string
    {
        return match ($this) {
            self::Tersedia => 'Tersedia',
            self::Nonaktif => 'Nonaktif',
        };
    }
}
