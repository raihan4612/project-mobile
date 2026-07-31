<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nim'            => 'required|string|max:20|unique:mhs,nim',
            'nama'           => 'required|string|max:100',
            'jenis_kelamin'  => 'required|in:L,P',
            'tanggal_lahir'  => 'required|date',
            'tempat_lahir'   => 'required|string|max:100',
            'alamat'         => 'required|string',
            'kota'           => 'required|string|max:100',
            'provinsi'       => 'required|string|max:100',
            'kode_pos'       => 'nullable|string|max:10',
            'no_hp'          => 'required|string|max:20',
            'email'          => 'required|email|max:100|unique:mhs,email',
            'prodi'          => 'required|string|max:100',
            'fakultas'       => 'required|string|max:100',
            'semester'       => 'required|integer|min:1|max:14',
            'ipk'            => 'nullable|numeric|min:0|max:4',
            'tahun_masuk'    => 'required|string|size:4',
            'status'         => 'required|in:Aktif,Cuti,Lulus,Dropout',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
