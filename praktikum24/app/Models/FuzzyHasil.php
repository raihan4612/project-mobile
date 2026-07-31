<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuzzyHasil extends Model
{
    protected $table = 'fuzzy_hasil';

    protected $fillable = [
        'mahasiswa_id',
        'nilai_fuzzy',
        'hasil_rekomendasi',
    ];

    protected $casts = [
        'nilai_fuzzy' => 'decimal:2',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }
}
