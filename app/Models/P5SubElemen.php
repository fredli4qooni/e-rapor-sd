<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5SubElemen extends Model
{
    protected $fillable = [
        'p5_elemen_id', 
        'nama_sub_elemen', 
        'capaian_fase_a', 
        'capaian_fase_b', 
        'capaian_fase_c'
    ];

    public function elemen()
    {
        return $this->belongsTo(P5Elemen::class, 'p5_elemen_id');
    }

    public function proyeks()
    {
        return $this->belongsToMany(P5Proyek::class, 'p5_proyek_sub_elemens', 'p5_sub_elemen_id', 'p5_proyek_id');
    }
}
