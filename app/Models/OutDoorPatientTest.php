<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutDoorPatientTest extends Model
{
    protected $guarded = [];

    public function scopeAuthCompany($query)
    {

        if (!\Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }

    public function subTestGroup(){

        return $this->belongsTo(SubTestGroup::class, 'sub_test_group_id')->with('testGroup');
    }

    public function testGroup(){

        return $this->belongsTo(TestGroup::class, 'test_group_id');
    }

}