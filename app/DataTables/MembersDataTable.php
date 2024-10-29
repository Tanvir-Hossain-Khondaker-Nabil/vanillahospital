<?php

namespace App\DataTables;

use App\Models\Member;
use App\User;
use Carbon\Carbon;
use Yajra\DataTables\Services\DataTable;

class MembersDataTable extends DataTable
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
        ->editColumn('expire_date', function($row) {
                return date_month_year_format($row->expire_date);
        })
        ->addColumn('action', function($member) {
            return view('common._edit-button', ['model' => $member, 'route' => 'admin.members']);
        })
        ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Member $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Member::query();

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
            'member_code',
            'api_access_key',
            'expire_date'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Members_' . date('YmdHis');
    }
}
