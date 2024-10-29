<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WarehouseHistory extends Model
{
    protected $guarded = [];

    protected $appends = ['type'];

    public function scopeWarehouse($query, $warehouse_id = "")
    {
        if($warehouse_id != "")
            $query =  $query->where('warehouse_id', $warehouse_id);

        return $query;
    }


    /**
     * Get the warehouse.
     */
    public function fromWarehouse($id)
    {
        return Warehouse::findOrFail($id);

    }

    /**
     * Get the warehouse.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);

    }
    /**
     * Get the Item.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);

    }


    /**
     * Get the Company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
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


    public function getTypeAttribute()
    {

        if($this->model == "SaleDetail")
        {
            $loadingType = "Unload";
        }elseif ($this->model == "PurchaseDetail"){
            $loadingType = "Load";
        }else{
            $loadingType = "Transfer";
        };

        return $loadingType;
    }

}
