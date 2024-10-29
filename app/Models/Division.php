<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $appends = ['display_name_bd'];

    public function getDisplayNameBdAttribute()
    {
        return $this->name.($this->bn_name ? " (".$this->bn_name.") " : '');
    }

    public function scopeActive($query)
    {
        return $query->where('active_status', 1);
    }

}
