<?php

namespace App\DataTables;

use App\Models\AccountType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class AccountTypesDataTable extends DataTable
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
            ->addColumn('permanently_delete', function($accountType) {
                return Auth::user()->hasRole('super-admin') && Auth::user()->can(['admin.account_types.force_delete']) ? '<a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm" data-target="'.route('admin.account_types.force_delete', $accountType->id).'" class="btn btn-xs btn-danger">
                    <i class="fa fa-trash"></i>
                </a>' : "";
            })
            ->addIndexColumn()
            ->rawColumns([ 'permanently_delete', 'action'])
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
        if(!Auth::user()->can(['super-admin']))
            $query = AccountType::onlyAccount()->authCompany()->orderBy('id','asc');
        else
            $query = AccountType::onlyAccount()->authCompany()->withTrashed()->orderBy('id','asc');

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
                'title'=>trans('common.account_code'),
            ],
            'display_name'=>['title'=> trans('common.display_name')],
            [
                'name' => 'parent_name',
                'data' => 'parent_name',
                'title' =>trans('common.parent_name'),
                'searchable'=>false,
                'orderable'=>false
            ],
            'status'=>['title'=> trans('common.status')],
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