<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class AccountType extends Model
{

    use LogsActivity, SoftDeletes;

    protected $fillable= ['display_name', 'name', 'parent_id', 'class_id', 'company_id'];

    protected $appends = ['account_code', 'account_code_name'];

//    protected $date = ['deleted_at'];

    protected static $logAttributes = ['display_name', 'name', 'parent_id', 'class_id', 'company_id', 'updated_at'];
    protected static $recordEvents = ['updated', 'deleted'];


    public function getAccountCodeNameAttribute()
    {
        return $this->account_code." ".$this->display_name;
    }

    public function getAccountCodeAttribute()
    {
        return format_number_digit($this->id);
    }

    public function parent_name(){

        return $this->belongsTo(AccountType::class, 'parent_id');
    }

    public function gl_class(){

        return $this->belongsTo(GLAccountClass::class, 'class_id');
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

    /**
     * Scope a query to only Member
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthCompany($query)
    {

        if(!Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', Auth::user()->company_id)->orWhereNull('company_id');

        return $query;
    }

    /**
     * Scope a query to only include active models
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->orderBy('id');
    }

    /**
     * Account Group Find
     * @param $query
     * @return mixed
     */
    public function scopeGroup($query)
    {
        $cash_banks = CashOrBankAccount::whereNotIn('account_type_id',[3,4])->get()->pluck('account_type_id');
        return $query->whereIntegerNotInRaw('id', $cash_banks)->whereNull('parent_id');
    }

    /**
     * Account List Find
     * @param $query
     * @return mixed
     */
    public function scopeOnlyAccount($query)
    {
        $cash_banks = CashOrBankAccount::whereNotIn('account_type_id',[3,4,971,929,949,955,956,970])->get()->pluck('account_type_id');
        return $query->whereIntegerNotInRaw('id', $cash_banks);
    }

    /**
     * Account List Find
     * @param $query
     * @return mixed
     */
    public function scopeOnlySharerAccount($query)
    {
        $sharer = SupplierOrCustomer::whereNotNull('account_type_id')->get()->pluck('account_type_id');
        return $query->whereIntegerInRaw('id', $sharer);
    }

    /**
     * Account List Find
     * @param $query
     * @return mixed
     */
    public function scopeOnlyBankAccount($query)
    {
        $cash_banks = AccountType::where('parent_id', 3)->get()->pluck('id');
        return $query->whereIntegerInRaw('id', $cash_banks);
    }


    public function findChildAccount($name)
    {
        $data = AccountType::where('name', '=', $name)->pluck('id')->first();
        return AccountType::where('parent_id', $data)->pluck('id');
    }

    public function scopeFindReconciliationAccount($query)
    {
        $data = AccountType::where('name', '=', 'reconciliation')->pluck('id')->first();
        return $query->where('parent_id', $data);
    }


    /**
     * Relation with Account Type
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function account_head_balance()
    {
        return $this->hasMany(
            AccountHeadDayWiseBalance::Class, 'account_type_id', 'id'
        );
    }

    /**
     * Relation with Account Type
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function previous_balance()
    {
        return $this->hasMany(
            TrackAccountHeadBalance::Class, 'account_type_id', 'id'
        );
    }

    /**
     * Relation with Transaction Details
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function transaction_details()
    {
        return $this->hasMany(
            TransactionDetail::Class, 'account_type_id', 'id'
        );
    }


    public function totalDr()
    {
        $result = $this->transaction_details()
            ->selectRaw(' sum(amount) as totalDr')
            ->where('transaction_type', '=','dr')
            ->groupBy('transaction_type');

        return $result;
    }

    public function totalCr()
    {
        return $this->transaction_details()
            ->selectRaw(' sum(amount) as totalCr')
            ->where('transaction_type', '=','cr')
            ->groupBy('transaction_type');
    }


    public function accountTypeNotDeletable()
    {
        $accounts = AccountType::where('id','<',46)->orWhereIn('name',
            [
                'advance_deposits&_prepayments',
                'due_from_affiliated_company',
                'due_to_affiliated_company',
                'income_tax_payble',
                'liabilities_for_expenses',
                'fixed_deposits_receipts'
            ])->pluck('id')->toArray();

        return $accounts;
    }


}
