<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPrestasi extends Model
{
    protected $table = 'jenis_prestasi';
    protected $fillable = ['nama_jenis'];

    public function prestasi()
    {
        return $this->hasMany(Prestasi::class, 'jenis_id');
    }
}
