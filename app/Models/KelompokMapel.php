<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelompokMapel extends Model
{
    protected $fillable = ['nama_kelompok', 'jenis_kelompok'];

    public function mapels()
    {
        return $this->hasMany(MataPelajaran::class, 'kelompok_mapel_id')->orderBy('nomor_urut');
    }
}
