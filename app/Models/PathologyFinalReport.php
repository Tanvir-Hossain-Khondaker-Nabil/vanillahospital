<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PathologyFinalReport extends Model
{

    protected $guarded = [];

    public function scopeAuthCompany($query)
    {

        if (!\Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }



    public function outDoorRegistration(){

        return $this->belongsTo(OutDoorRegistration::class, 'out_door_registration_id')->with(['doctor','refDoctor']);
    }

    public function subTestGroup(){

        return $this->belongsTo(SubTestGroup::class, 'sub_test_group_id');
    }
}