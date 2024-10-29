<?php

namespace App\DataTables;

use App\Models\QuotationTerm;
use Yajra\DataTables\Services\DataTable;

class QuotationSubTermDataTable extends DataTable
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
            ->editColumn('term_id', function ($modal){
                return $modal->term_id > 0 ? $modal->parentTerm->name : "";
            })
            ->editColumn('status', function ($modal){
                return $modal->status == 0 ? "Inactive" : "Active";
            })
            ->addColumn('action', function($modal) {
                return view('common._action-button', ['model' => $modal, 'route' => 'member.quotation_sub_terms']);
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
        $query = QuotationTerm::subTerm()->latest()->with('parentTerm');

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
            ['name' => 'name', 'data' => 'name',  'title' => "Terms & Conditions Title" ],
            [
                'name' => 'term_id',
                'data' => 'term_id',
                'title' => "Parent Term"
            ],
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
        return 'QuotationSubTerm_' . date('YmdHis');
    }
}
