<?php

namespace App\DataTables;

use App\Models\Quotation;
use App\Models\QuotationCompany;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;

class QuotationsDataTable extends DataTable
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
            ->editColumn('quotationer_id', function ($modal) {
                return $modal->quoteCompany->company_name;
            })
            ->addColumn('action', function($modal){
                return view('common._action-buttons', ['model' => $modal, 'route' => 'member.quotations']);
            })
            ->addColumn('manage_purchase', function($modal){

                $url = route('member.purchase_quotations.create');

                if(Auth::user()->can(['member.purchase_quotations.create']))
                    $manage =  "<a href='".$url."?id=$modal->id' class='btn btn-xs btn-success'  data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" title=\"Single Supplier Purchase\"> <i class='fa fa-shopping-cart'></i> </a>"."<a href='".$url."?id=$modal->id&multi_supplier=true' class='btn btn-xs btn-github ml-2' data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" title=\"Multi Supplier Purchase\"> <i class='fa fa-shopping-cart'></i> </a>";
                else
                    $manage = '';


                if($modal->purchase_id != "")
                {
                    $manage = $manage."<a href='javascript:void(0)' class='ml-3 btn btn-xs btn-primary'><i class='fa fa-check'></i></a>";
                }

                return $manage;
            })
            ->addColumn('manage_sale', function($modal){
                $sale_url =route('member.sale_quotations.create');

                if(Auth::user()->can(['member.sale_quotations.create']))
                    $manage =  "<a href='".$sale_url."?id=$modal->id' class='btn btn-xs btn-success'> <i class='fa fa-shopping-bag'></i> </a>";
                else
                    $manage = '';


                if($modal->sale_id != "")
                {
                    $manage = $manage."<a href='javascript:void(0)' class='ml-3 btn btn-xs btn-primary'><i class='fa fa-check'></i></a>";
                }


                return $manage;
            })
            ->rawColumns(['action', 'manage_purchase', 'manage_sale'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Quotation $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query  = Quotation::authCompany()->latest()->with(['quoteCompany']);

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
            'ref',
            [
                'name'=>'quotationer_id',
                'data'=>'quotationer_id',
                'title'=>'Company Name'
            ],
            'subject',
            [
                'name' => 'quote_date',
                'data' => 'date_format',
                'title' => 'Quote Date',
                'orderable' => true,
                'searchable' => true
            ],
            'manage_purchase',
            'manage_sale'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Quotation_' . date('YmdHis');
    }
}
