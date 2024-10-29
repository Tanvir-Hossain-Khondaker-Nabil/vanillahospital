<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaidCommission extends Model
{
    /**
     * Get the User.
     */
    public function employee()
    {
        return $this->belongsTo(EmployeeInfo::class,'employee_id', 'id');

    }
}
