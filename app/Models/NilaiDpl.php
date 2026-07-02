<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiDpl extends Model
{
    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function subdimensi()
    {
        return $this->belongsTo(DplSubdimensi::class, 'dpl_subdimensi_id');
    }
}
