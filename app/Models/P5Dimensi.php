<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5Dimensi extends Model
{
    protected $fillable = ['nama_dimensi', 'deskripsi'];

    public function elemens()
    {
        return $this->hasMany(P5Elemen::class, 'p5_dimensi_id');
    }
}
