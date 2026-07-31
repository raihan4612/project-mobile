<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TingkatPrestasi extends Model
{
    protected $table = 'tingkat_prestasi';
    protected $fillable = ['nama_tingkat'];

    public function prestasi()
    {
        return $this->hasMany(Prestasi::class, 'tingkat_id');
    }
}
