<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Weekend extends Model
{
    protected $guarded = [];

    /**
     * Get the Company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * Get the employee.
     */
    public function employee()
    {
        return $this->belongsTo(EmployeeInfo::class, 'employee_id', 'id');
    }


    /**
     * Scope a query to only Company
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthCompany($query)
    {
        if(!Auth::user()->can(['super-admin']))
            $query =  $query->where('company_id', Auth::user()->company_id);

        return $query;
    }
}
