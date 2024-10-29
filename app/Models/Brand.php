<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class Brand extends Model
{
    use LogsActivity;

    protected $guarded = [];

    protected $appends = ['display_name_bd'];

    protected static $logAttributes = ['name', 'bn_name', 'union_id', 'upazilla_id', 'updated_by', 'url', 'company_id', 'status', 'updated_at'];
    protected static $recordEvents = ['created', 'updated', 'deleted'];

    public function getDisplayNameBdAttribute()
    {
        return $this->name;
    }

    public function scopeAuthMember($query)
    {
        return $query->where('member_id', Auth::user()->member_id);
    }


    public function scopeActive($query)
    {
        return $query->where('status', 'active');
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
     * Get the company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);

    }
}
