<?php

namespace App\DataTables;

use App\Models\SalesRequisition;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;

class SalesRequisitionsDataTable extends DataTable
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

                if(Auth::user()->can(['super-admin', 'admin']))
                    $manage = $modal->is_sale == 0 ? "<a href='".route('member.sales_requisitions.requisition', $modal->id)."' class='btn btn-xs btn-success'> <i class='fa fa-shopping-cart'></i> </a>" : "<label class='h4 my-0'><i class='fa fa-check-circle text-success'></i></label>";
                else
                    $manage = "<label class='h4 my-0'>".($modal->is_sale == 1 ? "<i class='fa fa-check-circle-o text-success'></i>" : "<i class='fa fa-ban text-danger'></i>")."</label>";

                return $manage;
            })
            ->editColumn('sale_id', function($modal){


                if(Auth::user()->can(['admin','super-admin']))
                    return isset($modal->sale_id) ? '<a target="_blank" href="'.route('member.sales.show', $modal->sale_id).'">'.$modal->sale_id.'</a>' : '';
                else if(Auth::user()->hasRole(['dealer']))
                    return isset($modal->dealer_sale_id) ? '<a target="_blank" href="'.route('member.dealer_sales.show', $modal->dealer_sale_id).'">'.$modal->dealer_sale_id.'</a>' : '';
                else
                    return "";


            })
            ->rawColumns(['requisition_details', 'action', 'manage', 'sale_id'])
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

        $query  = SalesRequisition::authUser()->authCompany()->authMember()
                                    ->latest()->with(['creator']);

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
                'title' => 'Created By',
                'name' => 'creator.full_name',
                'data' => 'creator.full_name',
            ],
            [
                'title' => 'Creator Phone',
                'name' => 'creator.phone',
                'data' => 'creator.phone',
            ],
            // 'requisition_details',
            'total_price',
            [
                'title' =>'Manage',
                'data' =>'manage',
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
        return 'Requisition_' . date('YmdHis');
    }
}
