<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5Tema extends Model
{
    protected $fillable = ['nama_tema', 'deskripsi', 'is_aktif'];

    public function proyeks()
    {
        return $this->hasMany(P5Proyek::class, 'p5_tema_id');
    }
}
