<?php

namespace App\DataTables;

use App\Models\AccountType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class SystemUsersDataTable extends DataTable
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
            ->of($this->query())
            ->addIndexColumn()
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
        $query = DB::connection("landlord")->table('tenants')->latest()->get();

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
            ->minifiedAjax();
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            ['data'=>'DT_RowIndex','name'=>'DT_RowIndex','title'=>'#SL', 'orderable'=> false, 'searchable'=> false ],
            'name',
            'domain',
            'email',
            'password',
            'full_name',
            'phone',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'systemusers' . date('YmdHis');
    }
}
