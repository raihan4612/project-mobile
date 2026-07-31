<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBeasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mahasiswa_id'      => 'sometimes|exists:mhs,id',
            'nama_beasiswa'     => 'sometimes|string|max:200',
            'penyelenggara'     => 'sometimes|string|max:150',
            'tahun_akademik'    => 'sometimes|string|max:20',
            'jumlah_dana'       => 'sometimes|numeric|min:0',
            'tanggal_pengajuan' => 'sometimes|date',
            'keterangan'        => 'nullable|string',
        ];
    }
}
