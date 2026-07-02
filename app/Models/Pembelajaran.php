<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelajaran extends Model
{
    protected $fillable = [
        'sekolah_id',
        'semester_id',
        'rombel_id',
        'mata_pelajaran_id',
        'guru_id',
        'is_aktif',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
