<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class SalesRequisition extends Model
{
    use LogsActivity;
    protected $fillable = [
        'date', 'total_price', 'notation', 'member_id','company_id','created_by','updated_by',
        'dealer_id', 'requisition_request_by', 'customer_id', 'is_sale','sale_id'
    ];
    protected $guarded = [];
    protected $appends = ['date_format'];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function getDateFormatAttribute()
    {
        return db_date_month_year_format($this->date);
    }

    public function sales_requisition_details()
    {
        return $this->hasMany(SalesRequisitionDetail::class, 'sales_requisition_id');
    }

    public function scopeAuthCompany($query)
    {

        if(!Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', Auth::user()->company_id);

        return $query;
    }



    public function scopeAuthUser($query)
    {

//        if(Auth::user()->can(['member.sales_requisitions.index']) && !Auth::user()->hasRole(['superad']))
//            $query =  $query->where('created_by', Auth::user()->id);

        if(Auth::user()->can(['member.sales_requisitions.index']) && !Auth::user()->hasRole(['dealer']))
            $query =  $query->where('requisition_request_by', 0);


        if(Auth::user()->hasRole(['dealer']))
            $query =  $query->where('requisition_request_by', 0)->where('dealer_id', Auth::user()->id);

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

    public function dealer()
    {
        return $this->hasOne(User::class, 'id','dealer_id');
    }

//
//    public function customer()
//    {
//        return $this->belongsTo(Customer::class);
//    }

    // Customer AND Store Function Same for Relation
    public function customer()
    {
        return $this->belongsTo(Store::class, 'customer_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'customer_id');
    }

    public function sales()
    {
        return $this->belongsTo(Sale::class);
    }
}
