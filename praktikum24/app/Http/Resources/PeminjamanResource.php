<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeminjamanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'kode_peminjaman'        => $this->kode_peminjaman,
            'mahasiswa'              => new MahasiswaResource($this->whenLoaded('mahasiswa')),
            'buku'                   => new BukuResource($this->whenLoaded('buku')),
            'petugas'                => new PetugasResource($this->whenLoaded('petugas')),
            'tanggal_pinjam'         => $this->tanggal_pinjam,
            'tanggal_kembali_rencana'=> $this->tanggal_kembali_rencana,
            'tanggal_kembali_aktual' => $this->tanggal_kembali_aktual,
            'status'                 => $this->status,
            'denda'                  => $this->denda,
            'catatan'                => $this->catatan,
            'created_at'             => $this->created_at,
            'updated_at'             => $this->updated_at,
        ];
    }
}
