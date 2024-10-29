<?php

namespace App\DataTables;

use App\Models\EmpLeave;

;

use Yajra\DataTables\Services\DataTable;

class EmpLeaveDataTable extends DataTable
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
            ->editColumn('employee', function ($row) {
                return isset($row->employee) ? $row->employee->employee_name_id : '';
            })
            ->editColumn('status', function($row){

                $status = ['0'=>'Deleted', '3'=>'Requested','1'=>'Accepted', '2' =>"Canceled"];
                $labelStatus = ['0'=>'label label-warning', '3'=>'label label-primary','1'=>'label label-success', '2' =>'label label-danger'];

                return "<label class='".$labelStatus[$row->status]."'>".ucfirst($status[$row->status])."</label>";
            })
            ->addColumn('action', function ($model) {
                return view('common._action-button', ['model' => $model, 'route' => 'member.employee-leaves']);
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
        $query = EmpLeave::active()->authCompany();

        if (!\Auth::user()->hasRole(['admin','super-admin'])) {
            $query = $query->where('emp_id', \Auth::user()->employee->id);
        }

        $query = $query->with(['employee', 'leave'])
                       ->orderBy('emp_leave.start_date', 'desc');

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

        if (!\Auth::user()->hasRole(['admin','super-admin'])) {
            $datatables = [
                'id',
                ['data' => 'leave', 'name' => 'leave.title', 'title' => 'Leave Title'],
                'start_date',
                'end_date',
                'status'

            ];
        }else{
            $datatables = [
                'id',
                ['data' => 'employee','name' =>'employee.first_name','title' =>'Employee Name'],
                ['data' => 'l_note', 'name' => 'l_note', 'title' => 'Leave Note'],
                'start_date',
                'end_date',
                'status'

            ];
        }


        return $datatables;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'EmpLeaveDataTable_' . date('YmdHis');
    }
}
