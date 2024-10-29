<?php

namespace App\DataTables;

use App\Models\Shift;
use Yajra\DataTables\Services\DataTable;

class ShiftDataTable extends DataTable
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
            ->editColumn('time_in', function($model){
                return  \Carbon\Carbon::parse($model->time_in)->format("g:i A");
            })
             ->editColumn('time_out', function($model){
                return  \Carbon\Carbon::parse($model->time_out)->format("g:i A");
            })
             ->editColumn('late', function($model){
                return  \Carbon\Carbon::parse($model->late)->format("g:i A");
            })
            ->editColumn('shift_type', function($model){
                $type = $model->shift_type == 1 ? 'label label-primary': 'label label-success';
                $text = $model->shift_type == 1 ? 'Night': 'Day';
                return "<label class='".$type."'>".ucfirst($text)."</label>";
            })
            ->addColumn('action', function($model) {
                return view('common._edit-button', ['model' => $model, 'route' => 'member.shift']);
            })
            ->rawColumns(['action', 'shift_type'])
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
        $query = Shift::authCompany();

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
            'time_in'=>['title'=>trans('common.time_in')],
            'time_out'=>['title'=>trans('common.time_out')],
            'late'=>['title'=>trans('common.late')],
            'shift_type' =>['searchable'=>false,'title'=>trans('common.shift_type')],

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Shift_' . date('YmdHis');
    }
}