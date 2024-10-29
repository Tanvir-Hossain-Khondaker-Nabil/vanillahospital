<?php

namespace App\DataTables;

use App\Models\LeadCategory;
use Yajra\DataTables\Services\DataTable;

class LeadCategoryDataTable extends DataTable
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
            ->editColumn('status', function($model){
                $type = $model->status == 'online' ? 'label label-success': 'label label-danger';
                $text = $model->status == 'online' ? 'Online': 'Offline';
                return "<label class='".$type."'>".ucfirst($text)."</label>";
            })
            ->addColumn('action', function($model) {
                return view('common._action-button', ['model' => $model, 'route' => 'member.lead_category']);
            })
            ->rawColumns(['status','action'])
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
        $query = LeadCategory::authCompany();

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
            'display_name',
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
        return 'LeadCategory_' . date('YmdHis');
    }
}