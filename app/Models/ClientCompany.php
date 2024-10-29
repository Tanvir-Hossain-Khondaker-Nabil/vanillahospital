<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientCompany extends Model
{
    use SoftDeletes;

    protected $table = 'quotationers';

    protected $fillable = [
           'company_name','designation','contact_no','address_1','address_2','status','company_id','type',

    ];

    public function scopeAuthCompany($query)
    {

        if(!\Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }
}