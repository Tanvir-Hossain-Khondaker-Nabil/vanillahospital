<?php

namespace App;

use App\Models\Room;
use App\IpdFinalBill;
use Illuminate\Database\Eloquent\Model;

class IpdPatientInfo extends Model
{
    protected $guarded = [];
    protected $table = 'ipd_patient_info';
    public function scopeAuthCompany($query)
    {

        if (!auth()->user()->can(['super-admin']))
            $query = $query->where('company_id', auth()->user()->company_id);

        return $query;
    }
    public function cabin(){
        return $this->belongsTo(Room::class,'cabin_no','id');
    }
    public function finalBill(){
        return $this->hasMany(IpdFinalBill::class,'invoice_order_id','patient_info_id');
    }
}
