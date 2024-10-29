<?php

namespace App;

use App\Models\User;
use App\Models\Doctor;
use App\Models\HospitalService;
use Illuminate\Database\Eloquent\Model;

class HospitalServiceHistory extends Model
{
    public function scopeAuthCompany($query)
    {

        if (!auth()->user()->can(['super-admin']))
            $query = $query->where('company_id', auth()->user()->company_id);

        return $query;
    }

    public function service(){
        return $this->belongsTo(HospitalService::class,'service_id','id');
    }
    public function patient(){
        return $this->belongsTo(IpdPatientInfo::class,'patient_id','id');
    }
    public function doctor(){
        return $this->belongsTo(Doctor::class,'doctor_id','id');
    }
    public function marketingOfficer(){
        return $this->belongsTo(Marketing_officer::class,'marketing_officer_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'operator_id','id');
    }
}
