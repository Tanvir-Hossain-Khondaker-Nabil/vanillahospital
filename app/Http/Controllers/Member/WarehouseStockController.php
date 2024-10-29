<?php

namespace App\Http\Controllers\Member;

use App\DataTables\WarehouseHistoryDataTable;
use App\DataTables\WarehouseTransferHistoryDataTable;
use App\Http\Services\WarehouseStock;
use App\Models\PurchaseDetail;
use App\Models\SaleDetails;
use App\Models\WarehouseStock as WarehouseStockModel;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Warehouse;
use App\Models\WarehouseHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WarehouseStockController extends Controller
{

    public function unload_not_done()
    {
        $data['unloads'] = SaleDetails::whereNotIn('id', WarehouseHistory::where('model','SaleDetail')->pluck('model_id')->toArray())->get();


        return view('member.warehouse_stock.unload_not_done', $data);
    }

    public function load_not_done()
    {
        $data['loads'] = PurchaseDetail::whereNotIn('id', WarehouseHistory::where('model','PurchaseDetail')->pluck('model_id')->toArray())->get();


        return view('member.warehouse_stock.load_not_done', $data);
    }


    public function transfer_list(WarehouseTransferHistoryDataTable $dataTable)
    {
        $data['warehouses'] = Warehouse::authCompany()->active()->pluck('title', 'id');

        return $dataTable->render('member.warehouse_stock.transfer_list', $data);
    }

    public function index(WarehouseHistoryDataTable $dataTable)
    {

        return $dataTable->render('member.warehouse_stock.index');
    }


    public function unload($id)
    {
        $data['model'] = $sale = Sale::authCompany()->findOrFail($id);

        $data['products'] = Item::whereIn('id', $sale->sale_details()->pluck('item_id')->toArray())->pluck('item_name', 'id');
        $data['warehouses'] = Warehouse::authCompany()->active()->pluck('title', 'id');

        return view('member.warehouse_stock.unload', $data);
    }

    public function load($id)
    {
        $data['model'] = $model = Purchase::authCompany()->findOrFail($id);

        $data['products'] = Item::whereIn('id', $model->purchase_details()->pluck('item_id')->toArray())->pluck('item_name', 'id');
        $data['warehouses'] = Warehouse::authCompany()->active()->pluck('title', 'id');

        return view('member.warehouse_stock.load', $data);
    }

    public function transfer()
    {
        $item_id = WarehouseStockModel::authCompany()->pluck('item_id')->toArray();
        $data['products'] = Item::whereIn('id', $item_id)->authCompany()->select('item_name', 'id', 'unit')->get();

        $data['warehouses'] = Warehouse::authCompany()->active()->pluck('title', 'id');

        return view('member.warehouse_stock.transfer', $data);
    }

    public function unload_store(Request $request)
    {

        $sale_id = $request->sale_id;
        $date = db_date_format($request->date);

        $sale_details = $request->sale_details;
        $product_id = $request->product_id;
        $warehouse_id = $request->warehouse_id;
        $unload_qty = $request->unload_qty;
        $code = date("Ymdhis");

        $count = 0;
        $error = "";
        for ($i = 0; $i < count($product_id); $i++) {

            $product = Item::findOrFail($product_id[$i]);
            $warehouseCount = count($warehouse_id[$product->id]);

            for ($j = 0; $j < $warehouseCount; $j++) {

                $qty = $unload_qty[$product->id][$j];
                $warehouseId = $warehouse_id[$product->id][$j];
                $stock = $this->check_stock($product->id, $warehouseId);

                if ($qty > 0 && $stock >= $qty) {

                    $inputs = [];
                    $inputs['code'] = $code;
                    $inputs['company_id'] = $product->company_id;
                    $inputs['warehouse_id'] = $warehouseId;
                    $inputs['model'] = "SaleDetail";
                    $inputs['model_id'] = $sale_details[$i];
                    $inputs['item_id'] = $product->id;
                    $inputs['qty'] = $qty;
                    $inputs['date'] = $date;

                    WarehouseHistory::create($inputs);

                    $warehouse_stock = new WarehouseStock();
                    $warehouse_stock->set($product->id,$warehouseId, $qty, $date, 'Sale');
                    $warehouse_stock->stockOut();

                    $count++;
                } else {

                    $error .= $product->item_name . " Stock:" . $stock . ", ";
                }
            }


        }

        if (!$count) {
            $status = ['type' => 'danger', 'message' => $error];
            return redirect()->back()->with('status', $status);

        } else {

            if($error != "")
                $status = ['type' => 'danger', 'message' => trans('warehouse.some_warehouse_unload_successful_and').$error];
            else
                $status = ['type' => 'success', 'message' => trans('warehouse.warehouse_unload_successful')];

            return redirect()->route('member.warehouse.history.index')->with('status', $status);
        }


    }

    public function check_stock($item_id, $warehouse_id)
    {
        $stockCheck = WarehouseStockModel::where('item_id', $item_id)->where('warehouse_id', $warehouse_id)->first();

        return $stockCheck ? $stockCheck->stock : 0;
    }

    public function load_store(Request $request)
    {

        $purchase_id = $request->purchase_id;
        $date = db_date_format($request->date);

        $purchase_details = $request->purchase_details;
        $product_id = $request->product_id;
        $warehouse_id = $request->warehouse_id;
        $unload_qty = $request->unload_qty;
        $code = date("Ymdhis");

        for ($i = 0; $i < count($product_id); $i++) {

            $product = Item::findOrFail($product_id[$i]);

            $warehouseCount = count($warehouse_id[$product->id]);

            for ($j = 0; $j < $warehouseCount; $j++) {

                $qty = $unload_qty[$product->id][$j];
                $warehouseId = $warehouse_id[$product->id][$j];

                if ($qty > 0) {

                    $inputs = [];
                    $inputs['code'] = $code;
                    $inputs['company_id'] = $product->company_id;
                    $inputs['warehouse_id'] = $warehouseId;
                    $inputs['model'] = "PurchaseDetail";
                    $inputs['model_id'] = $purchase_details[$i];
                    $inputs['item_id'] = $product->id;
                    $inputs['qty'] = $qty;
                    $inputs['date'] = $date;

                    WarehouseHistory::create($inputs);

                    $warehouse_stock = new WarehouseStock();
                    $warehouse_stock->set($product->id, $warehouseId, $qty, $date, 'Purchase');
                    $warehouse_stock->stockIn();

                }
            }


        }

        $status = ['type' => 'success', 'message' => trans('warehouse.warehouse_load_successful')];
        return redirect()->route('member.warehouse.history.index')->with('status', $status);

    }

    public function transfer_store(Request $request)
    {
        $date = db_date_format($request->date);

        $product_id = $request->product_id;
        $warehouse_from = $request->warehouse_from;
        $warehouse_to = $request->warehouse_to;
        $unload_qty = $request->unload_qty;
        $code = date("Ymdhis");

        $transferCount = 0;
        $error = '';

        for ($i = 0; $i < count($product_id); $i++) {
            $product = Item::findOrFail($product_id[$i]);
            $qty = $unload_qty[$i];

            $stock = $this->check_stock($product_id[$i], $warehouse_from);

            if ($qty > 0 && $stock >= $qty) {

                $inputs = [];
                $inputs['code'] = $code;
                $inputs['company_id'] = $product->company_id;
                $inputs['warehouse_id'] = $warehouse_to;
                $inputs['model'] = "Transfer";
                $inputs['qty'] = $qty;
                $inputs['model_id'] = $warehouse_from;
                $inputs['item_id'] = $product_id[$i];
                $inputs['date'] = $date;

                WarehouseHistory::create($inputs);

                $warehouse_stock = new WarehouseStock();
                $warehouse_stock->set($product_id[$i], $warehouse_to, $qty, $date, 'Purchase');
                $warehouse_stock->stockIn();

                $warehouse_stock = new WarehouseStock();
                $warehouse_stock->set($product_id[$i], $warehouse_from, $qty, $date, 'Sale');
                $warehouse_stock->stockOut();

                $transferCount++;
            } else {

                $error .= $product->item_name . " Stock:" . $stock . ", ";
            }

        }

        if (!$transferCount) {

            $status = ['type' => 'danger', 'message' => $error];

            return redirect()->back()->with('status', $status);
        } else {

            $status = ['type' => 'success', 'message' => trans('warehouse.warehouse_transfer_successful')];

            return redirect()->route('member.warehouse.history.index')->with('status', $status);
        }
    }

    public function show($id)
    {

        $data['model'] = $warehouse = WarehouseHistory::where('code', $id)->authCompany()->get();

        if (count($warehouse) == 0) {
            $status = ['type' => 'danger', 'message' => trans('warehouse.you_dont_have_this_history')];
            return redirect()->back()->with('status', $status);
        }

//        dd($warehouse);
        return view('member.warehouse_stock.show', $data);
    }


}
