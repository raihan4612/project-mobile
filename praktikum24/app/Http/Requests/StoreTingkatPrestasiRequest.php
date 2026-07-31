<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTingkatPrestasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_tingkat' => 'required|string|max:50|unique:tingkat_prestasi,nama_tingkat',
        ];
    }
}
