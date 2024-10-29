<?php

namespace App\DataTables;

use App\Models\WarehouseHistory;
use Illuminate\Http\Request;
use Yajra\DataTables\Services\DataTable;

class WarehouseTransferHistoryDataTable extends DataTable
{
    public function ajax()
    {

        return datatables()
            ->eloquent($this->query())
            ->addColumn('type', function($model){
                    $loadingType = "Transfer";
                    $type = 'label label-default';

                return "<label class='".$type."'>".ucfirst($loadingType)."</label>";
            })
            ->editColumn('from_warehouse', function($model){
                return $model->fromWarehouse($model->model_id)->title ;
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
        $from_warehouse  = request()->get('from_warehouse') ?? "";
        $to_warehouse  = request()->get('warehouse_id') ?? "";

        if($from_warehouse != "")
        {
            $query = $query->where("model_id", $from_warehouse);
        }

        if($to_warehouse != "")
        {
            $query = $query->where("warehouse_id", $to_warehouse);
        }

        $query = $query->where("model", "Transfer");
        $query = $query->with(['warehouse','item'])->select("*")->orderBy('date','desc');

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
            ->minifiedAjax();
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
            'code',
            'date',
            'type' => [
                'title' => 'Type',
                'name' => 'model',
                'data' => 'type',
                'searchable'=>false
            ],
            'from_warehouse' => [
                'title' => 'From Warehouse',
                'name' => 'from_warehouse',
                'data' => 'from_warehouse',
                'searchable'=>false
            ],
            'warehouse' => [
                'title' => 'To Warehouse',
                'name' => 'warehouse.title',
                'data' => 'warehouse',
            ],
            'item' =>[
                'title' => 'Product',
                'name' => 'item.item_name',
                'data' => 'item.item_name',
            ],
            'qty',
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
