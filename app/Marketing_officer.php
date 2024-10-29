<?php

namespace App;

use App\Models\HospitalService;
use Auth;
use Illuminate\Database\Eloquent\Model;

class Marketing_officer extends Model
{
    //
    protected $fillable = [
        'company_id',
        'name',
        'designation',
        'description',
        'image',
        'contact_no',
        'address',
        'status',
        'operator_id',
        'operator_name',
    ];
    public function scopeAuthCompany($query)
    {

        if (!auth()->user()->can(['super-admin']))
            $query = $query->where('company_id', auth()->user()->company_id);

        return $query;
    }
    public function commission(){
        return $this->belongsToMany(Marketing_officer_commission::class,'marketing_officer_id','id');
    }
    public function service(){
        return $this->belongsToMany(HospitalService::class,'hospital_services_id','id');
    }
}
