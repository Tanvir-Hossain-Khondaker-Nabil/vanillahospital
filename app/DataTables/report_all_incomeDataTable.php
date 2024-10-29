<?php

namespace App\DataTables;

use App\Http\Traits\TransactionDetailsTrait;
use App\Models\Transactions;
use Yajra\DataTables\Services\DataTable;

class report_all_incomeDataTable extends DataTable
{
    use TransactionDetailsTrait;
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->addColumn('action', 'report_all_income.action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Transactions $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query  = $this->transaction_full_details(
            $member = true, $company=true, $page = 'ajax', $tr_payment=false, $updated_user=false, $tr_category=false,
            $select_column="", $group_tr_code=false, $group_tr_type=false, $order = 'DESC', $condition='',
            $condition_col='', $value='', $multiple_codition = []
        );

        return $query;
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
                    ->addAction(['width' => '80px'])
                    ->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id',
            'transaction_code'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'report_all_income_' . date('YmdHis');
    }
}
