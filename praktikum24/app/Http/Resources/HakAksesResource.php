<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HakAksesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'nama_role'  => $this->nama_role,
            'level'      => $this->level,
            'deskripsi'  => $this->deskripsi,
            'can_create' => $this->can_create,
            'can_read'   => $this->can_read,
            'can_update' => $this->can_update,
            'can_delete' => $this->can_delete,
            'can_export' => $this->can_export,
            'is_active'  => $this->is_active,
            'badge'      => $this->level_badge,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
