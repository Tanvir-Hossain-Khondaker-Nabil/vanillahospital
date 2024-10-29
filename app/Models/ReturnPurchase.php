<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class ReturnPurchase extends Model
{
    use LogsActivity;
    protected $fillable = [
        'purchase_id', 'item_id', 'qty', 'unit', 'price', 'fine_price', 'return_code', 'return_qty',
        'return_price', 'description', 'return_date', 'transaction_id'
    ];


    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    protected $appends = ['return_date_format'];

    public function getReturnDateFormatAttribute()
    {
        return db_date_month_year_format($this->return_date);
    }


    public function purchases()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }


    public function item()
    {
        return $this->hasOne( Item::class, 'id', 'item_id');
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

}
