<?php

namespace App\Policies;

use App\Models\Homestay;
use App\Models\User;

class HomestayPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->role->value === 'admin') {
            return true;
        }

        return null;
    }

    public function update(User $user, Homestay $homestay): bool
    {
        return $user->role->value === 'pemilik'
            && $homestay->id_pemilik === $user->pemilikHomestay?->id_pemilik;
    }

    public function manageRooms(User $user, Homestay $homestay): bool
    {
        return $this->update($user, $homestay);
    }

    public function managePackages(User $user, Homestay $homestay): bool
    {
        return $this->update($user, $homestay);
    }

    public function delete(User $user, Homestay $homestay): bool
    {
        return $this->update($user, $homestay)
            && ! $homestay->booking()->exists();
    }
}
