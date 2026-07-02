<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;
    protected $fillable = [
        'sekolah_id',
        'tahun_ajaran',
        'semester',
        'is_aktif',
        'kurikulum',
        'tanggal_rapor',
        'tempat_terbit'
    ];
}
