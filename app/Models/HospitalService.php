<?php

namespace App\Models;

use App\Marketing_officer;
use Illuminate\Database\Eloquent\Model;

class HospitalService extends Model
{
    protected $guarded = [];


    public function scopeAuthCompany($query)
    {

        if (!\Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }
   
}
