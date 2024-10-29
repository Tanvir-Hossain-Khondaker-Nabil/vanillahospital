<?php

namespace App\DataTables;


use App\Models\Warehouse;
use Yajra\DataTables\Services\DataTable;

class WarehouseDataTable extends DataTable
{
    public $sl;
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
                $type = $row->active_status == 1 ? 'label label-primary': 'label label-warning';
                $text = $row->active_status == 1 ? 'Active': 'Inactive';
                return "<label class='".$type."'>".ucfirst($text)."</label>";
            })
            ->addColumn('action', function($model) {
                return view('common._all_action-button', ['model' => $model, 'route' => 'member.warehouse']);
            })
            ->rawColumns(['status', 'action'])
            ->addIndexColumn()
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
        $query = Warehouse::authCompany();

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
            ['title'=>"#SL",'data' => 'DT_RowIndex', 'name'=>'DT_RowIndex', 'orderable'=> false, 'searchable'=> false ],
            'title',
            'mobile',
            'address',
            'contact_person',
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
            'status' => ['searchable'=>false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Warehouse_' . date('YmdHis');
    }
}
