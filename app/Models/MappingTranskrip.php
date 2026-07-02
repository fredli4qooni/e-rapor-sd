<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MappingTranskrip extends Model
{
    protected $fillable = [
        'mata_pelajaran_id', 'tingkat', 'kurikulum', 'nama_lokal', 'kelompok', 'no_urut'
    ];

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }
}
