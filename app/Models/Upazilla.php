<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upazilla extends Model
{
    protected $guarded = [];

    protected $appends = ['display_name_bd'];

    public function getDisplayNameBdAttribute()
    {
        return $this->name." (".$this->bn_name.") ";
    }
}
