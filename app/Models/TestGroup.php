<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestGroup extends Model
{
    protected $guarded = [];


    public function scopeAuthCompany($query)
    {

        if (!\Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }

    public function specimen(){

        return $this->belongsTo(Specimen::class, 'specimen_id');
    }
}