<?php

namespace App\DataTables;

use App\Models\RequisitionExpense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class RequisitionExpensesDataTable extends DataTable
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
            ->editColumn('employee', function ($model) {

                return $model->employee ? $model->employee->employee_name_id : "";
            })
            ->editColumn('transaction', function ($model) {

                return $model->transaction ? $model->transaction->transaction_code : "";
            })
            ->editColumn('created_by', function ($model) {

                return $model->createdBy ? $model->createdBy->uc_full_name : "";
            })
            ->editColumn('accept_status', function ($model) {
                $manage = "<label class='h4 my-0'>".($model->accept_status == 1 ? "<i class='fa fa-check-circle-o text-success'></i>" : "<i class='fa fa-times text-danger'></i>")."</label>";
                return $manage;
            })
            ->addColumn('action', function($requisitionExpenses) {
                return view('common._all_action-button', ['model' => $requisitionExpenses, 'route' => 'member.requisition_expenses']);
            })
            ->addIndexColumn()
            ->rawColumns(['accept_status','action'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\RequisitionExpense $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = RequisitionExpense::authCompany()->with(['employee','createdBy', 'transaction']);

        if(Auth::user()->hasRole(['user'])){
            $query = $query->where('employee_id', Auth::user()->employee->id);
        }

        $employee_id = request()->get('employee_id');
        $project_date = request()->get('date') ? db_date_format(request()->get('date')) : "";

        if( $employee_id > 0 )
        {
            $query = $query->where('employee_id', $employee_id);
        }

        if( $project_date != "")
        {
            $query = $query->where('date', $project_date);
        }

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
            ->ajax('')
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
            ['name' => 'id', 'data' => 'id',  'title' =>trans('common.serial') ],
            ['name' => 'date', 'data' => 'date_format',  'title' =>trans('common.date') ],
            ['name' => 'code', 'data' => 'code',  'title' =>trans('common.code') ],
            // 'code',
            'employee_id' => ['name' => 'employee.first_name', 'data' => 'employee',  'title' =>trans('common.employee')],
            ['name' => 'total_amount', 'data' => 'total_amount',  'title' =>trans('common.total_amount') ],
            // 'total_amount',
            'transaction_id' => ['name' => 'transaction.transaction_code', 'data' => 'transaction',  'title' =>trans('common.transaction_code'), 'class' => 'text-center'],
            'created_by' => ['name' => 'createdBy.full_name', 'data' => 'created_by',  'title' =>trans('common.created_by') ],
            'accept_status' => ['name'=>'accept_status', 'class'=>'text-center', 'title'=>trans('common.accept_status')]

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'RequisitionExpenseTypes_' . date('YmdHis');
    }
}