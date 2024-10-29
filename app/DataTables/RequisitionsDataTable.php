<?php

namespace App\DataTables;

use App\Models\Requisition;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;

class RequisitionsDataTable extends DataTable
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

            // ->addColumn('requisition_details', function($modal){
            //     $print = '<table width="100%"><tr><th width="40%">Item</th><th>QTY</th><th>Price</th></tr>';
            //     foreach ($modal->requisition_details as $value)
            //     {
            //         $print .= "<tr><td >".($value->item ? $value->item->item_name : $value->item_id)."</td>";
            //         $print .= "<td>".$value->qty."</td>";
            //         $print .= "<td>".$value->price."</td></tr>";
            //     }
            //     $print = $print."</table>";
            //     return $print;
            // })
           ->addColumn('action', function($modal){
                return view('common._action-buttons', ['model' => $modal, 'route' => 'member.requisition']);
            })
            ->addColumn('manage', function($modal){

                if(Auth::user()->can(['super-admin', 'admin']))
                    $manage =  "<a href='".route('member.purchase.requisition', $modal->id)."' class='btn btn-xs btn-success'> <i class='fa fa-shopping-cart'></i> </a>";
                 else
                    $manage = '';

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
        if(config('pos.requisition.user_based'))
            $query  = Requisition::authUser()->authCompany()->authMember()->latest();
        else
            $query  = Requisition::authCompany()->authMember()->latest();



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
            // 'requisition_details',
            [
                'name' => 'date',
                'data' => 'date_format',
                'title' => 'Date',
                'orderable' => true,
                'searchable' => true
            ],
            'total_price',
            'manage'
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
