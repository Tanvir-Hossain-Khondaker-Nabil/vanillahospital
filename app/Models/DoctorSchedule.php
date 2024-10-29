<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{

    protected $guarded = [];


    public function scopeAuthCompany($query)
    {

        if (!\Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }

    public function scheduleDay()
    {
        return $this->hasMany(DoctorScheduleDay::class,'doctor_schedule_id', 'id');

    }
    
    public function doctor()
    {
        return $this->belongsTo(Doctor::class,'doctor_id', 'id');

    }
}