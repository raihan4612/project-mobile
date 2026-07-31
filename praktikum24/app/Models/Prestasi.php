<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    protected $table = 'prestasi';
    protected $fillable = [
        'mahasiswa_id', 'jenis_id', 'tingkat_id',
        'nama_lomba', 'penyelenggara', 'tanggal',
        'juara', 'sertifikat', 'status_verifikasi',
    ];

    protected $casts = ['tanggal' => 'date'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function jenis()
    {
        return $this->belongsTo(JenisPrestasi::class, 'jenis_id');
    }

    public function tingkat()
    {
        return $this->belongsTo(TingkatPrestasi::class, 'tingkat_id');
    }

    public function verifikasi()
    {
        return $this->hasOne(VerifikasiPrestasi::class, 'prestasi_id');
    }
}
