<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiEkstrakurikuler extends Model
{
    protected $guarded = [];

    public function ekstrakurikuler()
    {
        return $this->belongsTo(Ekstrakurikuler::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }
}
