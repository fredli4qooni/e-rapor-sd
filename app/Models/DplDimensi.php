<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DplDimensi extends Model
{
    protected $guarded = [];

    public function subdimensis()
    {
        return $this->hasMany(DplSubdimensi::class, 'dpl_dimensi_id');
    }
}
