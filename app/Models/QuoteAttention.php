<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class QuoteAttention extends Model
{
    use SoftDeletes;

    protected $guarded = [];


    protected $appends = ['info_details'];


    public function getInfoDetailsAttribute()
    {
        return $this->name." (".$this->designation.", ".$this->department.", ".$this->contact.") ";
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



    public function quoteCompany()
    {
        return $this->hasOne(QuotationCompany::class, 'id','quotationer_id');
    }
}
