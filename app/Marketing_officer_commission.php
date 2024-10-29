<?php

namespace App;

use App\Models\HospitalService;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Marketing_officer_commission extends Model
{

    use SoftDeletes;
    protected $fillable = [
        'company_id',
        'marketing_officer_id',
        'hospital_services_id',
        'commission_type',
        'commission_amount',
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
    public function officer(){
        return $this->hasOne(Marketing_officer::class,'marketing_officer_id','id');
    }
    public function service(){
        return $this->belongsTo(HospitalService::class,'hospital_services_id','id');
    }
}
