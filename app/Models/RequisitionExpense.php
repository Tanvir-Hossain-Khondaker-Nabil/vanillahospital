<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RequisitionExpense extends Model
{
    protected $guarded = [];

    protected $appends = ['date_format'];


    public function getDateFormatAttribute()
    {
        return db_date_month_year_format($this->date);
    }


    public function scopeAuthCompany($query)
    {

        if (!\Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', Auth::user()->company_id);

        return $query;
    }


    public function expenseDetails()
    {
        return $this->hasMany(RequisitionExpenseDetail::class, 'requisition_expense_id', 'id');
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }


    public function employee()
    {
        return $this->belongsTo(EmployeeInfo::class, 'employee_id', 'id');
    }


    public function transaction()
    {
        return $this->belongsTo(Transactions::class);
    }
}
