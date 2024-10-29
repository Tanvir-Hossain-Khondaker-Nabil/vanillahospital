<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class WarehouseStock extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $fillable = ['item_id', 'stock'];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function item()
    {
        return $this->belongsTo(Item::class)->withTrashed();
    }

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
    /**
     * Get the company.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);

    }


}
