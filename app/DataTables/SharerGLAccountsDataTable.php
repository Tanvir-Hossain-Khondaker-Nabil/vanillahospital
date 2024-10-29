<?php

namespace App\DataTables;

use App\Models\AccountType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class SharerGLAccountsDataTable extends DataTable
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
     * @param \App\Models\AccountType $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = AccountType::onlySharerAccount()->authCompany()->orderBy('id','asc');
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
            'id',
            'account_code' => [
                'searchable'=>false,
                'orderable'=>false
            ],
            'display_name',
            [
                'name' => 'parent_name',
                'data' => 'parent_name',
                'title' => 'Parent Name',
                'searchable'=>false,
                'orderable'=>false
            ],
            'status'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'AccountTypes_' . date('YmdHis');
    }
}
