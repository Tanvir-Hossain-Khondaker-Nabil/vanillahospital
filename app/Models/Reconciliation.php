<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Reconciliation extends Model
{

    use SoftDeletes;

    protected $fillable = [ 'sharer_id', 'cash_or_bank_id', 'date', 'account_type_id', 'transaction_type',
    'notes', 'transaction_id', 'transaction_code', 'amount'];

    /**
     * Get the Created by User.
     */
    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the Updated by User.
     */
    public function updated_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the member.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the transaction.
     */
    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'transaction_id');
    }

    /**
     * Get the Cash Or Bank Account.
     */
    public function cash_or_bank_account()
    {
        return $this->belongsTo(CashOrBankAccount::class, 'cash_or_bank_id');
    }
    /**
     * Get the Company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the Account Type.
     */
    public function account_type()
    {
        return $this->belongsTo(AccountType::class);
    }

    /**
     * Get the Supplier Or Customer.
     */
    public function sharer()
    {
        return $this->belongsTo(SupplierOrCustomer::class);
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



}
