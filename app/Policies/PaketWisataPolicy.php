<?php

namespace App\Policies;

use App\Models\PaketWisata;
use App\Models\User;

class PaketWisataPolicy
{
    public function update(User $user, PaketWisata $paket): bool
    {
        return $user->role->value === 'pemilik'
            && $paket->homestay->id_pemilik === $user->pemilikHomestay?->id_pemilik;
    }

    public function delete(User $user, PaketWisata $paket): bool
    {
        return $this->update($user, $paket)
            && ! $paket->bookingPaket()->exists();
    }
}
