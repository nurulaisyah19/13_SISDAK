<?php

namespace App\Http\Requests\Ormawa;

use Illuminate\Foundation\Http\FormRequest;

class StoreDokumenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isOrmawa();
    }

    public function rules(): array
    {
        return [
            'nama_dokumen' => ['required', 'string', 'max:255'],
            'file'         => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:2048'],
            // id_kegiatan diambil dari route parameter, bukan dari form body
        ];
    }

    public function messages(): array
    {
        return [
            'nama_dokumen.required' => 'Nama dokumen wajib diisi.',
            'file.required'         => 'File dokumen wajib diupload.',
            'file.mimes'            => 'Format file harus pdf/doc/docx/jpg/png.',
            'file.max'              => 'Ukuran file maksimal 2MB.',
        ];
    }
}
