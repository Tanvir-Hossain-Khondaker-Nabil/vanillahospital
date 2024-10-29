<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class JournalEntryDetail extends Model
{

    use LogsActivity;

    protected $fillable = [
    'document_date', 'event_date', 'transaction_id', 'transaction_details_id',
    'account_type_id', 'payment_method_id','transaction_type', 'source_reference', 'amount',
    ];

    protected $appends = [
        'format_amount', 'uc_transaction_type', 'date_format', 'document_date_format',
        'event_date_format'
    ];

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
     * Get the Event Date Format.
     *
     * @return string
     */
    public function getEventDateFormatAttribute()
    {
        return db_date_month_year_format($this->event_date);
    }

    /**
     * Get the Document Date Format.
     *
     * @return string
     */
    public function getDocumentDateFormatAttribute()
    {
        return db_date_month_year_format($this->document_date);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account_type(){
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment_method(){
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction(){
        return $this->belongsTo(Transactions::class, 'transaction_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction_details(){
        return $this->belongsTo(TransactionDetail::class, 'transaction_details_id');
    }


    public function scopeAuthCompany($query)
    {

        if(Auth::user()->can(['super-admin']))
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
