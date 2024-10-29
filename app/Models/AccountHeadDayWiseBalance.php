<?php

namespace App\Models;

use App\Http\Traits\AccountHeadBalanceHistoryTrait;
use App\Http\Traits\AccountHeadDayWiseBalanceTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class AccountHeadDayWiseBalance extends Model
{
    use LogsActivity, AccountHeadDayWiseBalanceTrait, AccountHeadBalanceHistoryTrait;

    protected $table = 'account_head_day_wise_balance';
    protected $fillable = ['account_type_id', 'balance', 'date', 'company_id'];

    protected static $logAttributes = ['account_type_id', 'balance', 'date', 'company_id', 'updated_at'];
    protected static $recordEvents = ['updated'];

    public function account_types()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
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
     * Scope a query to only include Customer models
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthMember($query)
    {
        return $query->where('member_id', Auth::user()->member_id);
    }


    public function scopeLatestAccountBalance($query, $date)
    {
        return $query->where('date','<=', $date)->orderBy('date', 'desc');
    }

    public function scopePreviousAccountBalance($query, $date)
    {
        return $query->where('date','<', $date)->orderBy('date', 'desc');
    }


}
