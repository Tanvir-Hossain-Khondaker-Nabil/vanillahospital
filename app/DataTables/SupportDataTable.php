<?php

namespace App\DataTables;

use App\Models\Support;
use Carbon\Carbon;
use Yajra\DataTables\Services\DataTable;

class SupportDataTable extends DataTable
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
                $type = $model->status == 'unseen' ? 'label label-primary': 'label label-success';
                $text = $model->status == 'unseen' ? 'Unseen': 'Seen';
                return "<label class='".$type."'>".ucfirst($text)."</label>";
            })
             ->editColumn('reply_status', function($model){
                $type = $model->reply_status == 'no' ? 'label label-warning': 'label label-success';
                $text = $model->reply_status == 'no' ? 'Pending': 'Replied';
                return "<label class='".$type."'>".ucfirst($text)."</label>";
            })
            ->editColumn('created', function($model){

                return Carbon::parse($model->created_at)->toDayDateTimeString();
            })
            ->addColumn('action', function($model) {
                return view('common._support-action', ['model' => $model, 'route' => 'member.support']);
            })
            ->rawColumns(['reply_status','message','status','action',])
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
        $query = Support::authCompany()->where('message_status','ask')->where('generated_id',\Auth::user()->id);

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
            'status',
            'reply_status',
            ['name'=>'created_at','title'=>'Created','data'=>'created'],

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Support_' . date('YmdHis');
    }
}
