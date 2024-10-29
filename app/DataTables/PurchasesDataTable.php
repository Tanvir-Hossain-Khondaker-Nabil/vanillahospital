<?php

namespace App\DataTables;

use App\Models\Purchase;
use Yajra\DataTables\Services\DataTable;

class PurchasesDataTable extends DataTable
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
            ->editColumn('supplier_name', function($modal){
                return $modal->supplier ? $modal->supplier->name : '';
            })
            ->addColumn('return_purchase', function($modal){

                if(\Auth::user()->can(['member.purchase_return.edit'])) {
                    return '<a href="' . route('member.purchase_return.edit', $modal->id) . '" class="btn btn-xs btn-info"><i class="fa fa-reply"></i></a>';
                }else{
                    return "";
                }

            })
            ->addColumn('action', function($modal){
                return view('common._action-buttons', ['model' => $modal, 'route' => 'member.purchase']);
            })
            ->rawColumns(['action','return_purchase'])
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
        $query  = Purchase::authCompany()->authUser()->latest()->with(["supplier","transaction"]);


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
            ['name'=>'transaction.transaction_code','data'=>'transaction.transaction_code','title'=>trans('common.transaction_code')],
            ['name'=>'supplier.name','data'=>'supplier_name','title'=>trans('common.supplier_name')],
            ['data' => 'memo_no','title' => trans('common.memo_no')],
            // 'memo_no',
            ['data' => 'paid_amount','title' => trans('common.paid_amount')],
            // 'paid_amount',
            ['data' => 'due_amount','title' => trans('common.due_amount')],
            // 'due_amount',
            ['data' => 'total_discount','title' => trans('common.total_discount')],
            // 'total_discount',
            ['data' => 'total_amount','title' => trans('common.total_amount')],
            // 'total_amount'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Purchases_' . date('YmdHis');
    }
}
