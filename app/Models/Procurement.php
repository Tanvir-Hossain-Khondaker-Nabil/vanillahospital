<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
class Procurement extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    
    public function scopeAuthCompany($query)
    {
        if(!Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', Auth::user()->company_id);

        return $query;
    }
    public function procurement_details(){
        return $this->hasMany(ProcurementDetail::class)->with(['item','department']);
    }
    
}
