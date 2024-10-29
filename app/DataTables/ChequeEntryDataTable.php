<?php

namespace App\DataTables;

use App\Models\ChequeEntry;
use App\Models\Company;
use App\User;
use Yajra\DataTables\Services\DataTable;

class ChequeEntryDataTable extends DataTable
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
            ->editColumn('bank', function($row){
                return $row->bank->display_name;
            })
            ->editColumn('amount', function($row){
                return create_money_format($row->amount);
            })
            ->editColumn('giving_date', function($row){
                return db_date_month_year_format($row->giving_date);
            })
            ->editColumn('issue_date', function($row){
                return db_date_month_year_format($row->issue_date);
            })
            ->editColumn('issue_status', function($row){
                $type = $row->issue_status == 'pending' ? 'label label-primary': ($row->issue_status == 'completed' ? 'label label-success': 'label label-warning');
                return "<label class='".$type."'>".ucfirst($row->issue_status)."</label>";
            })
            ->addColumn('action', function($modal){
                if($modal->issue_status == 'pending')
                {
                    return view('common._action-button', ['model' => $modal, 'route' => 'member.cheque_entries']);
                }else{
                    return "";
                }
            })
            ->rawColumns(['issue_status', 'action'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ChequeEntry $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = ChequeEntry::authMember()->authCompany()->orderBy('id','DESC');

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
            [
                'name' => 'payer_name',
                'data' => 'payer_name',
                'title' => trans('common.customer')."/".trans('common.supplier_name')
            ],
            [
                'bank' => 'bank',
                'data' => 'bank',
                'title' => trans('common.banks')
            ],
            [
                'cheque_no' => 'cheque_no',
                'data' => 'cheque_no',
                'title' => trans('common.cheque_no')
            ],
            [
                'amount' => 'amount',
                'data' => 'amount',
                'title' => trans('common.amount')
            ],

            [
                'name' => 'giving_date',
                'data' => 'giving_date',
                'title' =>trans('common.received_date')
            ],
            [
                'name' => 'issue_date',
                'data' => 'issue_date',
                'title' =>trans('common.placing_date')
            ],
            [
                'name' => 'attempted_count',
                'data' => 'attempted_count',
                'title' =>trans('common.attempted_count')
            ],
             [
                'name' => 'issue_status',
                'data' => 'issue_status',
                'title' =>trans('common.issue_status')
            ],

            
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'ChequeEntry_' . date('YmdHis');
    }
}