<?php

namespace App\DataTables;

use App\Models\ProjectExpense;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class ProjectExpensesDataTable extends DataTable
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
            ->editColumn('project', function ($model) {

                return $model->project ? $model->project->project : "";
            })
            ->editColumn('created_by', function ($model) {

                return $model->createdBy ? $model->createdBy->uc_full_name : "";
            })
            ->addColumn('action', function($projectExpenses) {
                return view('common._all_action-button', ['model' => $projectExpenses, 'route' => 'member.project_expenses']);
            })
            ->addIndexColumn()
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
        $query = ProjectExpense::with(['project','createdBy','transaction']);

        $project_id = request()->get('project_id');
        $project_date = request()->get('date') ? db_date_format(request()->get('date')) : "";

        if( $project_id > 0 )
        {
            $query = $query->where('project_id', $project_id);
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
            ['name' => 'id', 'data' => 'id',  'title' => "#SL" ],
            ['name' => 'date', 'data' => 'date_format',  'title' => "Date" ],
            'code',
            'project_id' => ['name' => 'project.project', 'data' => 'project',  'title' => "Project" ],
            'total_amount',
            'transaction_id' => ['name' => 'transaction.transaction_code', 'data' => 'transaction.transaction_code',  'title' => "Transaction Code", 'class' => 'text-center'],
            'created_by' => ['name' => 'createdBy.full_name', 'data' => 'created_by',  'title' => "Created by" ],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'ProjectExpenseTypes_' . date('YmdHis');
    }
}
