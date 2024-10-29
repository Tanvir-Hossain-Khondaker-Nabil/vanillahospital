<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technologist extends Model
{

    protected $guarded = [];


    public function scopeAuthCompany($query)
    {

        if (!\Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }

    public function specimen(){

        return $this->belongsTo(Specimen::class, 'specimen_id');
    }

    public function testGroup(){

        return $this->belongsTo(TestGroup::class, 'test_group_id')->with('specimen');
    }

    public function technologistDoctor(){

        return $this->belongsTo(Doctor::class, 'technologist_doctor_id');
    }

    public function preparedDoctor(){

        return $this->belongsTo(Doctor::class, 'prepared_doctor_id');
    }

    public function checkedDoctor(){

        return $this->belongsTo(Doctor::class, 'checked_by_doctor_id');
    }

    public function technologistEmployee(){

        return $this->belongsTo(EmployeeInfo::class, 'technologist_employeeinfo_id');
    }

    public function preparedEmployee(){

        return $this->belongsTo(EmployeeInfo::class, 'prepared_employeeinfo_id');
    }

     public function checkedEmployee(){

        return $this->belongsTo(EmployeeInfo::class, 'checked_by_employeeinfo_id');
    }

    public function assignTechnologist(){

        return $this->hasMany(AssignTechnologist::class, 'technologist_id')->with('subTestGroup');
    }
}