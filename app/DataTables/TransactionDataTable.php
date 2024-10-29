<?php

namespace App\DataTables;

use App\Models\Transactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class TransactionDataTable extends DataTable
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
            ->editColumn('transaction_form', function($model){
                return isset($model->cash_or_bank_id) ? $model->cash_or_bank_account->title : '';
            })
            ->editColumn('transaction_method', function($model){

                if($model->transaction_method == "Payment")
                {
                    $transaction_method = $model->transaction_method."/ Expense";
                }elseif($model->transaction_method == "Deposit"){
                    $transaction_method = $model->transaction_method."/ Received";
                }else{
                    $transaction_method = $model->transaction_method;
                }

                return $transaction_method;
            })
            ->editColumn('sale_id', function($model){
                return isset($model->sale_id) ? '<a target="_blank" href="'.route('member.sales.show', $model->sale_id).'">'.$model->sale_id.'</a>' : '';
            })
            ->editColumn('memo_no', function($model){
                return isset($model->sale_id)  ?   $model->sale->memo_no  : '';
            })
            ->editColumn('purchase_id', function($model){
                return isset($model->purchase_id) ? '<a target="_blank"  href="'.route('member.purchase.show', $model->purchase_id).'">'.$model->purchase_id.'</a>' : '';
            })
            ->editColumn('invoice_no', function($model){
                return  isset($model->purchase_id) ? $model->purchase->invoice_no : '';
            })
//            ->editColumn('account_name', function($model){
//                $data = isset($model->transaction_details->transaction_category_id) ? $model->transaction_details->transaction_category->display_name : '';
//                $data = isset($model->transaction_details->account_type_id) ? $model->transaction_details->account_type->display_name : $data;
//
//                return $data;
//            })
            ->editColumn('amount', function($model){
                return create_money_format($model->amount);
            })
            ->editColumn('created_at', function($model){
                return date_string_format_with_time($model->created_at);
            })
//            ->editColumn('credit', function($model){
//                return $model->transaction_details->transaction_type == 'cr' ? create_money_format($model->amount) : create_money_format(0);
//            })
            ->addColumn('action', function($model) {
                $val = "";

                if($model->transaction_method=='Journal Entry' && Auth::user()->can(['member.journal_entry.show'])){
                    $val = '<a class="btn btn-xs btn-info" href="'.route('member.journal_entry.show', $model->transaction_code).'"><i class="fa fa-eye"></i></a>';
                }elseif(Auth::user()->can(['member.general_ledger.show'])){
                    $val =  '<a class="btn btn-xs btn-info" href="'.route('member.general_ledger.show', $model->transaction_code).'"><i class="fa fa-eye"></i></a>';
                }

                if(Auth::user()->can(['member.transaction.edit'])){

                    if($model->project_expense_id)
                        $val .= '<a class="btn btn-xs btn-primary" style="margin-left: 3px;" href="'.route('member.project_expenses.edit', $model->project_expense_id).'"><i class="fa fa-edit"></i></a>';
                    else
                        $val .= '<a class="btn btn-xs btn-primary" style="margin-left: 3px;" href="'.route('member.transaction.edit', $model->transaction_code).'"><i class="fa fa-edit"></i></a>';
                }

                if(Auth::user()->can(['member.transaction.destroy'])) {
                    $val .= '<a href="javascript:void(0);" style="margin-left: 3px;" class="btn btn-xs btn-danger delete-confirm" data-target="' . route('member.transaction.destroy', $model->id) . '"><i class="fa fa-times"></i></a>';
                }


                return $val;
            })
            ->rawColumns(['action', 'sale_id', 'purchase_id'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Transactions::authMember()->authCompany()
            ->withoutTransfer()
            ->where('amount', '!=', 0)
            ->where('transaction_method', '!=', "Initial")
            ->where('transaction_method', '!=', "repairs")
            ->orderBy('id','desc')->with(['sale','purchase','cash_or_bank_account']);

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
            ->addAction(['width' => '120px !important']);
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
                'name' => 'date',
                'data' => 'date_format',
                'title' => trans('common.date'),
                'className' => 'transaction-date',
                'orderable' => true,
                'searchable' => true
            ],
            // 'transaction_code',
            // 'transaction_method',
            'transaction_code'=>[
                'title' =>trans('common.transaction_code'),

                'data' => 'transaction_code',
            ],
            'transaction_method'=>[
                'title' =>trans('common.transaction_method'),

                'data' => 'transaction_method',
            ],
            'transaction_form' => [
                'title' =>trans('common.transaction_from'),
                'name' => 'cash_or_bank_account.title',
                'data' => 'transaction_form',
            ],
            'sale_id'=>[
                'name' => 'sale_id',
                'data' => 'sale_id',
                'title' =>trans('common.sale_id'),
                'orderable' => true,
                'searchable' => true
            ],
            'memo_no' => [
                'name' => 'sale.memo_no',
                'data' => 'memo_no',
                'title' =>trans('common.memo_no'),
            ],
            'purchase_id'=>[
                'name' => 'purchase_id',
                'data' => 'purchase_id',
                'title' =>trans('common.purchase_id'),
                'orderable' => true,
                'searchable' => true
            ],
            'invoice_no' => [
                'name' => 'purchase.invoice_no',
                'data' => 'invoice_no',
                'title' =>trans('common.invoice_no'),
            ],
            'amount'=>[
                'name' => 'amount',
                'data' => 'amount',
                'title' =>trans('common.amount'),
                'className' => 'text-right pr-5',
                'orderable' => false,
                'searchable' => false
            ],
            'created_at' =>[
                'title' =>trans('common.created_at'),
                'data' => 'created_at',
            ],
            // 'action' =>[
            //     'title' =>trans('common.action'),
            //     'data' => 'action',
            // ]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Transaction_' . date('YmdHis');
    }
}
