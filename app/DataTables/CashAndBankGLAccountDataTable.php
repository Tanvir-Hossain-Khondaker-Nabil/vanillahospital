<?php

namespace App\DataTables;

use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use Yajra\DataTables\Services\DataTable;

class CashAndBankGLAccountDataTable extends DataTable
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
            ->editColumn('parent_name', function ($accountType){
                return isset($accountType->parent_name) ? $accountType->parent_name->display_name : '';
            })
            ->addColumn('action', function($accountType) {
                return view('common._action-button', ['model' => $accountType, 'route' => 'admin.account_types']);
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
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
        $cash_banks = CashOrBankAccount::withoutSupplierCustomer()->authCompany()->authMember()->get()->pluck('account_type_id');

        $query = AccountType::whereIn('id', $cash_banks)->authCompany()->orderBy('id','asc');
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
            'id'=>['title'=> trans('common.id')],
            'account_code' => [
                'searchable'=>false,
                'orderable'=>false,
                'title' => trans('common.account_code')
            ],
            'display_name' => ['title' => trans('category.display_name')],
            [
                'name' => 'parent_name',
                'data' => 'parent_name',
                'title' => trans('common.parent_name'),
                'searchable'=>false,
                'orderable'=>false
            ],
            'status'=> ['title' => trans('common.status')]
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