<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';

    protected $primaryKey = 'id_ulasan';

    public $timestamps = false;

    protected $fillable = [
        'id_booking',
        'rating',
        'komentar',
        'tgl_ulasan',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'tgl_ulasan' => 'date',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking', 'id_booking');
    }
}
