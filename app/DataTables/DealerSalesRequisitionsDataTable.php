<?php

namespace App\DataTables;

use App\Models\SalesRequisition;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;

class DealerSalesRequisitionsDataTable extends DataTable
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
            ->addColumn('manage', function($modal){

                if(Auth::user()->hasRole(['dealer']))
                    $manage = "<a href='".route('member.sales_requisitions.requisition', $modal->id)."' class='btn btn-xs btn-success'> <i class='fa fa-shopping-cart'></i> </a>";
                else
                    $manage = "<label class='h4 my-0'>".($modal->is_sale == 1 ? "<i class='fa fa-check-circle-o text-success'></i>" : "<i class='fa fa-ban text-danger'></i>")."</label>";

                return $manage;
            })
            ->rawColumns(['requisition_details', 'action', 'manage'])
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
        $query  = SalesRequisition::where('is_sale', 0)->authCompany();

        if(Auth::user()->hasRole(['dealer']))
            $query = $query->where('dealer_id', Auth::user()->id)->where('requisition_request_by', 1);

        if(Auth::user()->hasRole(['sales_man']))
            $query = $query->where('sales_requisitions.created_by',Auth::user()->id)
                ->where('requisition_request_by', 1);


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
                'title' =>'Manage',
                'data' =>'manage',
                'className' => 'text-center'
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
        return 'DealerSaleRequisition_' . date('YmdHis');
    }
}
