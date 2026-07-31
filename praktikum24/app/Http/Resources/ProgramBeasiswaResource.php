<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramBeasiswaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'nama_beasiswa'   => $this->nama_beasiswa,
            'penyelenggara'   => $this->penyelenggara,
            'tahun_akademik'  => $this->tahun_akademik,
            'jumlah_dana'     => $this->jumlah_dana,
            'beasiswa_count'  => $this->whenCounted('beasiswa'),
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
