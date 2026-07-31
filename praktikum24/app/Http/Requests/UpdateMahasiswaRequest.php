<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('mahasiswa');

        return [
            'nim'            => ['sometimes', 'string', 'max:20', Rule::unique('mhs', 'nim')->ignore($id)],
            'nama'           => 'sometimes|string|max:100',
            'jenis_kelamin'  => 'sometimes|in:L,P',
            'tanggal_lahir'  => 'sometimes|date',
            'tempat_lahir'   => 'sometimes|string|max:100',
            'alamat'         => 'sometimes|string',
            'kota'           => 'sometimes|string|max:100',
            'provinsi'       => 'sometimes|string|max:100',
            'kode_pos'       => 'nullable|string|max:10',
            'no_hp'          => 'sometimes|string|max:20',
            'email'          => ['sometimes', 'email', 'max:100', Rule::unique('mhs', 'email')->ignore($id)],
            'prodi'          => 'sometimes|string|max:100',
            'fakultas'       => 'sometimes|string|max:100',
            'semester'       => 'sometimes|integer|min:1|max:14',
            'ipk'            => 'nullable|numeric|min:0|max:4',
            'tahun_masuk'    => 'sometimes|string|size:4',
            'status'         => 'sometimes|in:Aktif,Cuti,Lulus,Dropout',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
