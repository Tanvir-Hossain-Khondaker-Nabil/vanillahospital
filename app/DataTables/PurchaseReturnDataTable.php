<?php

namespace App\DataTables;

use App\Models\Purchase;
use App\Models\ReturnPurchase;
use Yajra\DataTables\Services\DataTable;

class PurchaseReturnDataTable extends DataTable
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

                if(\Auth::user()->can(['member.purchase_return.view_returns'])) {

                    return '<a href="'.route('member.purchase_return.view_returns',[ 'id'=>$modal->purchase_id, 'code'=>$modal->return_code]).'" class="btn btn-xs btn-info">
                            <i class="fa fa-eye"></i></a>';

                }else{
                    return "";
                }

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
        $return = ReturnPurchase::authCompany()->groupBy('return_code');
//        $query  = Purchase::whereIn('id', $return)->latest();

        $query = Purchase::whereHas('purchase_returns', function ($query)  {
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
            'id',
            ['data' => 'return_code','title' => trans('purchase.return_code')],
            ['data' => 'purchase_id','title' => trans('purchase.purchase_id')],
            // 'return_code',
            // 'purchase_id',
//            'return_qty',
//            'return_price',
            // 'created_at',
            ['data' => 'created_at','title' => trans('purchase.created_at')],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'PurchaseReturn_' . date('YmdHis');
    }
}
