<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorComission extends Model
{
    protected $guarded = [];

    public function scopeAuthCompany($query)
    {

        if (!\Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }

    public function comission()
    {
        return $this->hasMany(DoctorComissionTest::class,'doctor_comission_id', 'id')->with('subTestGroup');

    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class,'doctor_id', 'id');

    }

    public function testGroup()
    {
        return $this->belongsTo(TestGroup::class,'test_group_id', 'id');

    }
}