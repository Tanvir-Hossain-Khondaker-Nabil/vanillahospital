<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignTechnologist extends Model
{

    protected $guarded = [];


    public function scopeAuthCompany($query)
    {

        if (!\Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }


    public function subTestGroup(){

        return $this->belongsTo(SubTestGroup::class, 'sub_test_group_id');
    }

    public function technologist(){

        return $this->belongsTo(Technologist::class, 'technologist_id')->with(['checkedDoctor','preparedDoctor','technologistDoctor','checkedEmployee','preparedEmployee','technologistEmployee']);
    }
}