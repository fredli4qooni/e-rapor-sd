<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5Catatan extends Model
{
    protected $fillable = [
        'siswa_id',
        'p5_proyek_id',
        'catatan'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function proyek()
    {
        return $this->belongsTo(P5Proyek::class, 'p5_proyek_id');
    }
}
