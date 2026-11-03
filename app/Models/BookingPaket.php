<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPaket extends Model
{
    use HasFactory;

    protected $table = 'booking_paket';

    protected $primaryKey = 'id_booking_paket';

    public $timestamps = false;

    protected $fillable = [
        'id_booking',
        'id_paket',
        'jumlah_peserta',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_peserta' => 'integer',
            'subtotal' => 'decimal:2',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking', 'id_booking');
    }

    public function paket()
    {
        return $this->belongsTo(PaketWisata::class, 'id_paket', 'id_paket');
    }
}
