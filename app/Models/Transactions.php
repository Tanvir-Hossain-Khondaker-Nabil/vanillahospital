<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class Transactions extends Model
{

    use LogsActivity;
//    use SoftDeletes;

    protected $table = 'transactions';

    protected $fillable = [
        'member_id', 'transaction_method', 'amount', 'cash_or_bank_id', 'company_id',
        'transaction_code','date'
    ];

    protected $guarded = [
        'transaction_category_id', 'notation', 'updated_by', 'supplier_id'
    ];

    protected $appends = ['format_amount', 'date_format'];


    protected $hidden = ['deleted_at'];


    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];
    /**
     * Scope a query to only Member
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthMember($query)
    {
        return $query->where('transactions.member_id', Auth::user()->member_id);
    }

    /**
     * Get the Current balance Format.
     *
     * @return string
     */
    public function getFormatAmountAttribute()
    {
        return create_money_format($this->amount);
    }


//    /**
//     * Set the Date Format
//     *
//     * @return string
//     */
//    public function setDateAttribute($inputs)
//    {
//        return db_date_format($inputs);
//    }

    /**
     *  Get the Date Format
     *
     * @return string
     */
    public function getDateFormatAttribute()
    {
        return db_date_month_year_format($this->date);
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
            $query =  $query->where('transactions.company_id', Auth::user()->company_id);

        return $query;
    }

    /**
     * Scope a query for Group by transaction_type & transaction_code
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGroupTransactionTypeCode($query)
    {
        return $query->groupBy('transaction_type', 'transaction_code');
    }

    /**
     * Scope a query for Group by transaction_code
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGroupTransactionCode($query)
    {
        return $query->groupBy('transaction_code');
    }

    /**
     * Scope a query for Order by DESC
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderDesc($query)
    {
        return $query->orderBy('id', 'desc');
    }

    /**
     * Scope a query for Order by ASC
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderAsc($query)
    {
        return $query->orderBy('id', 'ASC');
    }

    /**
     * Scope a query to Without Transfer
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutTransfer($query)
    {
        return $query->where('transaction_method','!=', 'Transfer');
    }

    /**
     * Scope a query to only Transfer
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyTransfer($query)
    {
        return $query->where('transaction_method','=', 'Transfer');
    }

    /**
     * Get the Created by User.
     */
    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    /**
     * Get the Updated by User.
     */
    public function updated_user()
    {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }

    /**
     * Get the member.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the Payment Method.
     */
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Get the Transaction Category.
     */
    public function transaction_category()
    {
        return $this->belongsTo(TransactionCategory::class);
    }

    /**
     * Get the Cash Or Bank Account.
     */
    public function cash_or_bank_account()
    {
        return $this->belongsTo(CashOrBankAccount::class, 'cash_or_bank_id', 'id')->withTrashed();
    }
    /**
     * Get the Company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    /**
     * Get the Sale.
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    /**
     * Get the Company.
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
    /**
     * Get the Transaction Details.
     */
    public function transaction_details()
    {
        return $this->hasMany(TransactionDetail::class,'transaction_id');
    }

    /**
     * Get the project_expense.
     */
    public function project_expense()
    {
        return $this->hasOne(ProjectExpense::class,'transaction_id');
    }
    /**
     * Get the requisition_expense.
     */
    public function requisition_expense()
    {
        return $this->hasOne(RequisitionExpense::class,'transaction_id');
    }

    /**
     * Get the Account Type.
     */
    public function account_type()
    {
        return $this->belongsTo(AccountType::class)->withTrashed();
    }

    /**
     * Get the Supplier Or Customer.
     */
    public function sharer()
    {
        return $this->belongsTo(SupplierOrCustomer::class)->withTrashed();
    }

    /**
     * Get the Supplier Or Customer.
     */
//    public function getAccountsAttribute()
//    {
//        return !empty($this->cash_or_bank_account->title) ? $this->cash_or_bank_account->title : '';
//    }
//
//    /**
//     * Get the Supplier Or Customer.
//     */
//    public function getTransactionCategoryAttribute()
//    {
//        return !empty($this->transaction_category->display_name) ? $this->transaction_category->display_name : '';
//    }
//
//    /**
//     * Get the Supplier Or Customer.
//     */
//    public function getPaymentMethodAttribute()
//    {
//        return !empty($this->payment_method->name) ? $this->payment_method->name : '';
//    }



}
