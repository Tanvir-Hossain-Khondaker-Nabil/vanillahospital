<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Appoinment extends Model
{
    use LogsActivity;

    //
    protected $guarded = [];
    
    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function scopeAuthCompany($query)
    {

        if (!\Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);

    }

    public function doctorScheduleDay()
    {
        return $this->belongsTo(DoctorScheduleDay::class,'doctor_schedule_day_id');

    }
}