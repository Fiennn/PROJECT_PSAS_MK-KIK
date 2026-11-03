@props(['status' => null, 'color' => null])

@php
    $map = [
        'menunggu_pembayaran' => ['amber', 'Menunggu Pembayaran'],
        'menunggu_verifikasi' => ['sky', 'Menunggu Verifikasi'],
        'dikonfirmasi' => ['forest', 'Dikonfirmasi'],
        'selesai' => ['emerald', 'Selesai'],
        'ditolak' => ['red', 'Ditolak'],
        'dibatalkan' => ['stone', 'Dibatalkan'],
        'menunggu' => ['amber', 'Menunggu'],
        'disetujui' => ['forest', 'Disetujui'],
        'terverifikasi' => ['emerald', 'Terverifikasi'],
        'tersedia' => ['forest', 'Tersedia'],
        'nonaktif' => ['stone', 'Nonaktif'],
    ];
    $key = $color ?? $status;
    [$tone, $label] = $map[$key] ?? ['stone', status_label((string) $status)];

    $tones = [
        'amber' => 'bg-amber-50 text-amber-800 ring-amber-200',
        'sky' => 'bg-sky-50 text-sky-800 ring-sky-200',
        'forest' => 'bg-forest-50 text-forest-800 ring-forest-200',
        'emerald' => 'bg-emerald-50 text-emerald-800 ring-emerald-200',
        'red' => 'bg-red-50 text-red-800 ring-red-200',
        'stone' => 'bg-stone-100 text-stone-700 ring-stone-200',
        'clay' => 'bg-clay-50 text-clay-800 ring-clay-200',
    ];
    $toneClass = $tones[$tone] ?? $tones['stone'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset $toneClass"]) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70" aria-hidden="true"></span>
    {{ $label }}
</span>
