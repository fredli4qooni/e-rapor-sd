<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5Kelompok extends Model
{
    protected $fillable = [
        'sekolah_id',
        'semester_id',
        'nama_kelompok',
        'tingkat_pendidikan',
        'fase',
        'guru_id'
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function koordinator()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function siswas()
    {
        return $this->belongsToMany(Siswa::class, 'p5_kelompok_siswas', 'p5_kelompok_id', 'siswa_id');
    }

    public function proyeks()
    {
        return $this->belongsToMany(P5Proyek::class, 'p5_kelompok_proyeks', 'p5_kelompok_id', 'p5_proyek_id');
    }
}
