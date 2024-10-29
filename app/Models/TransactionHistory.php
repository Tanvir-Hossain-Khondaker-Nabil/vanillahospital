<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TransactionHistory extends Model
{
    protected $table = 'transaction_history';

    protected $fillable = [
        'member_id', 'amount', 'cash_or_bank_id', 'created_by', 'transaction_method', 'transaction_type',
        'company_id', 'transaction_code', 'flag', 'previous_balance', 'current_balance', 'browser_history',
        'ip_address'
    ];

    protected $hidden = ['deleted_at'];

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
     * Get the Created by User.
     */
    public function created_by()
    {
        return $this->belongsTo(User::class, 'id');
    }

    /**
     * Get the Updated by User.
     */
    public function updated_by()
    {
        return $this->belongsTo(User::class, 'id');
    }

    /**
     * Get the member.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the Cash Or Bank Account.
     */
    public function cash_or_bank_account()
    {
        return $this->belongsTo(CashOrBankAccount::class);
    }

    /**
     * Get the Company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
