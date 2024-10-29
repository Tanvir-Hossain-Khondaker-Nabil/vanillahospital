<?php

namespace App\DataTables;

use App\Models\Transactions;
use Yajra\DataTables\Services\DataTable;

class TransactionTransferDataTable extends DataTable
{
    /**
     * Build Ajax class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->editColumn('accounts', function($model){
                return isset($model->cash_or_bank_account->account_type) ? $model->cash_or_bank_account->account_type->display_name : '';
            })
//            ->editColumn('transaction_category', function($model){
//                return isset($model->transaction_category->display_name) ? $model->transaction_category->display_name : '';
//            })
//            ->addColumn('action', function($model) {
//                return view('common._action-button', ['model' => $model, 'route' => 'member.transaction']);
//            })
//            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Transactions $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Transactions::authMember()->authCompany()->onlyTransfer();

        return $this->applyScopes($query);
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
            'transaction_code',
            [
                'name' => 'transaction_method',
                'data' => 'transaction_method',
                'title' => 'Type'
            ],
            'cash_or_bank_id' => [
                'name' => 'cash_or_bank_account.account_type.display_name',
                'data' => 'accounts',
                'title' => 'Accounts',
                'orderable' => false,
                'searchable' => false
            ],
            [
                'name' => 'amount',
                'data' => 'format_amount',
                'title' => 'Amount'
            ]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'TransactionTransfer_' . date('YmdHis');
    }
}
