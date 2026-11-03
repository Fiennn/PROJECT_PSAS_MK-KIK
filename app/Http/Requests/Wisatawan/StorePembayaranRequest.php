<?php

namespace App\Http\Requests\Wisatawan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePembayaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role->value === 'wisatawan';
    }

    public function rules(): array
    {
        return [
            'metode' => ['required', Rule::in(['Transfer Bank', 'QRIS', 'E-Wallet'])],
            'bukti_transfer' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'metode.required' => 'Pilih metode pembayaran.',
            'metode.in' => 'Metode pembayaran tidak valid.',
            'bukti_transfer.required' => 'Bukti transfer wajib diunggah.',
            'bukti_transfer.file' => 'Bukti transfer harus berupa file.',
            'bukti_transfer.mimes' => 'Bukti transfer harus berupa JPG, PNG, atau PDF.',
            'bukti_transfer.max' => 'Ukuran bukti transfer maksimal 2 MB.',
        ];
    }
}
