<?php

namespace App\Policies;

use App\Models\Kamar;
use App\Models\User;

class KamarPolicy
{
    public function update(User $user, Kamar $kamar): bool
    {
        return $user->role->value === 'pemilik'
            && $kamar->homestay->id_pemilik === $user->pemilikHomestay?->id_pemilik;
    }

    public function delete(User $user, Kamar $kamar): bool
    {
        return $this->update($user, $kamar)
            && ! $kamar->booking()->exists();
    }
}
