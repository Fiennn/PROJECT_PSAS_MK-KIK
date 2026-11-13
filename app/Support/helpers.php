<?php

use Carbon\CarbonInterface;

if (! function_exists('rupiah')) {
    /**
     * Format a number as Indonesian Rupiah, for example: Rp 250.000.
     */
    function rupiah(int|float|string|null $value): string
    {
        return 'Rp '.number_format((float) ($value ?? 0), 0, ',', '.');
    }
}

if (! function_exists('tanggal_id')) {
    /**
     * Format a date as "12 Nov 2026".
     */
    function tanggal_id(CarbonInterface|string|null $date): string
    {
        if (blank($date)) {
            return '-';
        }

        $months = [
            1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des',
        ];

        $carbon = $date instanceof CarbonInterface ? $date : \Illuminate\Support\Carbon::parse($date);

        return $carbon->format('d').' '.$months[(int) $carbon->format('n')].' '.$carbon->format('Y');
    }
}

if (! function_exists('bulan_id')) {
    /**
     * Get an Indonesian month name by its number (1-12).
     */
    function bulan_id(int $month): string
    {
        return [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ][$month] ?? '-';
    }
}

if (! function_exists('status_label')) {
    /**
     * Human readable label for the status values used across the app.
     */
    function status_label(string $status): string
    {
        return [
            'menunggu' => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'terverifikasi' => 'Terverifikasi',
            'tersedia' => 'Tersedia',
            'nonaktif' => 'Nonaktif',
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'dikonfirmasi' => 'Dikonfirmasi',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
        ][$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}

if (! function_exists('kode_tiket')) {
    /**
     * Format an official Pokdarwis ticket code: HSG-00042.
     */
    function kode_tiket(int|string|null $id): string
    {
        return 'HSG-'.str_pad((string) ($id ?? 0), 5, '0', STR_PAD_LEFT);
    }
}

