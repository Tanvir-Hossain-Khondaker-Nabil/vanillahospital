<?php

namespace App\DataTables;

use App\Models\Sale;
use Yajra\DataTables\Services\DataTable;

class SalesDataTable extends DataTable
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
                return $modal->customer ? ($modal->customer->name != null ? $modal->customer->name : $modal->customer->phone) : '';
            })
            ->addColumn('return_sale', function($modal){
                return '<a href="'.route('member.sales.sales_return', $modal->id).'" class="btn btn-xs btn-info">
                            <i class="fa fa-reply"></i>
                        </a>';
            })
            ->addColumn('action', function($modal){
                return view('common._action-buttons', ['model' => $modal, 'route' => 'member.sales']);
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
        $query  = Sale::authUser()->authCompany()->latest()->with(['customer','transaction']);

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
            [
                'name'=>'transaction.transaction_code',
                'data'=>'transaction.transaction_code',
                'title'=>trans('common.transaction_code')
            ],
            'sale_code',
            ['name'=>'customer.name','data'=>'customer_name','title'=>trans('common.customer_name')],
            ['data' => 'grand_total','title' => trans('common.grand_total')],
            // 'grand_total',
            // 'shipping_charge',
            ['data' => 'shipping_charge','title' => trans('common.shipping_charge')],
            // 'paid_amount',
            ['data' => 'paid_amount','title' => trans('common.paid_amount')],
            // 'due'
            ['data' => 'due','title' => trans('common.due')],

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Sales_' . date('YmdHis');
    }
}
