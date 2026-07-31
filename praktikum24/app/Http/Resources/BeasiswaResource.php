<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BeasiswaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'mahasiswa'           => new MahasiswaResource($this->whenLoaded('mahasiswa')),
            'program_beasiswa'    => new ProgramBeasiswaResource($this->whenLoaded('programBeasiswa')),
            'status'              => $this->status,
            'tanggal_pengajuan'   => $this->tanggal_pengajuan,
            'keterangan'          => $this->keterangan,
            'fuzzy_hasil'         => $this->fuzzyHasilResource(),
            'created_at'          => $this->created_at,
            'updated_at'          => $this->updated_at,
        ];
    }

    private function fuzzyHasilResource()
    {
        if (!$this->relationLoaded('mahasiswa') || !$this->mahasiswa->relationLoaded('fuzzyHasil')) {
            return $this->when(false, null);
        }

        return $this->when(true, new FuzzyHasilResource($this->mahasiswa->fuzzyHasil));
    }
}
