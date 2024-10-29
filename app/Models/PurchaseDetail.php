<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseDetail extends Model
{
    use LogsActivity;
    protected $guarded = [];
    protected $appends = ['created_date'];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function purchases()
    {
        return $this->hasOne(Purchase::class, 'id', 'purchase_id');
    }

    public function item()
    {
        return $this->hasOne( Item::class, 'id', 'item_id')->withTrashed();
    }

    public function purchase_returns($purchase_id, $item_id)
    {
        return ReturnPurchase::where('purchase_id', $purchase_id)
            ->where('item_id', $item_id)->with('item')->get();
    }

    public function return_qty($purchase_id, $item_id)
    {
        return ReturnPurchase::where('purchase_id', $purchase_id)
            ->where('item_id', $item_id)->sum('return_qty');
    }


    public function getCreatedDateAttribute()
    {
        return db_date_month_year_format($this->created_at);
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



    public function warehouseItemQty($sale_id, $item_id)
    {
        return WarehouseHistory::where('model', "PurchaseDetail")
            ->where('model_id', $sale_id)
            ->where('item_id', $item_id)
            ->sum('qty');
    }

    public function warehouse($sale_id, $item_id)
    {
        return WarehouseHistory::where('model', "PurchaseDetail")
            ->where('model_id', $sale_id)
            ->where('item_id', $item_id)
            ->get();
    }


    public function warehouseLastUnload($item_id)
    {
        $lastUnload = WarehouseHistory::where('model', "PurchaseDetail")
            ->where('item_id', $item_id)
            ->orderBy('date', 'desc')
            ->first();

        return $lastUnload ? $lastUnload->qty : 0;
    }


}
