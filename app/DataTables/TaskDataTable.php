<?php

namespace App\DataTables;

use App\Models\Task;

;

use Yajra\DataTables\Services\DataTable;

class TaskDataTable extends DataTable
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
            ->editColumn('category', function ($model) {

                return $model->projectCategory ? $model->projectCategory->display_name : "";
            })
            ->editColumn('project', function ($model) {

                return $model->project ? $model->project->project : "";
            })
            ->editColumn('employee', function ($model) {

                return $model->employee ? $model->employee->first_name . " " . $model->employee->last_name : "";
            })
            ->editColumn('status', function ($model) {
                $type = $model->status == 'to_do' ? 'label label-primary' : ($model->status == 'in_progress' ? 'label label-primary' : ($model->status == 'review' ? 'label label-warning' : 'label label-success'));
                $text = $model->status == 'to_do' ? 'To Do' : ($model->status == 'in_progress' ? 'In Progress' : ($model->status == 'review' ? 'Review' : 'Done'));

                return "<label class='" . $type . "'>" . ucfirst($text) . "</label>";
            })
            ->editColumn('end_date', function ($model) {

                return $model->end_date;
            })
            ->addColumn('action', function ($model) {
                return view('common._action-button', ['model' => $model, 'route' => 'member.task']);
            })
            ->rawColumns(['Project', 'status', 'action'])
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
        $query = Task::authCompany()->with(['project', 'employee'])->orderBy('tasks.id', 'asc');

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
            'title',
            'project' => ['data' => 'project', 'name' => 'project.project', 'title' => 'Project'],
            'start_date',
            'end_date',
            'priority',
            // ['data'=>'company', 'name'=>'company.name', 'title'=>'Company'],
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
        return 'Task_' . date('YmdHis');
    }
}
