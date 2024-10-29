<?php

namespace App\DataTables;

use App\Models\Client;

;

use Yajra\DataTables\Services\DataTable;

class ClientDataTable extends DataTable
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
            ->editColumn('company', function ($model) {

                return $model->company ? $model->company->company_name : "";
            })
            ->editColumn('division', function ($model) {

                return $model->division ? $model->division->name : "";
            })
            ->editColumn('district', function ($model) {

                return $model->district ? $model->district->name : "";
            })
            ->editColumn('area', function ($model) {

                return $model->area ? $model->area->name : "";
            })
            ->editColumn('status', function ($model) {
                $type = $model->status == 'active' ? 'label label-primary' : 'label label-warning';
                $text = $model->status == 'active' ? 'Active' : 'Inactive';
                return "<label class='" . $type . "'>" . ucfirst($text) . "</label>";
            })
            ->addColumn('action', function ($model) {
                return view('common._action-button', ['model' => $model, 'route' => 'member.client']);
            })
            ->rawColumns(['status', 'action'])
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
        $query = Client::authCompany()->with(['company', 'division', 'district', 'area']);
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
            'name',
            ['data' => 'phone_1', 'title' => 'Phone No'],
            ['data' => 'company', 'name' => 'company_id', 'title' => 'Company Name'],
            ['data' => 'division', 'name' => 'division_id', 'title' => 'State'],
            ['data' => 'district', 'name' => 'district_id', 'title' => 'City'],
            ['data' => 'area', 'name' => 'area_id', 'title' => 'Area'],
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
        return 'Client_' . date('YmdHis');
    }
}
