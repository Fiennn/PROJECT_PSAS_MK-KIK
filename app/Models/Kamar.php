<?php

namespace App\Models;

use App\Enums\StatusKamar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';

    protected $primaryKey = 'id_kamar';

    public $timestamps = false;

    protected $fillable = [
        'id_homestay',
        'nama_kamar',
        'kapasitas',
        'harga_per_malam',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'kapasitas' => 'integer',
            'harga_per_malam' => 'decimal:2',
            'status' => StatusKamar::class,
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'id_kamar';
    }

    public function scopeTersedia(Builder $query): Builder
    {
        return $query->where('status', StatusKamar::Tersedia->value);
    }

    public function homestay()
    {
        return $this->belongsTo(Homestay::class, 'id_homestay', 'id_homestay');
    }

    public function booking()
    {
        return $this->hasMany(Booking::class, 'id_kamar', 'id_kamar');
    }
}
