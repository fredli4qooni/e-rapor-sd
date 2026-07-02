<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5Elemen extends Model
{
    protected $fillable = ['p5_dimensi_id', 'nama_elemen'];

    public function dimensi()
    {
        return $this->belongsTo(P5Dimensi::class, 'p5_dimensi_id');
    }

    public function subElemens()
    {
        return $this->hasMany(P5SubElemen::class, 'p5_elemen_id');
    }
}
