<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FuzzyHasilResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'mahasiswa_id'      => $this->mahasiswa_id,
            'nilai_fuzzy'       => $this->nilai_fuzzy,
            'hasil_rekomendasi' => $this->hasil_rekomendasi,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
