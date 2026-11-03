<?php

namespace App\Models;

use App\Enums\StatusBooking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking';

    protected $primaryKey = 'id_booking';

    public $timestamps = false;

    protected $fillable = [
        'id_wisatawan',
        'id_kamar',
        'tgl_booking',
        'tgl_checkin',
        'tgl_checkout',
        'jumlah_tamu',
        'total_harga',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tgl_booking' => 'date',
            'tgl_checkin' => 'date',
            'tgl_checkout' => 'date',
            'jumlah_tamu' => 'integer',
            'total_harga' => 'decimal:2',
            'status' => StatusBooking::class,
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'id_booking';
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->whereIn('status', StatusBooking::activeValues());
    }

    public function scopeOverlapping(Builder $query, string $checkin, string $checkout): Builder
    {
        return $query
            ->where('tgl_checkin', '<', $checkout)
            ->where('tgl_checkout', '>', $checkin);
    }

    public function wisatawan()
    {
        return $this->belongsTo(Wisatawan::class, 'id_wisatawan', 'id_wisatawan');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }

    public function paket()
    {
        return $this->belongsToMany(
            PaketWisata::class,
            'booking_paket',
            'id_booking',
            'id_paket'
        )->withPivot(['id_booking_paket', 'jumlah_peserta', 'subtotal']);
    }

    public function bookingPaket()
    {
        return $this->hasMany(BookingPaket::class, 'id_booking', 'id_booking');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_booking', 'id_booking');
    }

    public function ulasan()
    {
        return $this->hasOne(Ulasan::class, 'id_booking', 'id_booking');
    }

    public function hitungJumlahMalam(): int
    {
        if (! $this->tgl_checkin || ! $this->tgl_checkout) {
            return 0;
        }

        return (int) $this->tgl_checkin->diffInDays($this->tgl_checkout);
    }

    protected function jumlahMalam(): Attribute
    {
        return Attribute::get(fn () => $this->hitungJumlahMalam());
    }

    public function pembayaranTerverifikasi(): bool
    {
        return $this->pembayaran
            ->contains(fn (Pembayaran $pembayaran) => $pembayaran->status_verifikasi->value === 'terverifikasi');
    }

    public function sudahDiulas(): bool
    {
        return $this->ulasan()->exists();
    }
}
