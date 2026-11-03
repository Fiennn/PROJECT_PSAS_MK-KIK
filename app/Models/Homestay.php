<?php

namespace App\Models;

use App\Enums\StatusVerifikasi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homestay extends Model
{
    use HasFactory;

    protected $table = 'homestay';

    protected $primaryKey = 'id_homestay';

    public $timestamps = false;

    protected $fillable = [
        'id_pemilik',
        'nama_homestay',
        'deskripsi',
        'alamat',
        'status_verifikasi',
    ];

    protected function casts(): array
    {
        return [
            'status_verifikasi' => StatusVerifikasi::class,
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'id_homestay';
    }

    public function scopeDisetujui(Builder $query): Builder
    {
        return $query->where('status_verifikasi', StatusVerifikasi::Disetujui->value);
    }

    public function pemilik()
    {
        return $this->belongsTo(PemilikHomestay::class, 'id_pemilik', 'id_pemilik');
    }

    public function kamar()
    {
        return $this->hasMany(Kamar::class, 'id_homestay', 'id_homestay');
    }

    public function paket()
    {
        return $this->hasMany(PaketWisata::class, 'id_homestay', 'id_homestay');
    }

    public function fasilitas()
    {
        return $this->belongsToMany(
            Fasilitas::class,
            'homestay_fasilitas',
            'id_homestay',
            'id_fasilitas'
        )->withPivot('id_homestay_fasilitas');
    }

    public function booking()
    {
        return $this->hasManyThrough(
            Booking::class,
            Kamar::class,
            'id_homestay',
            'id_kamar',
            'id_homestay',
            'id_kamar'
        );
    }
}
