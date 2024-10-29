<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class SaleDetails extends Model
{
    use LogsActivity;

    protected $table = 'sales_details';

    protected $guarded = [];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function sale()
    {
        return $this->hasOne(Sale::class, 'id', 'sale_id')->orderBy('date');
    }

    public function item()
    {
        return $this->hasOne( Item::class, 'id', 'item_id')->withTrashed();
    }


    public function sale_returns($sale_id, $item_id)
    {
        return SaleReturn::where('sale_id', $sale_id)
            ->where('item_id', $item_id)->with('item')->get();
    }


    public function return_qty($sale_id, $item_id)
    {
        return SaleReturn::where('sale_id', $sale_id)
            ->where('item_id', $item_id)->sum('return_qty');
    }


    public function warehouseLastUnload($item_id)
    {
        $lastUnload = WarehouseHistory::where('model', "SaleDetail")
            ->where('item_id', $item_id)
            ->orderBy('date', 'desc')
            ->first();

        return $lastUnload ? $lastUnload->qty : 0;
    }

    public function warehouseItemQty($sale_id, $item_id)
    {
        return WarehouseHistory::where('model', "SaleDetail")
            ->where('model_id', $sale_id)
            ->where('item_id', $item_id)
            ->sum('qty');
    }

    public function warehouse($sale_id, $item_id)
    {
        return WarehouseHistory::where('model', "SaleDetail")
            ->where('model_id', $sale_id)
            ->where('item_id', $item_id)
            ->get();
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

    /**
     * Get the company.
     */
    public function area()
    {
        return $this->belongsTo(Area::class);

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

    /**
     * Get the member.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);

    }
}
