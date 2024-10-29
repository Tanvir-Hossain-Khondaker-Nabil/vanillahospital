<?php

namespace App\DataTables;

use App\Models\SubTestGroup;
use Yajra\DataTables\Services\DataTable;

class SubTestGroupDataTable extends DataTable
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
        ->editColumn('id', function($model) {
            return "<input type='checkbox' name='ids' class='checkbox_ids' id='' value='".$model->id."' />";
        })

        ->editColumn('title', function ($model) {

            return $model->title;
        })
        ->editColumn('price', function ($model) {

            return $model->price;
        })
        ->editColumn('unit', function ($model) {

            return $model->unit;

        }) ->editColumn('quack_ref_com', function ($model) {

            return $model->quack_ref_com;

        })  ->editColumn('ref_val', function ($model) {

            return $model->ref_val;

        }) ->editColumn('room_no', function ($model) {

            return $model->room_no;
        })

        ->addColumn('action', function($model) {
            return view('common._action-button', ['model' => $model, 'route' => 'member.sub_test_group']);
        })

        ->rawColumns(['id','action','status'])
        ->make(true);
     }




    public function query()
    {
        $query = SubTestGroup::authCompany()->with('testGroup');
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
            // 'sl'=>['title'=>'#SL'],
            'id'=>['title'=>'sl'],
            'title',
            'price',
            'unit',
            'quack_ref_com',
            'ref_val',
            'room_no',
            'unit',
            // 'action' =>['searchable'=>false,'printable'=>false],
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
        return 'SubTestGroup_' . date('YmdHis');
    }
}