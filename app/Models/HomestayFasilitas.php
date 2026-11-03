<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomestayFasilitas extends Model
{
    use HasFactory;

    protected $table = 'homestay_fasilitas';

    protected $primaryKey = 'id_homestay_fasilitas';

    public $timestamps = false;

    protected $fillable = [
        'id_homestay',
        'id_fasilitas',
    ];

    public function homestay()
    {
        return $this->belongsTo(Homestay::class, 'id_homestay', 'id_homestay');
    }

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'id_fasilitas', 'id_fasilitas');
    }
}
