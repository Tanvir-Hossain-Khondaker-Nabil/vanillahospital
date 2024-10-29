<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Designation extends Model
{
    protected $guarded = [];

    public function parent_designation()
    {
        return $this->belongsTo(Designation::class, 'parent_designation_id');
    }

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
