<?php

namespace App\Http\Requests\Ormawa;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKegiatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        // tambahan pengecekan owner bisa di Policy/Middleware
        return auth()->check() && auth()->user()->isOrmawa();
    }

    public function rules(): array
    {
        return [
            'nama_kegiatan'   => ['required', 'string', 'max:255'],
            'tanggal_mulai'   => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'id_jenis'        => ['required', 'uuid', 'exists:jenis_kegiatans,id_jenis'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kegiatan.required'   => 'Nama kegiatan wajib diisi.',
            'tanggal_mulai.required'   => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'id_jenis.required'        => 'Jenis kegiatan wajib dipilih.',
            'id_jenis.exists'          => 'Jenis kegiatan tidak valid.',
        ];
    }
}
