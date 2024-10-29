<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAttendence extends Model
{
    protected $guarded = [];


    /**
     * Get the employee.
     */
    public function employee()
    {
        return $this->belongsTo(EmployeeInfo::class,'employee_id', 'id');

    }

    /**
     * Get the company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class,'company_id', 'id');

    }

    /**
     * Get the shift.
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class,'shift_id', 'id');

    }

    /**
     * Get the shift.
     */
    public function assign_shift()
    {
        return $this->belongsTo(Shift::class,'assign_shift_id', 'id');

    }

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class,'created_by', 'id');

    }


}
