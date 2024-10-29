<?php

namespace App\DataTables;

use App\Models\QuotationCompany;
use App\Models\QuotationTerm;
use Yajra\DataTables\Services\DataTable;

class QuotationCompanyDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function  ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->editColumn('status', function ($modal){
                return $modal->status == 0 ? "Inactive" : "Active";
            })
            ->addColumn('action', function($modal) {
                return view('common._action-button', ['model' => $modal, 'route' => 'member.quote_company']);
            })
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Category $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = QuotationCompany::latest();

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
            'company_name',
            'designation',
            'address_1',
            'address_2',
            'contact_no',
            'status'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'QuotationCompany_' . date('YmdHis');
    }
}
