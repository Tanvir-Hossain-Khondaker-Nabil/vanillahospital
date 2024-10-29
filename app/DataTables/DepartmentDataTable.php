<?php

namespace App\DataTables;

use App\Models\Department;
use Yajra\DataTables\Services\DataTable;

class DepartmentDataTable extends DataTable
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
            ->editColumn('active_status', function($model){
                $type = $model->active_status == 1 ? 'label label-primary': 'label label-warning';
                $text = $model->active_status == 1 ? 'Active': 'Inactive';
                return "<label class='".$type."'>".ucfirst($text)."</label>";
            })
            ->addColumn('action', function($model) {
                return view('common._edit-button', ['model' => $model, 'route' => 'member.department']);
            })
            ->rawColumns(['active_status','action'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Department::authCompany();

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
            // ['data'=>'company', 'name'=>'company.name', 'title'=>'Company'],
            'active_status'=>['title'=>trans('common.active_status')],

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Department_' . date('YmdHis');
    }
}