<?php

namespace App\DataTables;

use App\Models\Project;

;

use Yajra\DataTables\Services\DataTable;

class ProjectDataTable extends DataTable
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
            ->editColumn('client', function ($model) {

                return $model->client ? $model->client->name : "";
            })
            ->editColumn('status', function ($model) {
                $type = $model->status == 'active' ? 'label label-primary' : 'label label-warning';
                $text = $model->status == 'active' ? 'Active' : 'Inactive';
                return "<label class='" . $type . "'>" . ucfirst($text) . "</label>";
            })
            ->editColumn('deadline', function ($model) {

                return $model->expire_date;
            })
            ->addColumn('action', function ($model) {
                return view('common._action-buttons', ['project_task' => 'project_task','kanban'=>'kanban', 'model' => $model, 'route' => 'member.project']);
            })
            ->rawColumns(['Client', 'status', 'action'])
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
        $query = Project::authCompany()->with(['client', 'projectCategory'])->latest();

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
            ->addAction(['width' => '120px']);
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
            'project',
            ['data' => 'category', 'name' => 'projectCategory.display_name', 'title' => 'Project Category'],
            'client' => ['data' => 'client', 'name' => 'client.name', 'title' => 'Client'],
//            'address',
             ['data'=>'price', 'name'=>'price', 'title'=>'Project Value (&euro;)', 'class'=>'text-center'],
            'start_date',
            'expire_date',
            // ['data'=>'company', 'name'=>'company.name', 'title'=>'Company'],
            'progress_status' => [ 'data' => 'progress_status', 'name' => 'progress_status',
                'title' => 'Progress Status', 'class'=>'text-capitalize'],
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
        return 'Project_' . date('YmdHis');
    }
}
