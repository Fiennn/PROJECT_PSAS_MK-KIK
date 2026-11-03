<?php

namespace App\Enums;

enum StatusBooking: string
{
    case MenungguPembayaran = 'menunggu_pembayaran';
    case MenungguVerifikasi = 'menunggu_verifikasi';
    case Dikonfirmasi = 'dikonfirmasi';
    case Ditolak = 'ditolak';
    case Selesai = 'selesai';
    case Dibatalkan = 'dibatalkan';

    public function label(): string
    {
        return match ($this) {
            self::MenungguPembayaran => 'Menunggu Pembayaran',
            self::MenungguVerifikasi => 'Menunggu Verifikasi',
            self::Dikonfirmasi => 'Dikonfirmasi',
            self::Ditolak => 'Ditolak',
            self::Selesai => 'Selesai',
            self::Dibatalkan => 'Dibatalkan',
        };
    }

    /**
     * Statuses that still block a room from being booked on the same dates.
     *
     * @return array<int, string>
     */
    public static function activeValues(): array
    {
        return [
            self::MenungguPembayaran->value,
            self::MenungguVerifikasi->value,
            self::Dikonfirmasi->value,
        ];
    }

    public function isActive(): bool
    {
        return in_array($this->value, self::activeValues(), true);
    }
}
