<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingTranskrip extends Model
{
    protected $fillable = [
        'sekolah_id', 'tampilan_nama_siswa', 'jumlah_angka_desimal', 
        'tampilkan_baris_rata_rata', 'angka_desimal_rata_rata', 
        'tempat_tanggal_transkrip', 'nama_kepala_sekolah', 
        'nip_kepala_sekolah', 'tampilkan_ttd_kepala_sekolah',
        'ukuran_kertas', 'margin_kiri', 'margin_kanan', 'margin_atas', 
        'margin_bawah', 'jarak_antar_identitas', 'tinggi_judul', 
        'tinggi_isi_tabel', 'persentase_kop'
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }
}
