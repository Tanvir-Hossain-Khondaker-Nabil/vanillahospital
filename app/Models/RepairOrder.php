<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class RepairOrder extends Model
{
    use LogsActivity;

    protected $table = 'product_repairs';

    protected $guarded = [];

    protected $appends = ['screenshot_path', 'date_format'];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function getDateFormatAttribute()
    {
        return db_date_month_year_format($this->date);
    }

//    public function setTotalPriceAttribute($value)
//    {
//        $this->attributes['total_price'] = create_float_format($value);
//    }

//    public function setDueAttribute($value)
//    {
//        $this->attributes['due'] = create_float_format($value);
//    }

//    public function setAdvanceAmountAttribute($value)
//    {
//        $this->attributes['advance_amount'] = create_float_format($value);
//    }
//
//    public function setPaidAmountAttribute($value)
//    {
//        $this->attributes['paid_amount'] = create_float_format($value);
//    }
//
//
//    public function setAmountToPayAttribute($value)
//    {
//        $this->attributes['amount_to_pay'] = create_float_format($value);
//    }


    /**
     * Get the product image path.
     *
     * @return string
     */
    public function getScreenshotPathAttribute()
    {
        return asset('storage/app/public/take_screenshot/'. $this->take_screenshot);
    }

    public function defects()
    {
        return DefectType::whereIn('id', json_decode($this->defect_type_ids))->get();
    }


    public function customer()
    {
        return $this->hasOne(SupplierOrCustomer::class, 'id', 'customer_id');
    }

    public function cash_or_bank()
    {
        return $this->hasOne(CashOrBankAccount::class, 'id','account_type_id');
    }

    public function creator()
    {
        return $this->hasOne(User::class, 'id','created_by');
    }


    public function sale()
    {
        return $this->hasOne(Sale::class, 'sale_code', 'order_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transactions::class, 'repair_order_id');
    }

    public function repair_items()
    {
        return $this->hasMany(RepairItem::class, 'repair_id');
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
