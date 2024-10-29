<?php

namespace App\DataTables;

use App\Models\TransactionCategory;
use Yajra\DataTables\Services\DataTable;

class ExpenseTransactionCategoriesDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables($query);
//            ->addColumn('action', function($expenseCategory) {
//                return view('common._action-button', ['model' => $expenseCategory, 'route' => 'admin.transaction_categories']);
//            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TransactionCategory $model)
    {
        return $model->newQuery()->where('type', 'expense');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->addAction(['width' => '80px']);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'display_name',
            'type'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'ExpenseTransactionCategories_' . date('YmdHis');
    }
}
