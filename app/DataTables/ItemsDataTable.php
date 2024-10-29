<?php

namespace App\DataTables;

use App\Models\Item;

use Milon\Barcode\DNS1D;
use Yajra\DataTables\Services\DataTable;

class ItemsDataTable extends DataTable
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
            ->editColumn('product_code', function($row){
                $barcode = !empty($row->productCode) ? DNS1D::getBarcodeHTML( $row->productCode, "C128", .5,33) : '';
                return $barcode;
            })
            ->editColumn('product_image', function($row){
                $logo = !empty($row->product_image) ? '<img src="'.$row->product_image_path.'" width="50px" />' : '';
                return $logo;
            })
            ->editColumn('warranty', function($row){
                return $row->warranty ? $row->warranty." Months" : '';
            })
            ->editColumn('guarantee', function($row){
                return $row->guarantee ? $row->guarantee." Months" : '';
            })
            ->editColumn('brand', function($row){
                return $row->brand_id > 0  ? $row->brand->name : '';
            })
            ->addColumn('action', function($modal) {
                return view('common._action-button', ['model' => $modal, 'route' => 'member.items']);
            })
            ->rawColumns(['product_code','warranty', 'guarantee', 'product_image','status', 'action'])
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Item $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {

        $query = Item::authCompany()->latest()->with('category', 'brand');

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
            ['name' => 'id', 'data' => 'id',  'title' => "ID" ],
            'product_code'=>['title' => trans('common.product_code'),'searchable'=>false,'orderable'=>false],
            'product_image'=>['title'=>trans('common.product_image')],
            ['name' => 'item_name', 'data' => 'item_name',  'title' => trans('common.product_name') ],
            ['name' => 'category.name', 'data' => 'category.display_name',  'title' => trans('common.product_category') ],
            ['name' => 'brand.name', 'data' => 'brand',  'title' => trans('common.brand') ],
            'warranty'=>['title'=>trans('common.warranty')],
            'guarantee'=>['title'=>trans('common.guarantee')],
            'unit'=>['title'=>trans('common.unit')],
            'price'=>['title'=>trans('common.price')],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Items_' . date('YmdHis');
    }
}
