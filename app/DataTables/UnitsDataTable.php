<?php

namespace App\DataTables;

use App\Models\Unit;
use Yajra\DataTables\Services\DataTable;

class UnitsDataTable extends DataTable
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
            ->addColumn('action', function($unit) {
                return view('common._action-button', ['model' => $unit, 'route' => 'admin.units']);
            })
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Unit $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Unit::latest();

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
            ['name' => 'id', 'data' => 'id',  'title' => "ID" ],
            'display_name'=>['title' => trans('category.display_name')],
            'name' => ['title' => trans('common.name')],
//            'slug'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Units_' . date('YmdHis');
    }
}
