<?php

namespace App\Models;

use App\Enums\StatusPembayaran;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $primaryKey = 'id_pembayaran';

    public $timestamps = false;

    protected $fillable = [
        'id_booking',
        'metode',
        'jumlah',
        'bukti_transfer',
        'tgl_bayar',
        'status_verifikasi',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'tgl_bayar' => 'datetime',
            'status_verifikasi' => StatusPembayaran::class,
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'id_pembayaran';
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking', 'id_booking');
    }
}
