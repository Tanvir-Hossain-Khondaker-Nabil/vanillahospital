<?php

namespace App\DataTables;

use App\Marketing_officer;
use Yajra\DataTables\Services\DataTable;

class MarketingOfficerDataTable extends DataTable
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
        ->editColumn('name', function ($model) {

            return $model->name;
        })
        ->editColumn('designation', function ($model) {

            return $model->designation;
        })
        ->editColumn('contact_no', function ($model) {

            return $model->contact_no;
        })
        ->editColumn('description', function ($model) {

            return $model->description;
        })
        ->editColumn('address', function ($model) {

            return $model->address;
        })
        ->editColumn('image', function ($model) {

            return "<img src='".asset('public/uploads/marketing_officer').'/'.$model->image."'>";
        })
        ->editColumn('status', function ($model) {

            return $model->status == 1 ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span>';

        })
        ->addColumn('action', function($model) {
            return view('common._action-mbuttons', ['model' => $model, 'route' => 'member.marketing_officer']);
        })

        ->rawColumns(['action','status','image'])
        ->make(true);
     }




    public function query()
    {
        $query = Marketing_officer::authCompany();
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
            'name',
            'designation',
            'contact_no',
            'description',
            'address',
            'image',
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
        return 'HospitalService_' . date('YmdHis');
    }
}
