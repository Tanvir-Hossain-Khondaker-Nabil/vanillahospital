<?php

namespace App\DataTables;

use App\Models\Company;
use Yajra\DataTables\Services\DataTable;

class CompanyDataTable extends DataTable
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
     ->editColumn('logo', function($row){
         $logo = !empty($row->logo) ? '<img src="'.$row->company_logo_path.'" width="30px" />' : '';
         return $logo;
     })
     ->editColumn('status', function($row){
        $type = $row->status == 'active' ? 'label label-primary': 'label label-warning';
        return "<label class='".$type."'>".ucfirst($row->status)."</label>";
    })
     ->addColumn('action', function($company) {
        return view('common._action-button', ['model' => $company, 'route' => 'member.company']);
    })
     ->rawColumns(['logo','status', 'action'])
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
     $query = Company::authMember();

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
            'logo',
            'company_name',
            'phone',
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
        return 'Company_' . date('YmdHis');
    }
}
