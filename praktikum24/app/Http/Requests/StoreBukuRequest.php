<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_buku'     => 'required|string|max:20|unique:buku,kode_buku',
            'judul'         => 'required|string|max:200',
            'pengarang'     => 'required|string|max:100',
            'penerbit'      => 'required|string|max:100',
            'tahun_terbit'  => 'required|string|size:4',
            'kategori'      => 'required|string|max:50',
            'jumlah_stok'   => 'required|integer|min:0',
            'deskripsi'     => 'nullable|string',
            'status'        => 'nullable|in:Tersedia,Habis',
        ];
    }
}
