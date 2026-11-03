<?php

namespace App\Http\Requests\Wisatawan;

use Illuminate\Foundation\Http\FormRequest;

class StoreUlasanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role->value === 'wisatawan';
    }

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'between:1,5'],
            'komentar' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'Pilih rating untuk homestay ini.',
            'rating.between' => 'Rating harus bernilai antara 1 sampai 5.',
            'komentar.required' => 'Ceritakan pengalaman Anda.',
            'komentar.min' => 'Komentar minimal 10 karakter.',
            'komentar.max' => 'Komentar maksimal 2000 karakter.',
        ];
    }
}
