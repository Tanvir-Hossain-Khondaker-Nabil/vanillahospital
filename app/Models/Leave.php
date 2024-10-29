<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Leave extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }


    /**
     * Scope a query to only Company
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthCompany($query)
    {
        if(!Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', Auth::user()->company_id);

        return $query;
    }


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

}
