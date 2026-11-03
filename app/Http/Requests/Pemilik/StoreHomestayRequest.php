<?php

namespace App\Http\Requests\Pemilik;

use Illuminate\Foundation\Http\FormRequest;

class StoreHomestayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role->value === 'pemilik';
    }

    public function rules(): array
    {
        return [
            'nama_homestay' => ['required', 'string', 'max:100'],
            'deskripsi' => ['required', 'string', 'min:20', 'max:5000'],
            'alamat' => ['required', 'string', 'min:10', 'max:500'],
            'fasilitas' => ['nullable', 'array'],
            'fasilitas.*' => ['integer', 'exists:fasilitas,id_fasilitas'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_homestay.required' => 'Nama homestay wajib diisi.',
            'nama_homestay.max' => 'Nama homestay maksimal 100 karakter.',
            'deskripsi.required' => 'Deskripsi homestay wajib diisi.',
            'deskripsi.min' => 'Ceritakan homestay Anda minimal 20 karakter.',
            'deskripsi.max' => 'Deskripsi maksimal 5000 karakter.',
            'alamat.required' => 'Alamat homestay wajib diisi.',
            'alamat.min' => 'Alamat minimal 10 karakter.',
            'alamat.max' => 'Alamat maksimal 500 karakter.',
            'fasilitas.*.exists' => 'Fasilitas yang dipilih tidak valid.',
        ];
    }
}
