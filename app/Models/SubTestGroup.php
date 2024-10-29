<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubTestGroup extends Model
{
    protected $guarded = [];
    public function scopeAuthCompany($query)
    {

        if (!\Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }

    public function testGroup(){

        return $this->belongsTo(TestGroup::class, 'test_group_id')->with('specimen');
    }

    public function pathologyReport(){

        return $this->hasOne(PathologyReport::class, 'sub_test_group_id');
    }

}