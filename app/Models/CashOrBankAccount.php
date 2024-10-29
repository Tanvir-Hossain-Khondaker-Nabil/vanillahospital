<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class CashOrBankAccount extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = [
        'title', 'phone', 'contact_person', 'member_id', 'created_by', 'status',
        'account_type_id'
    ];

    protected $guarded = [
        'bank_charge_account_id', 'description', 'internet_banking_url', 'account_number',
        'initial_balance', 'updated_by'
    ];

    protected $appends = ['format_initial_balance', 'format_current_balance'];

    protected static $logAttributes = ['title', 'phone', 'contact_person', 'member_id',
        'created_by', 'status',
        'account_type_id', 'company_id', 'bank_charge_account_id', 'description',
        'internet_banking_url',
        'account_number', 'initial_balance', 'updated_by', 'updated_at'];

    protected static $recordEvents = ['updated', 'deleted'];

    /**
     * Scope a query to Auth Member
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthMember($query)
    {
        return $query->where('member_id', Auth::user()->member_id);
    }

    /**
     * Scope a query to only include active models
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the Initial balance Format.
     *
     * @return string
     */
    public function getFormatInitialBalanceAttribute()
    {
        return create_money_format($this->initial_balance);
    }

    /**
     * Get the Current balance Format.
     *
     * @return string
     */
    public function getFormatCurrentBalanceAttribute()
    {
        return create_money_format($this->current_balance);
    }

    /**
     * Relation with Account Type
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account_type()
    {
        return $this->belongsTo(AccountType::class,'account_type_id')->withTrashed();
    }

    /**
     * Relation with Account Type
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function account_head_balance()
    {
        return $this->hasMany(
            AccountHeadDayWiseBalance::Class,
            'account_type_id',
            'account_type_id'

        );
    }


//    /**
//     * Relation with Sharer
//     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
//     */
//    public function sharer()
//    {
//        return $this->hasOne(SupplierOrCustomer::class,'cash_or_bank_id');
//    }

    /**
     * Relation with Account Type
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bank_charge()
    {
        return $this->belongsTo(AccountType::class,'account_type_id');
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


    public function scopeWithoutSupplierCustomer($query){

        $supplierCustomer = SupplierOrCustomer::whereNotNull('cash_or_bank_id')->authCompany()->authMember()->get()->pluck('cash_or_bank_id');
        return  $query->whereIntegerNotInRaw('id', $supplierCustomer);
    }
}
