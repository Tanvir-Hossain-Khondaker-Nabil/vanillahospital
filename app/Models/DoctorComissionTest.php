<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorComissionTest extends Model
{
    protected $guarded = [];
    protected $table ='doctor_comission_test';


    public function subTestGroup()
    {
        return $this->belongsTo(SubTestGroup::class,'sub_test_group_id', 'id');

    }

    public function doctorComission()
    {
        return $this->belongsTo(DoctorComission::class,'doctor_comission_id', 'id')->with('testGroup');

    }

}