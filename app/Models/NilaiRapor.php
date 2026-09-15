<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiRapor extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tp_tertinggi' => 'array',
            'tp_terendah' => 'array',
        ];
    }

    public function deskripsi()
    {
        return $this->hasOne(DeskripsiRapor::class, 'nilai_rapor_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function getDeskripsiTertinggiAttribute()
    {
        if ($this->tp_tertinggi) {
            $tps = is_array($this->tp_tertinggi) ? $this->tp_tertinggi : json_decode($this->tp_tertinggi, true);
            if (is_array($tps)) {
                return implode(', ', array_column($tps, 'deskripsi'));
            }
            return $this->tp_tertinggi;
        }
        return '-';
    }

    public function getDeskripsiTerendahAttribute()
    {
        if ($this->tp_terendah) {
            $tps = is_array($this->tp_terendah) ? $this->tp_terendah : json_decode($this->tp_terendah, true);
            if (is_array($tps)) {
                return implode(', ', array_column($tps, 'deskripsi'));
            }
            return $this->tp_terendah;
        }
        return '-';
    }

    public function getCapaianKompetensiAttribute()
    {
        $descTertinggi = null;
        $descTerendah = null;

        if ($this->deskripsi) {
            $descTertinggi = $this->deskripsi->deskripsi_tertinggi;
            $descTerendah = $this->deskripsi->deskripsi_terendah;
        }

        if (!$descTertinggi || $descTertinggi === '-') {
            $t = $this->deskripsi_tertinggi;
            if ($t && $t !== '-') {
                $descTertinggi = "Menunjukkan penguasaan yang sangat baik dalam " . rtrim(trim($t), '.') . ".";
            }
        }

        if (!$descTerendah || $descTerendah === '-') {
            $r = $this->deskripsi_terendah;
            if ($r && $r !== '-') {
                $descTerendah = "Perlu pendampingan dalam " . rtrim(trim($r), '.') . ".";
            }
        }

        $parts = [];
        if ($descTertinggi && $descTertinggi !== '-') {
            $parts[] = $descTertinggi;
        }
        if ($descTerendah && $descTerendah !== '-') {
            $parts[] = $descTerendah;
        }

        return count($parts) > 0 ? implode("\n\n", $parts) : '-';
    }
}
