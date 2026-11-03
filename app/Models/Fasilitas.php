<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'fasilitas';

    protected $primaryKey = 'id_fasilitas';

    public $timestamps = false;

    protected $fillable = [
        'nama_fasilitas',
    ];

    public function homestay()
    {
        return $this->belongsToMany(
            Homestay::class,
            'homestay_fasilitas',
            'id_fasilitas',
            'id_homestay'
        )->withPivot('id_homestay_fasilitas');
    }
}
