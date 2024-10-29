<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class SaleReturn extends Model
{
    use LogsActivity;
    protected $table = 'sales_return';

    protected $fillable = [
        'sale_id', 'item_id', 'qty', 'unit', 'price','return_qty', 'return_price', 'return_code', 'description', 'notation', 'return_date', 'transaction_id'
    ];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];
    
    protected $appends = ['return_date_format'];

    public function getReturnDateFormatAttribute()
    {
        return db_date_month_year_format($this->return_date);
    }

    public function sales()
    {
        return $this->belongsTo(Sale::class,'sale_id', 'id');
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
