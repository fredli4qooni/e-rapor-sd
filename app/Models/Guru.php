<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'sekolah_id',
        'nip',
        'nama_lengkap',
        'gelar_depan',
        'gelar_belakang',
        'nip_tampil',
        'is_kepsek',
        'ttd_wali',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
