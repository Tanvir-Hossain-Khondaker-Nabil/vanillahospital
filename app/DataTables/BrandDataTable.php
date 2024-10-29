<?php

namespace App\DataTables;

use App\Models\Brand;
use Yajra\DataTables\Services\DataTable;

class BrandDataTable extends DataTable
{
    public function ajax()
    {

        return datatables()
            ->eloquent($this->query())
            ->editColumn('status', function($row){
                $type = $row->status == 'active' ? 'label label-primary': 'label label-warning';
                return "<label class='".$type."'>".ucfirst($row->status)."</label>";
            })
            ->addColumn('action', function($brand) {
                return view('common._action-button', ['model' => $brand, 'route' => 'member.brand']);
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
        $query = Brand::authMember()->authCompany();

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
            'name'=>['title' => trans('common.name')]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Area_' . date('YmdHis');
    }
}
