<?php

namespace App\DataTables;

use App\Models\Bank;
use App\Models\BankBranch;
use Yajra\DataTables\Services\DataTable;

class BankBranchDataTable extends DataTable
{
    public function ajax()
    {

        return datatables()
            ->eloquent($this->query())
            ->editColumn('status', function($row){
                $type = $row->status == 'active' ? 'label label-primary': 'label label-warning';
                return "<label class='".$type."'>".ucfirst($row->status)."</label>";
            })
            ->addColumn('action', function($model) {
                return view('common._action-button', ['model' => $model, 'route' => 'member.bank_branch']);
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
        $query =  BankBranch::latest();

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
            'branch_name',
//            'short_name',
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
        return 'BankBranch_' . date('YmdHis');
    }
}
