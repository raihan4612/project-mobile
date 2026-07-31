<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beasiswa extends Model
{
    protected $table = 'beasiswa';
    protected $fillable = [
        'program_beasiswa_id',
        'mahasiswa_id',
        'status',
        'tanggal_pengajuan',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function programBeasiswa()
    {
        return $this->belongsTo(ProgramBeasiswa::class, 'program_beasiswa_id');
    }
}
