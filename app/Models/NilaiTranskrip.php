<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiTranskrip extends Model
{
    protected $fillable = [
        'siswa_id', 'mata_pelajaran_id', 'nilai'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }
}
