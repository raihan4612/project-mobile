<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramBeasiswa extends Model
{
    protected $table = 'program_beasiswa';
    protected $fillable = [
        'nama_beasiswa',
        'penyelenggara',
        'tahun_akademik',
        'jumlah_dana',
    ];

    protected $casts = [
        'jumlah_dana' => 'decimal:2',
    ];

    public function beasiswa()
    {
        return $this->hasMany(Beasiswa::class, 'program_beasiswa_id');
    }
}
