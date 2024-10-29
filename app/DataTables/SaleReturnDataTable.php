<?php

namespace App\DataTables;

use App\Models\Sale;
use App\Models\SaleReturn;
use Yajra\DataTables\Services\DataTable;

class SaleReturnDataTable extends DataTable
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
            ->addColumn('action', function($modal){

                if(\Auth::user()->can(['member.sales.view_return'])) {
                    return '<a href="' . route('member.sales.view_return', ['id' => $modal->sale_id, 'code' => $modal->return_code]) . '" class="btn btn-xs btn-info"> <i class="fa fa-eye"></i> </a>';
                }else{
                    return '';
                }
            })
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
        $return = SaleReturn::authCompany()->groupBy('return_code');

        $query = Sale::whereHas('sales_return', function ($query)  {
            $query->groupBy('return_code');
        });

        return $this->applyScopes($return);
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
            // 'return_code',
            ['data' => 'return_code','title' => trans('common.total_discount')],
            // 'qty',
            ['data' => 'qty','title' => trans('common.qty')],
            // 'price',
            ['data' => 'price','title' => trans('common.price')],
            // 'return_qty',
            ['data' => 'return_qty','title' => trans('common.return_qty')],
            // 'return_price',
            ['data' => 'return_price','title' => trans('common.return_price')],
            // 'created_at',
            ['data' => 'created_at','title' => trans('common.created_at')],

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'SaleReturn_' . date('YmdHis');
    }
}
