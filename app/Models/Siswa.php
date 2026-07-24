<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'sekolah_id',
        'nisn',
        'nis',
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_lahir',
        'tempat_lahir',
        'nama_ayah',
        'pekerjaan_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'alamat',
        'foto',
        'no_ijazah',
        'no_transkrip',
        'tgl_lulus',
        'is_transkrip_published',
        'is_pelengkap_published',
        'is_rapor_published',
        'is_p5_published',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rombels()
    {
        return $this->belongsToMany(Rombel::class, 'anggota_rombels');
    }
}
