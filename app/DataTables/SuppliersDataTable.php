<?php

namespace App\DataTables;

use App\Models\SupplierOrCustomer;
use App\User;
use Yajra\DataTables\Services\DataTable;

class SuppliersDataTable extends DataTable
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
            ->editColumn('status', function($row){
                $type = $row->status == 'active' ? 'label label-primary': 'label label-warning';
                return "<label class='".$type."'>".ucfirst($row->status)."</label>";
            })
            ->addColumn('action', function($supplier) {
                return view('common._all_action-button', ['model' => $supplier, 'route' => 'member.sharer']);
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SupplierOrCustomer $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = SupplierOrCustomer::onlySuppliers()->authMember()->authCompany();

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
            'id'=>['title'=> trans('common.id')],
            'name' => ['title' => trans('common.name')],
            'email' => ['title' => trans('common.email')],
            'phone' => ['title' => trans('common.phone')],
            'address' => ['title' => trans('common.address')],
//            [
//                'name' => 'initial_balance',
//                'data' => 'format_initial_balance',
//                'title' => 'Initial Balance (TK)',
//            ],
//            [
//                'name' => 'current_balance',
//                'data' => 'format_current_balance',
//                'title' => 'Current Balance (TK)',
//            ],
            'status' => ['title' => trans('common.status')]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Suppliers_' . date('YmdHis');
    }
}