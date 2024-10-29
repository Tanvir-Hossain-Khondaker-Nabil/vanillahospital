<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationTransaction extends Model
{
    protected $guarded = [];


    public function quotation()
    {
        return $this->belongsTo( Quotation::class)->withTrashed();
    }

    public function transaction()
    {
        return $this->belongsTo( Transactions::class);
    }
}
