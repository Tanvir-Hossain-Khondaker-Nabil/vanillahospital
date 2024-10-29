<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class QuotationCompany extends Model
{
    use SoftDeletes;

    protected $table = "quotationers";

    protected $guarded = [];


    protected $appends = ['info_details'];


    public function getInfoDetailsAttribute()
    {
        return $this->company_name."( To, ".$this->designation.") ";
    }

    /**
     * Scope a query to only include active models
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
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


    public function scopeQuotationType($query)
    {
        return $query->where('type', "quotation");
    }

    public function scopeProjectType($query)
    {
        return $query->where('type', "project");
    }

}
