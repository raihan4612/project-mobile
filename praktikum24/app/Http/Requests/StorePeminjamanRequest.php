<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePeminjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mahasiswa_id'          => 'required|exists:mhs,id',
            'buku_id'               => 'required|exists:buku,id',
            'petugas_id'            => 'nullable|exists:petugas,id',
            'tanggal_pinjam'        => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'catatan'               => 'nullable|string',
        ];
    }
}
