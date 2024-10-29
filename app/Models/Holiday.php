<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
// use Auth;
class Holiday extends Model
{
    // use SoftDeletes;

    protected $fillable = [
      'title', 'start_date', 'end_date', 'type', 'company_id'
    ];

    public function scopeAuthCompany($query)
    {

        if(!\Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }
}