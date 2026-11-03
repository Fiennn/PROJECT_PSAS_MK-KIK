<?php

namespace App\Http\Requests\Pemilik;

use App\Enums\StatusKamar;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKamarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role->value === 'pemilik';
    }

    public function rules(): array
    {
        return [
            'nama_kamar' => ['required', 'string', 'max:100'],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:20'],
            'harga_per_malam' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'status' => ['required', Rule::in(array_column(StatusKamar::cases(), 'value'))],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kamar.required' => 'Nama kamar wajib diisi.',
            'kapasitas.required' => 'Kapasitas kamar wajib diisi.',
            'kapasitas.min' => 'Kapasitas minimal 1 tamu.',
            'kapasitas.max' => 'Kapasitas maksimal 20 tamu.',
            'harga_per_malam.required' => 'Harga per malam wajib diisi.',
            'harga_per_malam.numeric' => 'Harga per malam harus berupa angka.',
            'harga_per_malam.min' => 'Harga per malam tidak boleh negatif.',
            'status.required' => 'Pilih status kamar.',
            'status.in' => 'Status kamar tidak valid.',
        ];
    }
}
