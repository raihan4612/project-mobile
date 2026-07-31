<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePetugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('petugas');

        return [
            'kode_petugas' => ['sometimes', 'string', 'max:255', Rule::unique('petugas', 'kode_petugas')->ignore($id)],
            'nama'         => 'sometimes|string|max:255',
            'email'        => ['sometimes', 'email', 'max:255', Rule::unique('petugas', 'email')->ignore($id)],
            'no_hp'        => 'nullable|string|max:20',
            'jabatan'      => 'sometimes|string|max:255',
            'status'       => 'sometimes|in:Aktif,Nonaktif',
        ];
    }
}
