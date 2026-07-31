<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJenisPrestasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('jenis_prestasi');

        return [
            'nama_jenis' => ['sometimes', 'string', 'max:50', Rule::unique('jenis_prestasi', 'nama_jenis')->ignore($id)],
        ];
    }
}
