<?php

namespace App\DataTables;

use App\Models\DealerSale;
use App\Models\Sale;
use Yajra\DataTables\Services\DataTable;

class DealerSalesDataTable extends DataTable
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
            ->editColumn('customer_name', function($modal){
                return $modal->customer ? $modal->customer->store_name : '';
            })

            ->addColumn('action', function($modal){
                return view('common._action-buttons', ['model' => $modal, 'route' => 'member.dealer_sales']);
            })
            ->rawColumns([ 'action','return_sale'])
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
        $query  = DealerSale::authUser()->authCompany()->latest()->with(['customer']);

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
            'sale_code',
            ['name'=>'customer.store_name','data'=>'customer_name','title'=>'Customer Name'],
            'grand_total',
            'shipping_charge',
            'paid_amount',
            'due'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'DealerSales_' . date('YmdHis');
    }
}
