<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class DealerStock extends Model
{
    use LogsActivity;

    protected $fillable = ['item_id', 'stock', 'dealer_id', 'company_id', 'member_id', 'created_by'];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function item()
    {
        return $this->belongsTo(Item::class)->withTrashed();
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


    /**
     * Scope a query to only include Customer models
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthMember($query)
    {
        return $query->where('member_id', Auth::user()->member_id);
    }

    /**
     * Get the member.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);

    }

    /**
     * Get the member.
     */
    public function dealer()
    {
        return $this->belongsTo(User::class, 'dealer_id');

    }

    public function scopeAuthDealer($query)
    {

        if(Auth::user()->hasRole(['dealer']))
            $query =  $query->where('dealer_id', Auth::user()->id);

        return $query;
    }
}
