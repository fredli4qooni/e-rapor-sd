<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5Proyek extends Model
{
    protected $fillable = [
        'sekolah_id',
        'semester_id',
        'p5_tema_id',
        'no_urut',
        'nama_proyek',
        'deskripsi',
        'fase'
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function tema()
    {
        return $this->belongsTo(P5Tema::class, 'p5_tema_id');
    }

    public function targetSubElemens()
    {
        return $this->belongsToMany(P5SubElemen::class, 'p5_proyek_sub_elemens', 'p5_proyek_id', 'p5_sub_elemen_id');
    }
}
