<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHakAksesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_role'  => 'required|string|max:50|unique:hak_akses,nama_role',
            'level'      => 'required|integer|in:1,2,3,4',
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
