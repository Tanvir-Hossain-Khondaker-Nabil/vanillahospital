<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Shift extends Model
{
    protected $fillable =[
        'time_in', 'time_out', 'shift_type', 'company_id', 'late'
    ];

    protected $appends = ['time_in_out','shift_type_name'];
    
    public function scopeAuthCompany($query)
    {

        if(!Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', Auth::user()->company_id);

        return $query;
    }

    public function getTimeInOutAttribute()
    {
        $timeIn = Carbon::parse($this->time_in)->format("g:i A");
        $timeOut = Carbon::parse($this->time_out)->format("g:i A");

        return $timeIn." - ".$timeOut;
    }

    public function getShiftTypeNameAttribute()
    {
        $shift_type = $this->shift_type == 0 ? "Day " : "Night ";

        return $shift_type;
    }
}
