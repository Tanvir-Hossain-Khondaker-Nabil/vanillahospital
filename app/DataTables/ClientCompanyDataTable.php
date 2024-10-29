<?php

namespace App\DataTables;

use App\Models\ClientCompany;
;
use Yajra\DataTables\Services\DataTable;

class ClientCompanyDataTable extends DataTable
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

            ->editColumn('status', function($model){
                $type = $model->status == '1' ? 'label label-primary': 'label label-warning';
                $text = $model->status == '1' ? 'Active': 'Inactive';
                return "<label class='".$type."'>".ucfirst($text)."</label>";
            })
            ->addColumn('action', function($model) {
                return view('common._action-button', ['model' => $model, 'route' => 'member.client_company']);
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = ClientCompany::authCompany();
        //   return ($query);
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
            'id',
            'company_name',
            'designation',
            'address_1',
            'address_2',
            'status',

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'ClientCompany_' . date('YmdHis');
    }
}