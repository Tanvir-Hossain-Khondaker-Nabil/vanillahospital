<?php

namespace App\DataTables;

use App\Models\RepairOrder;
use Yajra\DataTables\Services\DataTable;

class RepairOrderDataTable extends DataTable
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
            ->addColumn('action', function($modal){
                return view('common._all_action-button', ['model' => $modal, 'route' => 'member.repair_orders']);
            })
            ->rawColumns([ 'action'])
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
        $query  = RepairOrder::authCompany()->latest()->with(['customer','transaction'])
            ->has('repair_items')->has("transaction");

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
            ['data' => 'token','title' => trans('common.token')],
            [
                'name'=>'transaction.transaction_code',
                'data'=>'transaction.transaction_code',
                'title'=>trans('common.transaction_code')
            ],
            ['data' => 'product_name','title' => trans('common.product_name')],
            ['name'=>'customer.name','data'=>'customer_name','title'=>trans('common.customer_name')],
            ['data' => 'amount_to_pay','title' => trans('common.amount_to_pay')],
            ['data' => 'paid','title' => trans('common.paid_amount')],
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
        return 'RepairOrders_' . date('YmdHis');
    }
}
