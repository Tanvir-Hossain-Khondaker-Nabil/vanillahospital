<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class SupplierOrCustomer extends Model
{
    use LogsActivity, SoftDeletes;

    protected $table = 'suppliers_or_customers';

    // Must be Assignable
    protected $fillable = [
        'name', 'phone', 'customer_type', 'status' , 'sale_limit', 'purchase_limit',
        'balance_limit', 'address', 'email', 'cash_or_bank_id', 'account_type_id',
        'division_id', 'district_id', 'area_id', 'upazilla_id', 'union_id',
        'file', 'manager_id', 'photo',
        'is_blacklist'
    ];

    // Not Required Fill
    protected $guarded = [ 'initial_balance', 'current_balance'];

    protected $appends = ['format_initial_balance', 'format_current_balance', 'name_phone',
        'name_address', 'sharer_photo', 'sharer_file'];


    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function getNamePhoneAttribute()
    {
        return $this->name." (".$this->phone.")";
    }

    public function getNameAddressAttribute()
    {
        return $this->name.( $this->address != null ?? " (".$this->address.")");
    }

    public function getCustomerDetailsAttribute()
    {
        return $this->name." (".$this->phone.")".( $this->address != null ?? " (".$this->address.")");
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
     * Scope a query to only include Supplier models
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlySuppliers($query)
    {
        return $query->where('customer_type', '!=', 'customer')
            ->where('customer_type', '!=', 'broker')
            ->whereNotNull('account_type_id')
            ->whereNotNull('cash_or_bank_id');
    }

    /**
     * Scope a query to only include Customer models
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyCustomers($query)
    {
        return $query->where('customer_type', '!=', 'supplier')
            ->where('customer_type', '!=', 'broker')
            ->whereNotNull('account_type_id')
            ->whereNotNull('cash_or_bank_id');
    }


    /**
     * Scope a query to only include Customer models
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyBroker($query)
    {
        return $query->where('customer_type', '=', 'broker')
            ->whereNotNull('account_type_id')
            ->whereNotNull('cash_or_bank_id');
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
     * Get the member.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);

    }

    /**
     * Get the Division.
     */
    public function division()
    {
        return $this->belongsTo(Division::class);

    }
    /**
     * Get the upazilla.
     */
    public function upazilla()
    {
        return $this->belongsTo(Upazilla::class);

    }
    /**
     * Get the union.
     */
    public function union()
    {
        return $this->belongsTo(Union::class);

    }
    /**
     * Get the District.
     */
    public function district()
    {
        return $this->belongsTo(District::class);

    }
    /**
     * Get the District.
     */
    public function area()
    {
        return $this->belongsTo(Area::class);

    }
    /**
     * Get the District.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');

    }

    /**
     * Get the cash_bank.
     */
    public function cash_bank()
    {
        return $this->belongsTo(CashOrBankAccount::class, 'cash_or_bank_id', 'id');

    }
    /**
     * Relation with Account Type
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account_type()
    {
        return $this->belongsTo(AccountType::class,'account_type_id');
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
    /**
     * Relation with Account Type
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function previous_balance()
    {
        return $this->hasMany(
            TrackAccountHeadBalance::Class, 'account_type_id', 'account_type_id'
        );
    }


    /**
     * Relation with Transaction Details
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function transaction_details()
    {
        return $this->hasMany(
            TransactionDetail::Class,
            'account_type_id',
            'account_type_id'
        );
    }

    /**
     * Relation with Sales
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function sales()
    {
        return $this->hasMany( Sale::Class,'customer_id' );
    }

    public function totalDr()
    {
        return $this->transaction_details()
            ->selectRaw(' sum(amount) as totalDr')
            ->where('transaction_type', '=','dr')
            ->groupBy('transaction_type');
    }


    public function totalCr()
    {
        return $this->transaction_details()
            ->selectRaw(' sum(amount) as totalCr')
            ->where('transaction_type', '=','cr')
            ->groupBy('transaction_type');
    }

    /**
     * Relation with Transactions
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transactions::class,'supplier_id');
    }

    /**
     * Relation with submitted_document
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function submitted_document()
    {
        return $this->hasMany(SharerSubmittedDocument::class,'sharer_id','id');
    }

    /**
     * Get the Sharer Photo.
     *
     * @return string
     */
    public function getSharerPhotoAttribute()
    {
        return asset('storage/app/public/sharers/'. $this->photo);
    }

    /**
     * Get the Sharer files.
     *
     * @return string
     */
    public function getSharerfileAttribute()
    {
        return asset('storage/app/public/'. $this->file);
    }



    /**
     * Get the Created by User.
     */
    public function user_access()
    {
        return $this->belongsTo(User::class, 'id','user_id');
    }
}
