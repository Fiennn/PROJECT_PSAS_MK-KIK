<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $role = $this->input('role');

        return [
            'username' => ['required', 'string', 'min:4', 'max:50', 'alpha_dash', Rule::unique('users', 'username')],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['required', Rule::in(['wisatawan', 'pemilik'])],

            'nama_wisatawan' => ['exclude_unless:role,wisatawan', 'required', 'string', 'max:100'],
            'asal_kota' => ['exclude_unless:role,wisatawan', 'required', 'string', 'max:50'],

            'nama_pemilik' => ['exclude_unless:role,pemilik', 'required', 'string', 'max:100'],
            'alamat' => ['exclude_unless:role,pemilik', 'required', 'string', 'max:500'],

            'no_hp' => ['required', 'string', 'min:8', 'max:15', 'regex:/^[0-9+\-\s]+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, garis bawah, dan tanda hubung.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
            'role.required' => 'Pilih jenis akun.',
            'role.in' => 'Jenis akun tidak valid.',
            'nama_wisatawan.required' => 'Nama lengkap wajib diisi.',
            'nama_pemilik.required' => 'Nama pemilik wajib diisi.',
            'asal_kota.required' => 'Asal kota wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.regex' => 'Nomor HP hanya boleh berisi angka, spasi, tanda hubung, atau tanda plus.',
        ];
    }
}
