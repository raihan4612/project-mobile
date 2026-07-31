<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePeminjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mahasiswa_id'            => 'sometimes|exists:mhs,id',
            'buku_id'                 => 'sometimes|exists:buku,id',
            'petugas_id'              => 'nullable|exists:petugas,id',
            'tanggal_pinjam'          => 'sometimes|date',
            'tanggal_kembali_rencana' => 'sometimes|date|after:tanggal_pinjam',
            'tanggal_kembali_aktual'  => 'nullable|date',
            'status'                  => 'sometimes|in:Dipinjam,Dikembalikan,Terlambat',
            'denda'                   => 'sometimes|integer|min:0',
            'catatan'                 => 'nullable|string',
        ];
    }
}
