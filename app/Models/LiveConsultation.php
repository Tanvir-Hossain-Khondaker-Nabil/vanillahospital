<?php

namespace App\Models;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Model;

class LiveConsultation extends Model
{
    protected $guarded = [];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    

    public function scopeAuthCompany($query)
    {

        if (!\Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }
}
