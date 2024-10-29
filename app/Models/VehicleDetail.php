<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Driver;
use App\Models\VehicleInfo;
use App\Models\VehicleSchedule;

class VehicleDetail extends Model
{
    protected $guarded = [];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicleInfo()
    {
        return $this->belongsTo(VehicleInfo::class);
    }

    public function vehicleSchedule()
    {
        return $this->belongsTo(VehicleSchedule::class);
    }

    public function scopeAuthCompany($query)
    {
        if (!\Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }
}
