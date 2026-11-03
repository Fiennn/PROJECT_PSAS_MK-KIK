<?php

namespace App\Http\Requests\Wisatawan;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role->value === 'wisatawan';
    }

    public function rules(): array
    {
        return [
            'id_kamar' => ['required', 'integer', 'exists:kamar,id_kamar'],
            'tgl_checkin' => ['required', 'date', 'after_or_equal:today'],
            'tgl_checkout' => ['required', 'date', 'after:tgl_checkin'],
            'jumlah_tamu' => ['required', 'integer', 'min:1', 'max:20'],
            'paket' => ['nullable', 'array'],
            'paket.*' => ['integer', 'min:1', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_kamar.required' => 'Kamar yang dipilih tidak valid.',
            'id_kamar.exists' => 'Kamar yang dipilih tidak ditemukan.',
            'tgl_checkin.required' => 'Tanggal check-in wajib diisi.',
            'tgl_checkin.after_or_equal' => 'Tanggal check-in tidak boleh di masa lalu.',
            'tgl_checkout.required' => 'Tanggal check-out wajib diisi.',
            'tgl_checkout.after' => 'Tanggal check-out harus setelah tanggal check-in.',
            'jumlah_tamu.required' => 'Jumlah tamu wajib diisi.',
            'jumlah_tamu.min' => 'Jumlah tamu minimal 1 orang.',
            'jumlah_tamu.max' => 'Jumlah tamu terlalu banyak.',
            'paket.*.integer' => 'Jumlah peserta paket harus berupa angka.',
            'paket.*.min' => 'Jumlah peserta paket minimal 1 orang.',
            'paket.*.max' => 'Jumlah peserta paket terlalu banyak.',
        ];
    }
}
