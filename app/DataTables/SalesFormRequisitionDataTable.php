<?php

namespace App\DataTables;

use App\Models\RequisitionDetail;
use App\Models\SalesRequisition;
use App\Models\SalesRequisitionDetail;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;

class SalesFormRequisitionDataTable extends DataTable
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
                return view('common._action-buttons', ['model' => $modal, 'route' => 'member.sales_requisitions']);
            })
            ->addColumn('is_sale', function($modal){

                $manage = "<label class='h4 my-0'>".($modal->is_sale == 1 ? "<i class='fa fa-check-circle-o text-success'></i>" : "<i class='fa fa-ban text-danger'></i>")."</label>";

                return $manage;
            })
            ->editColumn('sale_id', function($modal){


                if($modal->sale_id>0)
                    return isset($modal->sale_id) ? '<a target="_blank" href="'.route('member.sales.show', $modal->sale_id).'">'.$modal->sale_id.'</a>' : '';
                else if($modal->dealer_sale_id>0)
                    return isset($modal->dealer_sale_id) ? '<a target="_blank" href="'.route('member.dealer_sales.show', $modal->dealer_sale_id).'">'.$modal->dealer_sale_id.'</a>' : '';
                else
                    return "";




            })
            ->rawColumns(['requisition_details', 'action', 'is_sale', 'sale_id'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Requisition $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query  = SalesRequisition::where('is_sale', 1)->where('requisition_request_by', 1)->authCompany();

        if(Auth::user()->hasRole(['dealer']))
            $query = $query->where('dealer_id', Auth::user()->id);

        if(Auth::user()->hasRole(['sales_man']))
            $query = $query->where('sales_requisitions.created_by',Auth::user()->id);


        $query = $query->latest()->with(['dealer', 'customer']);

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
//            ->parameters($this->getBuilderParameters())
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
            ['name' => 'date', 'data' => 'date',  'title' => "Date" ],
            [
                'title' => 'Customer',
                'name' => 'customer.store_name',
                'data' => 'customer.store_name',
            ],
            [
                'title' => 'Dealer',
                'name' => 'dealer.full_name',
                'data' => 'dealer.full_name',
            ],
            [
                'title' => ' Phone',
                'name' => 'dealer.phone',
                'data' => 'dealer.phone',
            ],
            // 'requisition_details',
            'total_price',
            [
                'title' =>'Is Sale',
                'data' =>'is_sale',
                'className' => 'text-center'
            ],
            'sale_id'=>[
                'name' => 'sale_id',
                'data' => 'sale_id',
                'title' => 'Sale Id',
                'orderable' => true,
                'searchable' => true
            ]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'SalesFormRequisitionDataTable' . date('YmdHis');
    }
}
