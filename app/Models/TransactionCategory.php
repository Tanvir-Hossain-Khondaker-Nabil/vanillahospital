<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionCategory extends Model
{
    protected $table = 'transaction_categories';

    /**
     * Scope a query to only include Income Transaction Category
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIncome($query)
    {
        return $query->where('type', '=', 'income');
    }

    /**
     * Scope a query to only include Expense Transaction Category
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpense($query)
    {
        return $query->where('type', '=', 'expense');
    }
}
