<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Wisatawan = 'wisatawan';
    case Pemilik = 'pemilik';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Wisatawan => 'Wisatawan',
            self::Pemilik => 'Pemilik Homestay',
        };
    }

    public function dashboardRoute(): string
    {
        return match ($this) {
            self::Admin => 'admin.dashboard',
            self::Wisatawan => 'wisatawan.dashboard',
            self::Pemilik => 'pemilik.dashboard',
        };
    }
}
