<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTingkatPrestasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('tingkat_prestasi');

        return [
            'nama_tingkat' => ['sometimes', 'string', 'max:50', Rule::unique('tingkat_prestasi', 'nama_tingkat')->ignore($id)],
        ];
    }
}
