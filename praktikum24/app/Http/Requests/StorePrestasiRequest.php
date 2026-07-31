<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrestasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mahasiswa_id'  => 'required|exists:mhs,id',
            'jenis_id'      => 'required|exists:jenis_prestasi,id',
            'tingkat_id'    => 'required|exists:tingkat_prestasi,id',
            'nama_lomba'    => 'required|string|max:200',
            'penyelenggara' => 'required|string|max:150',
            'tanggal'       => 'required|date',
            'juara'         => 'nullable|string|max:50',
            'sertifikat'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }
}
