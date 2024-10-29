<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class DueCollectionHistory extends Model
{
    use LogsActivity;

    protected $table = 'due_collection_histories';

    protected $guarded = [];

    protected $appends = ['date_format'];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];

    public function sale()
    {
        return $this->hasMany(Sale::class, 'inventory_type_id');
    }

    public function purchase()
    {
        return $this->hasMany(Purchase::class, 'inventory_type_id');
    }

    public function getDateFormatAttribute()
    {
        return db_date_month_year_format($this->collection_date);
    }

    public function sharer()
    {
        return $this->hasOne(SupplierOrCustomer::class, 'id','sharer_id');
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

    /**
     * Get the company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);

    }
}
