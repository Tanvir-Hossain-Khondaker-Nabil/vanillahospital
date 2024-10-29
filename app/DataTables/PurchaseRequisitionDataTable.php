<?php

namespace App\DataTables;

use App\Models\Purchase;
use Yajra\DataTables\Services\DataTable;

class PurchaseRequisitionDataTable extends DataTable
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
                return '<a href="'.route('member.purchase_return.edit', $modal->id).'" class="btn btn-xs btn-info">
                            <i class="fa fa-reply"></i>
                        </a>';
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
        $query  = Purchase::isRequisition()->authCompany()->authUser()->latest()->with(["supplier","transaction"]);


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
//                    ->parameters($this->getBuilderParameters())
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
            ['name'=>'transaction.transaction_code','data'=>'transaction.transaction_code','title'=>'Transaction Code'],
            ['name'=>'supplier.name','data'=>'supplier_name','title'=>'Supplier Name'],
            'memo_no',
            'paid_amount',
            'due_amount',
            'total_discount',
            'total_amount'
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
