<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePetugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_petugas' => 'required|string|max:255|unique:petugas,kode_petugas',
            'nama'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:petugas,email',
            'no_hp'        => 'nullable|string|max:20',
            'jabatan'      => 'required|string|max:255',
            'status'       => 'required|in:Aktif,Nonaktif',
        ];
    }
}
