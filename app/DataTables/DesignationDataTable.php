<?php

namespace App\DataTables;

use App\Models\Designation;
use Yajra\DataTables\Services\DataTable;

class DesignationDataTable extends DataTable
{
    public function ajax()
    {

        return datatables()
            ->eloquent($this->query())
            ->editColumn('parent_designation_id', function ($v){
                return isset($v->parent_designation_id)  ? $v->parent_designation->name : '';
            })
            ->editColumn('active_status', function($row){
                $type = $row->active_status == 1 ? 'label label-primary': 'label label-warning';
                $text = $row->active_status == 1 ? 'Active': 'Inactive';
                return "<label class='".$type."'>".ucfirst($text)."</label>";
            })
            ->addColumn('action', function($row) {
                return view('common._edit-button', ['model' => $row, 'route' => 'member.designation']);
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
        $query = Designation::with(['parent_designation'])->latest();

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
            'id'=>['title'=>trans('common.id')],
            'name'=>['title'=>trans('common.name')],
//            'commission_area',
            'salary'=>['title'=>trans('common.salary')],
//            'commission_percentage',
            [
                'name'=>'parent_designation.name',
                'title'=>trans('common.head_of_designation'),
                'data'=>'parent_designation_id',
                'searchable'=>false,
                'orderable'=>false
            ],
            'active_status'=>['title'=>trans('common.active_status')]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Designation_' . date('YmdHis');
    }
}