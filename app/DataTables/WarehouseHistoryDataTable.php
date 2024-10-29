<?php

namespace App\DataTables;

use App\Models\WarehouseHistory;
use function foo\func;
use Illuminate\Http\Request;
use Yajra\DataTables\Services\DataTable;

class WarehouseHistoryDataTable extends DataTable
{
    private $i =1;
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->addColumn('sl', function($model){
                return $this->i++;
            })
            ->addColumn('type', function($model){

                if($model->model == "SaleDetail")
                {
                    $type = 'label label-primary';
                    $loadingType = "Unload";
                }elseif ($model->model == "PurchaseDetail"){
                    $loadingType = "Load";
                    $type = 'label label-success';
                }elseif ($model->model == "Damage"){
                    $loadingType = "Damage / Loss";
                    $type = 'label label-danger';
                }elseif ($model->model == "Loss"){
                    $loadingType = "Damage/ Loss";
                    $type = 'label label-danger';
                }elseif ($model->model == "Overflow"){
                    $loadingType = "Overflow";
                    $type = 'label label-info';
                }elseif ($model->model == "Initial"){
                    $loadingType = "Initial";
                    $type = 'label label-info';
                }else{
                    $loadingType = "Transfer";
                    $type = 'label label-default';
                };

                return "<label class='".$type."'>".ucfirst($loadingType)."</label>";
            })
            ->editColumn('warehouse', function($model){
                return isset($model->warehouse) ? $model->warehouse->title : '';
            })
            ->addColumn('action', function($model) {

                $text = '<a href="'.route('member.warehouse.history.show', $model->code).'" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></a>';

                return $text;
            })
            ->rawColumns(['action', 'type'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Area $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {

        $query = WarehouseHistory::authCompany();

        $keyword = strtolower(request()->get('type'));

        if($keyword != "")
        {
            if( strpos('load', $keyword ) !== false ) {
                $query = $query->where("model", "PurchaseDetail");
            }else if ( strpos('unload', $keyword) !== false ) {
                $query = $query->where("model", "SaleDetail");
            }else if ( strpos('transfer', $keyword ) !== false  ) {
                $query = $query->where("model", "Transfer");
            }else if ( strpos('damage', $keyword ) !== false  ) {
                $query = $query->where("model", "Damage")->orWhere("model", "Loss");
            }else if ( strpos('overflow', $keyword ) !== false  ) {
                $query = $query->where("model", "Overflow");
            }
        }

        $query = $query->with('warehouse')->select("*")
            ->groupBy('code','model')
            ->orderBy('date','desc')->orderBy('id','desc');

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
            'sl'=>[
              'title' => "#SL",
                'searchable'=>false
            ],
            'code',
            'date',
            'type' => [
                'title' => 'Type',
                'name' => 'model',
                'data' => 'type',
//                'searchable'=>false
            ],
            'warehouse' => [
                'title' => 'Warehouse',
                'name' => 'warehouse.title',
                'data' => 'warehouse',
            ],
//            'total_product' =>
//            [
//                'title' => 'total product',
//                'name' => 'total_product',
//                'data' => 'total_product',
//                'searchable'=>false
//
//            ]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'WarehouseHistory_' . date('YmdHis');
    }
}
