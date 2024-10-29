<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationDetail extends Model
{
    protected $guarded = [];
    protected $appends = ['created_date'];


    public function quotation()
    {
        return $this->hasOne(Quotation::class, 'id', 'quotation_id');
    }

    public function item()
    {
        return $this->hasOne( Item::class, 'id', 'item_id')->withTrashed();
    }

    public function getCreatedDateAttribute()
    {
        return db_date_month_year_format($this->created_at);
    }
}
