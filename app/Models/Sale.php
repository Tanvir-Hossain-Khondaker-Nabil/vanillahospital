<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class Sale extends Model
{
    use LogsActivity;

    protected $fillable = [
        'sale_code', 'date', 'total_price', 'paid_amount', 'discount_type', 'discount',
        'cash_or_bank_id', 'shipping_charge',
        'customer_id', 'payment_method_id', 'delivery_type_id', 'member_id', 'company_id',
        'notation', 'amount_to_pay', 'due',
        'grand_total', 'membership_card', 'total_discount', 'memo_no', 'chalan_no',
        'transport_cost', 'unload_cost', 'vehicle_number',
        'is_requisition', 'sale_requisition_id', 'quotation_id', 'track_sale'
    ];
    protected $appends = ['date_format'];
    protected $guarded = [];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function getDateFormatAttribute()
    {
        return db_date_month_year_format($this->date);
    }

    public function setTotalPriceAttribute($value)
    {
        $this->attributes['total_price'] = create_float_format($value);
    }

    public function setDueAttribute($value)
    {
        $this->attributes['due'] = create_float_format($value);
    }

//    public function setAdvanceAmountAttribute($value)
//    {
//        $this->attributes['advance_amount'] = create_float_format($value);
//    }

    public function setPaidAmountAttribute($value)
    {
        $this->attributes['paid_amount'] = create_float_format($value);
    }

    public function setGrandTotalAttribute($value)
    {
        $this->attributes['grand_total'] = create_float_format($value);
    }

    public function setAmountToPayAttribute($value)
    {
        $this->attributes['amount_to_pay'] = create_float_format($value);
    }

    public function customer()
    {
        return $this->hasOne(SupplierOrCustomer::class, 'id', 'customer_id');
    }

    public function cash_or_bank()
    {
        return $this->hasOne(CashOrBankAccount::class, 'id','cash_or_bank_id');
    }

    public function creator()
    {
        return $this->hasOne(User::class, 'id','created_by');
    }

    public function payment_method()
    {
        return $this->hasOne(PaymentMethod::class, 'id','payment_method_id');
    }

    public function delivery_type()
    {
        return $this->hasOne(DeliveryType::class, 'id','delivery_type_id');
    }


    public function transaction()
    {
        return $this->hasOne(Transactions::class, 'sale_id');
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class, 'id', 'quotation_id');
    }

    public function sale_details()
    {
        return $this->hasMany(SaleDetails::class, 'sale_id');
    }

    public function shopping_bags()
    {
        return $this->hasMany(TrackShoppingBags::class, 'sale_id');
    }

    public function sales_return()
    {
        return $this->hasMany(SaleReturn::class, 'sale_id');
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


    public function scopeIsRequisition($query)
    {
        return $query->where('is_requisition', 1);
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

    public function scopeAuthUser($query)
    {
        if(Auth::user()->hasRole(['user'])){

            $query = $query->where('branch_id', Auth::user()->branch_id);
        }

        return $query;
    }
}
