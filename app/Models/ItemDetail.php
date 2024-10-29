<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ItemDetail extends Model
{
    protected $table = "product_details";

    protected $guarded = [];

    public function items(){

        return $this->hasMany(Item::class);
    }

    public function scopeAuthCompany($query)
    {

        if(!Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', Auth::user()->company_id);

        return $query;
    }




}
