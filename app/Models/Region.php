<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('active_status', 1);
    }
}
