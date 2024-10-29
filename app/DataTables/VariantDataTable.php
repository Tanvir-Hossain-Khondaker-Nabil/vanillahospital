<?php

namespace App\DataTables;

use App\Models\Category;
use App\Models\Variant;
use Yajra\DataTables\Services\DataTable;

class VariantDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function  ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->editColumn('variant_values', function($modal) {
                $arrays =  $modal->variant_values->pluck('name')->toArray();

                return implode(', ', $arrays);

            })
            ->addColumn('action', function($modal) {
                return view('common._action-button', ['model' => $modal, 'route' => 'member.variants']);
            })
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Category $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Variant::with('variant_values')->latest();

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
            'title'=>['title'=>trans('common.title')],
            ['name' => 'variant_values', 'data' => 'variant_values',  'title' => trans('common.values'), 'searchable'=>false ],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Variants_' . date('YmdHis');
    }
}
