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
        'status_input_nilai',
        'kurikulum',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_mulai_input',
        'tanggal_akhir_input',
        'tanggal_rapor',
        'tempat_terbit'
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
        'status_input_nilai' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_mulai_input' => 'date',
        'tanggal_akhir_input' => 'date',
        'tanggal_rapor' => 'date',
    ];

    public function getNamaSemesterAttribute()
    {
        return $this->tahun_ajaran . ' ' . ($this->semester == 1 ? 'Ganjil' : 'Genap');
    }

    /**
     * Deadline acuan batas waktu input nilai guru
     */
    public function getDeadlineInputNilaiAttribute()
    {
        return $this->tanggal_akhir_input ?? $this->tanggal_rapor ?? $this->tanggal_selesai;
    }

    /**
     * Sisa hari menuju deadline input nilai (angka bulat)
     */
    public function getSisaHariInputAttribute()
    {
        $deadline = $this->deadline_input_nilai;
        if (!$deadline) {
            return null;
        }

        return (int) \Carbon\Carbon::today()->diffInDays($deadline, false);
    }

    /**
     * Status periode pengisian nilai saat ini
     */
    public function getStatusPeriodeInputAttribute()
    {
        if (!$this->status_input_nilai) {
            return 'ditutup';
        }

        $today = \Carbon\Carbon::today();

        if ($this->tanggal_mulai_input && $today->lt($this->tanggal_mulai_input)) {
            return 'belum_buka';
        }

        $sisaHari = $this->sisa_hari_input;
        if ($sisaHari !== null) {
            if ($sisaHari < 0) {
                return 'lewat_deadline';
            }
            if ($sisaHari === 0) {
                return 'hari_h';
            }
            if ($sisaHari <= 14) {
                return 'mendekati_deadline';
            }
        }

        return 'aktif';
    }

    /**
     * Label teks rentang tanggal periode semester
     */
    public function getPeriodeSemesterTeksAttribute()
    {
        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            return $this->tanggal_mulai->translatedFormat('d M Y') . ' s/d ' . $this->tanggal_selesai->translatedFormat('d M Y');
        } elseif ($this->tanggal_selesai) {
            return 's/d ' . $this->tanggal_selesai->translatedFormat('d M Y');
        }

        return '-';
    }
}
