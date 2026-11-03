<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketWisata extends Model
{
    use HasFactory;

    protected $table = 'paket_wisata';

    protected $primaryKey = 'id_paket';

    public $timestamps = false;

    protected $fillable = [
        'id_homestay',
        'nama_paket',
        'deskripsi',
        'harga',
        'durasi',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'id_paket';
    }

    public function homestay()
    {
        return $this->belongsTo(Homestay::class, 'id_homestay', 'id_homestay');
    }

    public function bookingPaket()
    {
        return $this->hasMany(BookingPaket::class, 'id_paket', 'id_paket');
    }
}
