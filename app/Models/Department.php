<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Database\Eloquent\SoftDeletes;
class Department extends Model
{
    // use SoftDeletes;
    protected $guarded = [];


    public function scopeActive($query)
    {
        return $query->where('active_status', 1);
    }

    public function scopeAuthCompany($query)
    {

        if(!Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', Auth::user()->company_id);

        return $query;
    }

    /**
     * Get the company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);

    }


}