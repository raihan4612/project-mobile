<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MahasiswaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'nim'            => $this->nim,
            'nama'           => $this->nama,
            'jenis_kelamin'  => $this->jenis_kelamin,
            'tanggal_lahir'  => $this->tanggal_lahir,
            'tempat_lahir'   => $this->tempat_lahir,
            'alamat'         => $this->alamat,
            'kota'           => $this->kota,
            'provinsi'       => $this->provinsi,
            'kode_pos'       => $this->kode_pos,
            'no_hp'          => $this->no_hp,
            'email'          => $this->email,
            'prodi'          => $this->prodi,
            'fakultas'       => $this->fakultas,
            'semester'       => $this->semester,
            'tahun_masuk'    => $this->tahun_masuk,
            'status'         => $this->status,
            'ipk'            => $this->ipk,
            'foto'           => $this->foto,
            'peminjaman_count' => $this->whenCounted('peminjaman'),
            'prestasi_count'   => $this->whenCounted('prestasi'),
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
