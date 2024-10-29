<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class DeliveryType extends Model
{

    use LogsActivity;

    protected $fillable = [
        'display_name', 'name', 'status', 'member_id', 'company_id'
    ];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    /**
     * Get the members.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope a query to only include active models
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }



    /**
     * Scope a query to only Member
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthMember($query)
    {
        return $query->where('member_id', Auth::user()->member_id);
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
