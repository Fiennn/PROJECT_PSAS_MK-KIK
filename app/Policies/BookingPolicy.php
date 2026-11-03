<?php

namespace App\Policies;

use App\Enums\StatusBooking;
use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function view(User $user, Booking $booking): bool
    {
        if ($user->role->value === 'admin') {
            return true;
        }

        if ($user->role->value === 'wisatawan') {
            return $booking->id_wisatawan === $user->wisatawan?->id_wisatawan;
        }

        if ($user->role->value === 'pemilik') {
            return $booking->kamar->homestay->id_pemilik === $user->pemilikHomestay?->id_pemilik;
        }

        return false;
    }

    public function cancel(User $user, Booking $booking): bool
    {
        return $user->role->value === 'wisatawan'
            && $booking->id_wisatawan === $user->wisatawan?->id_wisatawan
            && $booking->status === StatusBooking::MenungguPembayaran;
    }

    public function uploadPayment(User $user, Booking $booking): bool
    {
        return $user->role->value === 'wisatawan'
            && $booking->id_wisatawan === $user->wisatawan?->id_wisatawan
            && $booking->status === StatusBooking::MenungguPembayaran;
    }

    public function review(User $user, Booking $booking): bool
    {
        return $user->role->value === 'wisatawan'
            && $booking->id_wisatawan === $user->wisatawan?->id_wisatawan
            && $booking->status === StatusBooking::Selesai
            && ! $booking->ulasan()->exists();
    }

    public function confirm(User $user, Booking $booking): bool
    {
        return $user->role->value === 'pemilik'
            && $booking->kamar->homestay->id_pemilik === $user->pemilikHomestay?->id_pemilik
            && $booking->status === StatusBooking::MenungguVerifikasi
            && $booking->pembayaranTerverifikasi();
    }

    public function reject(User $user, Booking $booking): bool
    {
        return $user->role->value === 'pemilik'
            && $booking->kamar->homestay->id_pemilik === $user->pemilikHomestay?->id_pemilik
            && in_array($booking->status, [StatusBooking::MenungguPembayaran, StatusBooking::MenungguVerifikasi], true);
    }

    public function complete(User $user, Booking $booking): bool
    {
        return $user->role->value === 'pemilik'
            && $booking->kamar->homestay->id_pemilik === $user->pemilikHomestay?->id_pemilik
            && $booking->status === StatusBooking::Dikonfirmasi
            && $booking->tgl_checkout->lessThanOrEqualTo(today());
    }
}
