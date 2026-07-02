<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingRapor extends Model
{
    protected $fillable = [
        'sekolah_id', 'ukuran_kertas', 'margin_kiri', 'margin_kanan', 
        'margin_atas', 'margin_bawah', 'hal_awal_rapor', 
        'tampilkan_ttd_kepsek', 'tampilkan_ttd_wali', 'posisi_ttd_kepsek', 
        'tampilkan_nama_wali'
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }
}
