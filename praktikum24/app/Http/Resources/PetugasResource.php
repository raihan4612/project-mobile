<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetugasResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'kode_petugas'    => $this->kode_petugas,
            'nama'            => $this->nama,
            'email'           => $this->email,
            'no_hp'           => $this->no_hp,
            'jabatan'         => $this->jabatan,
            'status'          => $this->status,
            'peminjaman_count' => $this->whenCounted('peminjaman'),
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
