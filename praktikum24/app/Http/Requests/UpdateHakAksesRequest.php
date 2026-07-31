<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHakAksesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('hak_akses');

        return [
            'nama_role'  => ['sometimes', 'string', 'max:50', Rule::unique('hak_akses', 'nama_role')->ignore($id)],
            'level'      => 'sometimes|integer|in:1,2,3,4',
            'deskripsi'  => 'nullable|string',
            'can_create' => 'boolean',
            'can_read'   => 'boolean',
            'can_update' => 'boolean',
            'can_delete' => 'boolean',
            'can_export' => 'boolean',
            'is_active'  => 'boolean',
        ];
    }
}
