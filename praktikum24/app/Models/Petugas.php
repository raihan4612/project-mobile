<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    protected $table = 'petugas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'kode_petugas',
        'nama',
        'email',
        'no_hp',
        'jabatan',
        'status',
    ];

    // Relasi ke peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'petugas_id');
    }
}