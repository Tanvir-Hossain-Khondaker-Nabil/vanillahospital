<?php

namespace App\DataTables;

use App\Models\RequisitionDetail;
use Yajra\DataTables\Services\DataTable;

class PurchaseFormRequisitionDataTable extends DataTable
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
            ->editColumn('item_name', function($modal){
                return $modal->item ? $modal->item->item_name : '';
            })
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Purchase $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query  = RequisitionDetail::where('purchase_status', 0)->orderBy('id','desc');


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
                    ->minifiedAjax();
//                    ->addAction(['width' => '80px'])
//                    ->parameters($this->getBuilderParameters());
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
            ['name' => 'item.item_name', 'data' => 'item_name',  'title' => "Item name" ],
            'unit',
            'qty',
            'price',
            'total_price',
            'description',

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'PurchaseFormRequisitionDataTable' . date('YmdHis');
    }
}
