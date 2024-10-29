<?php

namespace App\DataTables;

use App\Models\Holiday;
;
use Yajra\DataTables\Services\DataTable;

class HolidayDataTable extends DataTable
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
            ->editColumn('type', function($model){
                $type = $model->type == 'govt' ? 'label label-primary': 'label label-warning';
                $text = $model->type == 'govt' ? 'Govt Holiday': 'Holiday';
                return "<label class='".$type."'>".ucfirst($text)."</label>";
            })
            ->addColumn('action', function($model) {
                return view('common._edit-button', ['model' => $model, 'route' => 'member.holiday']);
            })
            ->rawColumns(['type','action'])
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
        $query = Holiday::authCompany();

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
            'title'=>['title'=>trans('common.title')],
            'start_date'=>['title'=>trans('common.start_date')],
            'end_date'=>['title'=>trans('common.end_date')],
            // ['data'=>'company', 'name'=>'company.name', 'title'=>'Company'],
            'type'=>['title'=>trans('common.type')],

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Holiday_' . date('YmdHis');
    }
}