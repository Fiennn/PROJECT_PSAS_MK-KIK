<?php

namespace App\Policies;

use App\Models\Pembayaran;
use App\Models\User;

class PembayaranPolicy
{
    public function viewProof(User $user, Pembayaran $pembayaran): bool
    {
        if ($user->role->value === 'admin') {
            return true;
        }

        return $user->role->value === 'wisatawan'
            && $pembayaran->booking->id_wisatawan === $user->wisatawan?->id_wisatawan;
    }
}
