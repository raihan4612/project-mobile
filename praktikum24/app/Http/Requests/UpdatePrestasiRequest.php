<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePrestasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mahasiswa_id'  => 'sometimes|exists:mhs,id',
            'jenis_id'      => 'sometimes|exists:jenis_prestasi,id',
            'tingkat_id'    => 'sometimes|exists:tingkat_prestasi,id',
            'nama_lomba'    => 'sometimes|string|max:200',
            'penyelenggara' => 'sometimes|string|max:150',
            'tanggal'       => 'sometimes|date',
            'juara'         => 'nullable|string|max:50',
            'sertifikat'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }
}
