<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Quotation extends Model
{
    protected $guarded = [];

    protected $appends = ['date_format'];



    public function quoteTerms()
    {
        return $this->belongsToMany(QuotationTerm::class, 'quote_terms', 'quotation_id', 'quote_term_id')->whereNull('term_id');
    }

    public function quoteSubTerms($id, $subterm)
    {
        return $this->belongsToMany(QuotationTerm::class, 'quote_terms', 'quotation_id', 'quote_term_id')->where('term_id', $id)
            ->whereIn('id', $subterm)->first();
    }


    public function quoteAttention()
    {
        return $this->belongsTo( QuoteAttention::class, 'quote_attention_id', 'id')->withTrashed();
    }

    public function quoteCompany()
    {
        return $this->belongsTo( QuotationCompany::class, 'quotationer_id', 'id')->withTrashed();
    }

    public function quotingContactBy()
    {
        return $this->belongsTo( Quoting::class, 'contact_quoting_id', 'id')->withTrashed();
    }

    public function quotingBy()
    {
        return $this->belongsTo( Quoting::class, 'quoting_id', 'id')->withTrashed();
    }

    public function company()
    {
        return $this->belongsTo( Company::class);
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

    public function getDateFormatAttribute()
    {
        return db_date_month_year_format($this->quote_date);
    }

    public function quotation_details()
    {
        return $this->hasMany(QuotationDetail::class, 'quotation_id');
    }

    public function transactions()
    {
        return $this->hasMany(QuotationTransaction::class, 'quotation_id', 'id');
    }

    public function purchases($purchaseArr)
    {
        return Purchase::whereIn('id', $purchaseArr)->get();
    }

    public function sales($saleArr)
    {
        return Sale::whereIn('id', $saleArr)->get();
    }




}
