<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class Purchase extends Model
{
    use LogsActivity;
    protected $fillable = [
        'date','chalan','memo_no','total_price','due_amount','advance_amount',
        'paid_amount', 'bank_charge',
        'total_discount','amt_to_pay','cash_or_bank_id','supplier_id','transport_cost',
        'unload_cost','discount_type','discount','total_amount','notation','file',
        'payment_method_id','invoice_no', 'vehicle_number', 'file', 'is_requisition', 'quotation_id'
    ];
    protected $guarded = [];
    protected $appends = ['date_format','attach_file_path'];

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

    public function setDueAmountAttribute($value)
    {
        $this->attributes['due_amount'] = create_float_format($value);
    }

    public function setAdvanceAmountAttribute($value)
    {
        $this->attributes['advance_amount'] = create_float_format($value);
    }

    public function setPaidAmountAttribute($value)
    {
        $this->attributes['paid_amount'] = create_float_format($value);
    }

    public function setTotalAmountAttribute($value)
    {
        $this->attributes['total_amount'] = create_float_format($value);
    }

    public function setAmtToPayAttribute($value)
    {
        $this->attributes['amt_to_pay'] = create_float_format($value);
    }

    public function supplier()
    {
        return $this->hasOne(SupplierOrCustomer::class, 'id','supplier_id');
    }

    public function cash_or_bank()
    {
        return $this->hasOne(CashOrBankAccount::class, 'id','cash_or_bank_id');
    }

    public function payment_method()
    {
        return $this->hasOne(PaymentMethod::class, 'id','payment_method_id');
    }

    public function purchase_details()
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id');
    }

    public function purchase_returns()
    {
        return $this->hasMany( ReturnPurchase::class, 'purchase_id');
    }

    public function purchase_product_info()
    {
        return $this->hasMany( ItemDetail::class, 'purchase_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transactions::class, 'purchase_id');
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class, 'id', 'quotation_id');
    }

    public function scopeIsRequisition($query)
    {
        return $query->where('is_requisition', 1);
    }

    public function scopeAuthCompany($query)
    {

        if(!Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', Auth::user()->company_id);

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

    public function scopeAuthUser($query)
    {
         if(Auth::user()->hasRole(['user']) && Auth::user()->branch_id != null){

            $query = $query->where('branch_id', Auth::user()->branch_id);
        }

        return $query;
    }
    /**
     * Get the Company Logo path.
     *
     * @return string
     */
    public function getAttachFilePathAttribute()
    {
        return asset('public/storage/file/'. $this->file);
    }

}
