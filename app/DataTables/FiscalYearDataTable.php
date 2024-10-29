<?php 

namespace App\DataTables;

use App\Models\FiscalYear;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class FiscalYearDataTable extends DataTable
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
        ->editColumn('start_date', function($row) {
            return db_date_month_year_format($row->start_date);
        })
        ->editColumn('end_date', function($row) {
            return db_date_month_year_format($row->end_date);
        })
        ->editColumn('status', function($row){
            $type = $row->status == 'active' ? 'label label-primary': 'label label-warning';
            return "<label class='".$type."'>".ucfirst($row->status)."</label>";
        })
        ->addColumn('action', function($fiscal_year) {
            return view('common._action-button', ['model' => $fiscal_year, 'route' => 'member.fiscal_year']);
        })
        ->rawColumns(['status', 'action'])
        ->make(true);
    }
      

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\FiscalYear $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
       $query = FiscalYear::authMember()->authCompany();

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
            'start_date',
            'end_date',
            'status'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'FiscalYear_' . date('YmdHis');
    }
}
