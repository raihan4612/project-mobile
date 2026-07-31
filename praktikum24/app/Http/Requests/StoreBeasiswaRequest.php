<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBeasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mahasiswa_id'      => 'required|exists:mhs,id',
            'nama_beasiswa'     => 'required|string|max:200',
            'penyelenggara'     => 'required|string|max:150',
            'tahun_akademik'    => 'required|string|max:20',
            'jumlah_dana'       => 'required|numeric|min:0',
            'tanggal_pengajuan' => 'required|date',
            'keterangan'        => 'nullable|string',
        ];
    }
}
