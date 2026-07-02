<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_mapel',
        'nama_singkat',
        'is_transkrip',
        'is_lokal',
        'mapel_referensi_id',
        'kelompok_mapel_id',
        'nomor_urut',
    ];

    public function kelompok()
    {
        return $this->belongsTo(KelompokMapel::class, 'kelompok_mapel_id');
    }
}
