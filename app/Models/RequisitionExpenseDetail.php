<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisitionExpenseDetail extends Model
{
    protected $guarded = [];

    public function requisition_expense()
    {
        return $this->belongsTo(RequisitionExpense::class, 'requisition_expense_id', 'id');
    }

    public function expenseType()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id', 'id');
    }

}
