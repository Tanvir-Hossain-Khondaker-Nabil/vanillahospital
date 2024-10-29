<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WarehouseStockHistory extends Model
{
    protected $guarded = [];


    public function scopeWarehouse($query, $warehouse_id = "")
    {
        if($warehouse_id != "")
            $query =  $query->where('warehouse_id', $warehouse_id);

        return $query;
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
