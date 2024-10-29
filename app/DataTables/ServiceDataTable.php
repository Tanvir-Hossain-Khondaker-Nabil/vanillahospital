<?php

namespace App\DataTables;

use App\Models\Serviceing;
use Yajra\DataTables\Services\DataTable;

class ServiceDataTable extends DataTable
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
                return view('common._action-button', ['model' => $model, 'route' => 'member.services']);
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Area $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Serviceing::authCompany();

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
            'title'=>['title' => trans('common.title')],
            'price'=>['title' => trans('common.price')],
            'status'=>['title' => trans('common.status')]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Service_' . date('YmdHis');
    }
}
