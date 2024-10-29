<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class TransactionDetail extends Model
{

    use LogsActivity;
//    use SoftDeletes;

    protected $fillable = [
        'transaction_id', 'account_type_id', 'payment_method_id', 'amount', 'description',
        'transaction_type', 'company_id', 'short_description', 'date',
        'against_account_type_id', 'against_account_name'
    ];

    protected $guarded = [
        'transaction_category_id', 'description'
    ];

    protected $appends = ['format_amount', 'uc_transaction_type', 'date_format'];


    protected $hidden = ['deleted_at'];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];
    /**
     * Get the Current balance Format.
     *
     * @return string
     */
    public function getFormatAmountAttribute()
    {
        return create_money_format($this->amount);
    }

    /**
     * Get the Current balance Format.
     *
     * @return string
     */
    public function getUcTransactionTypeAttribute()
    {
        return ucfirst($this->transaction_type);
    }

    /**
     * Get the Date Format.
     *
     * @return string
     */
    public function getDateFormatAttribute()
    {
        return db_date_month_year_format($this->date);
    }

    /**
     * Set the Date Format.
     *
     * @return string
     */
//    public function setDateAttribute($inputs)
//    {
//        return db_date_format($inputs);
//    }


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
     * Scope a query for Group by transaction_type & transaction_code
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGroupTransactionType($query)
    {
        return $query->groupBy('transaction_type');
    }

    /**
     * Get the Created by User.
     */
    public function transaction()
    {
        return $this->hasOne(Transactions::class, 'id','transaction_id');
    }


    public function account_type(){

        return $this->belongsTo(AccountType::class, 'account_type_id')->withTrashed();
    }


    public function against_account_type(){

        return $this->belongsTo(AccountType::class, 'against_account_type_id')->withTrashed();
    }

    public function payment_method(){

        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function company(){

        return $this->belongsTo(Company::class, 'company_id');
    }
}
