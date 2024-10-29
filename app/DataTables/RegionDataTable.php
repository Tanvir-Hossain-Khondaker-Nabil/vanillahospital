<?php

namespace App\DataTables;

use App\Models\Region;
use App\Models\Area;
use Yajra\DataTables\Services\DataTable;

class RegionDataTable extends DataTable
{
    public function ajax()
    {

        return datatables()
            ->eloquent($this->query())
            ->editColumn('active_status', function($row){
                $type = $row->active_status == 1 ? 'label label-primary': 'label label-warning';
                $text = $row->active_status == 1 ? 'Active': 'Inactive';
                return "<label class='".$type."'>".ucfirst($text)."</label>";
            })
            ->addColumn('action', function($area) {
                return view('common._edit-button', ['model' => $area, 'route' => 'member.regions']);
            })
            ->rawColumns(['active_status', 'action'])
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
        $query = Region::orderBy('id','desc');

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
            'name',
            'active_status',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Region_' . date('YmdHis');
    }
}
