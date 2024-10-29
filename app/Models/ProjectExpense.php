<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectExpense extends Model
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
            $query = $query->where('company_id', \Auth::user()->company_id);

        return $query;
    }


    public function projectExpenseDetails()
    {
        return $this->hasMany(ProjectExpenseDetail::class, 'project_expense_id', 'id');
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }


    public function project()
    {
        return $this->belongsTo(Project::class);
    }


    public function transaction()
    {
        return $this->belongsTo(Transactions::class);
    }


}
