<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeskripsiRapor extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function nilaiRapor()
    {
        return $this->belongsTo(NilaiRapor::class, 'nilai_rapor_id');
    }
}
