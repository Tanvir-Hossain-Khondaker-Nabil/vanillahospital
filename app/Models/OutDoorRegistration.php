<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutDoorRegistration extends Model
{
    protected $guarded = [];
    public function scopeAuthCompany($query)
    {

        if (!\Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }

    public function doctor(){

        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function refDoctor(){

        return $this->belongsTo(Doctor::class, 'ref_doctor_id');
    }

    public function shareHolder(){

        return $this->belongsTo(ShareHolder::class, 'share_holder_id');
    }

    public function user(){

        return $this->belongsTo(User::class, 'created_by');
    }

    public function outdoorPatientTest(){

        return $this->hasMany(OutDoorPatientTest::class, 'out_door_registration_id')->with('subTestGroup');
    }

    public function opdDueCollectionHistory(){

        return $this->hasMany(opdDueCollectionHistory::class, 'out_door_registration_id');
    }

}