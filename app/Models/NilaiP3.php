<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiP3 extends Model
{
    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function subElemen()
    {
        return $this->belongsTo(P5SubElemen::class, 'p5_sub_elemen_id');
    }
}
