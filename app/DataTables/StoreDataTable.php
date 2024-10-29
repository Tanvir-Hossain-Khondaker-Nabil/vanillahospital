<?php

namespace App\DataTables;

use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;

class StoreDataTable extends DataTable
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
        ->editColumn('approval_status', function($row){
            $type = $row->approval_status == 1 ? 'btn btn-sm btn-success': 'btn btn-sm btn-warning';
            $text = '<h5 class="my-0 text-center"><i class="fa fa-'.($row->approval_status == 1 ? 'check-square-o text-success' : 'times-circle text-danger').'"></i></h5>';
//            return "<label class='".$type."'>".$text."</label>";
            return $text;
        })
        ->editColumn('active_status', function($row){
            $type = $row->active_status == 1 ? 'label label-primary': 'label label-warning';
            $text = $row->active_status == 1 ? 'Active': 'Inactive';
            return "<label class='".$type."'>".ucfirst($text)."</label>";
        })
        ->addColumn('action', function($member) {
            return view('common._action-button',
                ['model' => $member, 'route' => 'member.store']);
        })
        ->rawColumns(['approval_status','active_status', 'action'])
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

        $query = Store::authArea()->authCompany()->latest()->with('region');

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
            'store_name',
            ['title'=>'Mobile Number','data'=>'mobile_no','name'=>'mobile_no'],
            'latitude',
            'longitude',
            'city',
            ['title'=>'Region','data'=>'region.name','name'=>'region.name'],
            'approval_status',
            'active_status',
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
