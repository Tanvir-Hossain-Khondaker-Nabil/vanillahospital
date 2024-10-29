<?php

namespace App\DataTables;

use App\Models\HospitalService;
use Yajra\DataTables\Services\DataTable;

class HospitalServiceDataTable extends DataTable
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
        ->editColumn('title', function ($model) {

            return $model->title;
        })
        ->editColumn('price', function ($model) {

            return $model->price;
        })
        ->editColumn('comission', function ($model) {

            return $model->comission;
        })
        ->editColumn('status', function ($model) {

            return $model->status == 'active' ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span>';

        })
        ->addColumn('action', function($model) {
            return view('common._action-button', ['model' => $model, 'route' => 'member.hospital_service']);
        })

        ->rawColumns(['action','status'])
        ->make(true);
     }




    public function query()
    {
        $query = HospitalService::authCompany();
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
            'id'=>['title'=>trans('common.id')],
            'title',
            'price',
            'comission',
            // 'status',

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'HospitalService_' . date('YmdHis');
    }
}