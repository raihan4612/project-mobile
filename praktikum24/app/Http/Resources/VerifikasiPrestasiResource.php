<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VerifikasiPrestasiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'prestasi_id'       => $this->prestasi_id,
            'admin'             => $this->whenLoaded('admin', fn() => [
                'id'   => $this->admin->id,
                'nama' => $this->admin->nama,
            ]),
            'tanggal_verifikasi'=> $this->tanggal_verifikasi,
            'catatan'           => $this->catatan,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
