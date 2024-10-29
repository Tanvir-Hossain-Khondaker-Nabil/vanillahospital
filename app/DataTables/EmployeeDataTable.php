<?php

namespace App\DataTables;

use App\Models\EmployeeInfo;
use Carbon\Carbon;
use Yajra\DataTables\Services\DataTable;

class EmployeeDataTable extends DataTable
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
        ->editColumn('dob', function($row) {
                return date_month_year_format($row->dob);
        })
        ->editColumn('join_date', function($row) {
                return date_month_year_format($row->join_date);
        })
        ->editColumn('designation_id', function($row) {
            return isset($row->designation) ? $row->designation->name : '';
        })
        ->addColumn('action', function($member) {
            return view('common._all_action-button', ['model' => $member, 'route' => 'member.employee']);
        })
        ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Member $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = EmployeeInfo::authCompany();

        $country_id = request()->get('country_id');
        $department_id = request()->get('department_id');
        $designation_id = request()->get('designation_id');
        $month = request()->get('month');
        $join_year = request()->get('join_year');
        $dob_year = request()->get('dob_year');

        if( $country_id > 0 )
        {
            $query = $query->where('country_id', $country_id);
        }

        if( $department_id > 0 )
        {
            $query = $query->where('department_id', $department_id);
        }

        if( $designation_id > 0 )
        {
            $query = $query->where('designation_id', $designation_id);
        }

        if( $month != "" && $join_year>0)
        {
            $query = $query->whereMonth('join_date', $month)->whereYear('join_date', $join_year);
        }elseif( $join_year>0 ){
            $query = $query->whereYear('join_date', $join_year);
        }

        if( $month  != "" && $dob_year>0)
        {
            $query = $query->whereMonth('dob', $month)->whereYear('dob', $dob_year);
        }elseif( $dob_year>0 ){
            $query = $query->whereYear('dob', $dob_year);
        }

        $query = $query->with('designation')->orderBy('employee_info.id', 'desc');

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
            ['title'=>trans('common.employee_id'),'data'=>'employeeID','name'=>'employeeID'],
            'first_name'=>['title'=>trans('common.first_name')],
            'last_name'=>['title'=>trans('common.last_name')],
            ['title'=>trans('common.phone'),'data'=>'phone2','name'=>'phone2'],
            ['title'=>trans('common.birth_year'),'data'=>'dob','name'=>'dob'],
            'join_date'=>['title'=>trans('common.join_date')],
            ['title'=>trans('common.designation'),'data'=>'designation_id','name'=>'designation_id'],
            ['title'=>trans('common.salary_system'),'data'=>'salary_system','name'=>'salary_system'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Employee_' . date('YmdHis');
    }
}