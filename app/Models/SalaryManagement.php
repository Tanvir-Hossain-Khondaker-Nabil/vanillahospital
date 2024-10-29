<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SalaryManagement extends Model
{
    protected $guarded  = [];
    protected $table = "salary_management";

    public $timestamps = false;

    protected $appends = ['final_tax_amount', 'receive_amount'];

    public function getFinalTaxAmountAttribute()
    {

        if($this->tax_amount > 0 ){
           $tax =  $this->tax_amount;
        }else{
           $tax =  $this->tax_amount;
        }

        return $tax;
    }
    public function getReceiveAmountAttribute()
    {

        $receive =  $this->net_payable-$this->final_tax_amount;

        return $receive;
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
