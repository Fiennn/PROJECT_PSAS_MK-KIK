<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\PemilikHomestay;
use App\Models\User;
use App\Models\Wisatawan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => $validated['role'],
                'status_aktif' => true,
            ]);

            if ($validated['role'] === Role::Wisatawan->value) {
                Wisatawan::create([
                    'id_user' => $user->id_user,
                    'nama_wisatawan' => $validated['nama_wisatawan'],
                    'no_hp' => $validated['no_hp'],
                    'asal_kota' => $validated['asal_kota'],
                ]);
            } else {
                PemilikHomestay::create([
                    'id_user' => $user->id_user,
                    'nama_pemilik' => $validated['nama_pemilik'],
                    'no_hp' => $validated['no_hp'],
                    'alamat' => $validated['alamat'],
                ]);
            }

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->to($user->dashboardUrl())
            ->with('success', 'Akun berhasil dibuat. Selamat datang di HomeStay Go!');
    }
}
