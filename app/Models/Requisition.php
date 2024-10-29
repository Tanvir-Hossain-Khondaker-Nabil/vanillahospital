<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class Requisition extends Model
{
    use LogsActivity;
    // protected $fillable = [
    //     'date', 'total_price', 'notation', 'is_purchased', 'purchase_id'
    // ];
    protected $guarded = [];
    protected $appends = ['date_format'];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function getDateFormatAttribute()
    {
        return db_date_month_year_format($this->date);
    }

    public function requisition_details()
    {
        return $this->hasMany(RequisitionDetail::class, 'requisition_id');
    }

    public function scopeAuthCompany($query)
    {

        if(!Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', Auth::user()->company_id);

        return $query;
    }

    public function scopeAuthUser($query)
    {

        if(!Auth::user()->can(['super-admin', 'admin']))
            $query =  $query->where('created_by', Auth::user()->id);

        return $query;
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


    public function creator()
    {
        return $this->hasOne(User::class, 'id','created_by');
    }

}
