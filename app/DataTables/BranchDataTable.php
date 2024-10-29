<?php

namespace App\DataTables;

use App\Models\Branch;
use Yajra\DataTables\Services\DataTable;

class BranchDataTable extends DataTable
{
    public function ajax()
    {

        return datatables()
            ->eloquent($this->query())
            ->editColumn('status', function($row){
                $type = $row->status == 'active' ? 'label label-primary': 'label label-warning';
                return "<label class='".$type."'>".ucfirst($row->status)."</label>";
            })
            ->addColumn('action', function($branch) {
                return view('common._action-button', ['model' => $branch, 'route' => 'member.branch']);
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Branch $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Branch::authMember()->authCompany();

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
            'display_name',
            'phone',
            'email',
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
        return 'Branch_' . date('YmdHis');
    }
}
