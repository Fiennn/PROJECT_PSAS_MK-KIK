<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $key = Str::transliterate(Str::lower($validated['username'])).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'username' => "Terlalu banyak percobaan masuk. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        $user = User::where('username', $validated['username'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            RateLimiter::hit($key, 60);

            throw ValidationException::withMessages([
                'username' => 'Username atau password salah.',
            ]);
        }

        if (! $user->status_aktif) {
            RateLimiter::hit($key, 60);

            throw ValidationException::withMessages([
                'username' => 'Akun Anda sudah dinonaktifkan. Hubungi administrator desa wisata.',
            ]);
        }

        RateLimiter::clear($key);
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended($user->dashboardUrl())->with('success', 'Selamat datang kembali, '.$user->displayName().'.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda berhasil keluar.');
    }
}
