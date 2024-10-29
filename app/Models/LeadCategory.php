<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadCategory extends Model
{
    use SoftDeletes;

    protected $fillable =[
        'category_image','name','display_name','parent_id','status','created_by','updated_by','company_id',
         ];

         public function scopeAuthCompany($query)
         {

             if(!\Auth::user()->can(['super-admin']))
                 $query =  $query->where('company_id', \Auth::user()->company_id);

             return $query;
         }
}
