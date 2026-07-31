<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrestasiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'mahasiswa'        => new MahasiswaResource($this->whenLoaded('mahasiswa')),
            'jenis'            => new JenisPrestasiResource($this->whenLoaded('jenis')),
            'tingkat'          => new TingkatPrestasiResource($this->whenLoaded('tingkat')),
            'nama_lomba'       => $this->nama_lomba,
            'penyelenggara'    => $this->penyelenggara,
            'tanggal'          => $this->tanggal,
            'juara'            => $this->juara,
            'sertifikat'       => $this->sertifikat,
            'status_verifikasi'=> $this->status_verifikasi,
            'verifikasi'       => new VerifikasiPrestasiResource($this->whenLoaded('verifikasi')),
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}
