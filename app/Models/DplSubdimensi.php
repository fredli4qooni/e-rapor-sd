<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DplSubdimensi extends Model
{
    protected $guarded = [];

    public function dimensi()
    {
        return $this->belongsTo(DplDimensi::class, 'dpl_dimensi_id');
    }
}
