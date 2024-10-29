<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class Variant extends Model
{
    use LogsActivity, SoftDeletes;

    protected $guarded = [];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated', 'deleted'];
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

    public function scopeActive($query)
    {
        return $query->where('status', "active");
    }

    public function variant_values()
    {
        return $this->hasMany(VariantValue::class,'variant_id');
    }

    public function items()
    {
        return $this->hasMany(ItemVariant::class,'variant_id');
    }
}
