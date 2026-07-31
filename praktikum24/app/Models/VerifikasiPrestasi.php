<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikasiPrestasi extends Model
{
    protected $table = 'verifikasi_prestasi';
    protected $fillable = ['prestasi_id', 'admin_id', 'tanggal_verifikasi', 'catatan'];
    protected $casts = ['tanggal_verifikasi' => 'date'];

    public function prestasi()
    {
        return $this->belongsTo(Prestasi::class, 'prestasi_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
