<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AdvanceShift extends Model
{

    protected $table = "advance_shift";
    protected $guarded = [];

    public $timestamps = false;

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
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

}
