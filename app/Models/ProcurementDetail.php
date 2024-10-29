<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProcurementDetail extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function item(){
        return $this->belongsTo(Item::class);
    }
    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function scopeAuthCompany($query)
    {
        if(!Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', Auth::user()->company_id);

        return $query;
    }
}
