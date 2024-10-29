<?php

namespace App\DataTables;

use App\Models\CashOrBankAccount;
use Yajra\DataTables\Services\DataTable;

class CashAndBankAccountDataTable extends DataTable
{
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
            ->editColumn('status', function($row){
                $type = $row->status == 'active' ? 'label label-primary': 'label label-warning';
                return "<label class='".$type."'>".ucfirst($row->status)."</label>";
            })
            ->addColumn('action', function($cash_and_bank) {
                return view('common._action-button', ['model' => $cash_and_bank, 'route' => 'member.cash_or_bank_accounts']);
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CashOrBankAccount $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {

        $query = CashOrBankAccount::withoutSupplierCustomer()->authCompany()->authMember();

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
            'id'=> ['title'=>trans('common.id')],
            'title' => ['title' => trans('common.title')],
            [
                'name' => 'initial_balance',
                'data' => 'format_initial_balance',
                'title' => trans('common.initial_balance').' (TK)',
            ],
            [
                'name' => 'current_balance',
                'data' => 'format_current_balance',
                'title' => trans('common.current_balance').' (TK)',
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
        return 'CashAndBankAccount_' . date('YmdHis');
    }
}