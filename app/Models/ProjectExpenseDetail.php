<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectExpenseDetail extends Model
{
    protected $guarded = [];

    public function project_expense()
    {
        return $this->belongsTo(ProjectExpense::class, 'project_expense_id', 'id');
    }

    public function projectExpenseType()
    {
        return $this->belongsTo(ProjectExpenseType::class, 'project_expense_type_id', 'id');
    }


}
