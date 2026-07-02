<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5Nilai extends Model
{
    protected $fillable = [
        'siswa_id',
        'p5_proyek_id',
        'p5_sub_elemen_id',
        'capaian'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function proyek()
    {
        return $this->belongsTo(P5Proyek::class, 'p5_proyek_id');
    }

    public function subElemen()
    {
        return $this->belongsTo(P5SubElemen::class, 'p5_sub_elemen_id');
    }
}
