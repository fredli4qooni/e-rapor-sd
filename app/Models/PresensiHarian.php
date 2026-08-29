<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiHarian extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'rombel_id',
        'semester_id',
        'tanggal',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'H' => 'Hadir',
            'S' => 'Sakit',
            'I' => 'Izin',
            'A' => 'Alpa / Tanpa Keterangan',
            default => 'Hadir',
        };
    }
}
