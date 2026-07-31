<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('buku');

        return [
            'kode_buku'     => ['sometimes', 'string', 'max:20', Rule::unique('buku', 'kode_buku')->ignore($id)],
            'judul'         => 'sometimes|string|max:200',
            'pengarang'     => 'sometimes|string|max:100',
            'penerbit'      => 'sometimes|string|max:100',
            'tahun_terbit'  => 'sometimes|string|size:4',
            'kategori'      => 'sometimes|string|max:50',
            'jumlah_stok'   => 'sometimes|integer|min:0',
            'deskripsi'     => 'nullable|string',
            'status'        => 'nullable|in:Tersedia,Habis',
        ];
    }
}
