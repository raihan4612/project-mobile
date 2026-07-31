<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'kode_buku'       => $this->kode_buku,
            'judul'           => $this->judul,
            'pengarang'       => $this->pengarang,
            'penerbit'        => $this->penerbit,
            'tahun_terbit'    => $this->tahun_terbit,
            'kategori'        => $this->kategori,
            'jumlah_stok'     => $this->jumlah_stok,
            'jumlah_tersedia' => $this->jumlah_tersedia,
            'deskripsi'       => $this->deskripsi,
            'status'          => $this->status,
            'peminjaman_count' => $this->whenCounted('peminjaman'),
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
