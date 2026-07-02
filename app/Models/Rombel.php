<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    use HasFactory;
    protected $fillable = [
        'semester_id',
        'sekolah_id',
        'nama_rombel',
        'tingkat',
        'fase',
        'jenis_rombel',
        'wali_kelas_id',
        'kurikulum',
    ];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }

    public function siswas()
    {
        return $this->belongsToMany(Siswa::class, 'anggota_rombels');
    }

    public function pembelajarans()
    {
        return $this->hasMany(Pembelajaran::class, 'rombel_id');
    }
}
