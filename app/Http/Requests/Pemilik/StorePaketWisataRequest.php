<?php

namespace App\Http\Requests\Pemilik;

use Illuminate\Foundation\Http\FormRequest;

class StorePaketWisataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role->value === 'pemilik';
    }

    public function rules(): array
    {
        return [
            'nama_paket' => ['required', 'string', 'max:100'],
            'deskripsi' => ['required', 'string', 'min:10', 'max:2000'],
            'harga' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'durasi' => ['required', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_paket.required' => 'Nama paket wajib diisi.',
            'deskripsi.required' => 'Deskripsi paket wajib diisi.',
            'deskripsi.min' => 'Deskripsi paket minimal 10 karakter.',
            'harga.required' => 'Harga paket wajib diisi.',
            'harga.numeric' => 'Harga paket harus berupa angka.',
            'harga.min' => 'Harga paket tidak boleh negatif.',
            'durasi.required' => 'Durasi paket wajib diisi.',
            'durasi.max' => 'Durasi maksimal 50 karakter.',
        ];
    }
}
