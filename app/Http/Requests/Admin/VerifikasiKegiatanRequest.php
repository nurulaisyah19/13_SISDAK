<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VerifikasiKegiatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'status'  => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string|max:500',
        ];
    }
}
