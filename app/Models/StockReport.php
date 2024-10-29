<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class StockReport extends Model
{
    use LogsActivity;

    protected $table = 'stock_reports';

    protected $guarded = [];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];


    public function setOpeningStockAttribute($value){
        $this->attributes['opening_stock'] = round($value, 2);
    }
    public function setPurchaseQtyAttribute($value){
        $this->attributes['purchase_qty'] = round($value, 2);
    }
    public function setSaleQtyAttribute($value){
        $this->attributes['sale_qty'] = round($value, 2);
    }

    public function setPurchaseReturnQtyAttribute($value){
        $this->attributes['purchase_return_qty'] = round($value, 2);
    }
    public function setSaleReturnQtyAttribute($value){
        $this->attributes['sale_return_qty'] = round($value, 2);
    }

    public function item()
    {
        return $this->belongsTo(Item::class)->withTrashed();
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
}
