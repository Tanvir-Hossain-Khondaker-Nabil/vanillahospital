<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EmpLeave extends Model
{
    protected $table = 'emp_leave';

    protected $guarded = [];
    protected $appends = ["attached_file"];

    public $timestamps = false;


    public function scopeActive($query)
    {
        return $query->where('status', "!=", 0);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * Get the employee.
     */
    public function employee()
    {
        return $this->belongsTo(EmployeeInfo::class,'emp_id', 'id');

    }

    /**
     * Get the leave.
     */
    public function leave()
    {
        return $this->belongsTo(Leave::class,'leave_id', 'id');

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

    /**
     * Get the Sharer files.
     *
     * @return string
     */
    public function getAttachedFileAttribute()
    {
        return asset('storage/app/public/'. $this->attachment);
    }



}
