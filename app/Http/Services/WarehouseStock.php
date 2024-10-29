<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 6/5/2023
 * Time: 3:12 PM
 */

namespace App\Http\Services;

use App\Models\Item;
use App\Models\Warehouse;
use App\Models\WarehouseHistory;
use App\Models\WarehouseStockHistory;
use App\Models\WarehouseStockReport;
use App\Models\WarehouseStock as WarehouseStockModel;
use Illuminate\Support\Facades\Auth;

class WarehouseStock
{
    private $product;
    private $qty;
    private $date;
    private $type;
    private $warehouse;

    public function __construct()
    {
        // Removed Code For Carely use.
    }

//
//    public function __construct($product_id="", $warehouse="", $qty="", $date="", $type = "sale")
//    {
//        $this->product = $product_id;
//        $this->qty = $qty;
//        $this->date = $date;
//        $this->type = $type;
//        $this->warehouse = $warehouse;
//    }

    public function set($product_id, $warehouse, $qty, $date, $type = "sale")
    {
        $this->product = $product_id;
        $this->qty = $qty;
        $this->date = $date;
        $this->type = $type;
        $this->warehouse = $warehouse;
    }

    public function stockIn()
    {
        $warehouseProduct = $this->check_stock($this->product, $this->warehouse);

        if ($warehouseProduct) {
            $warehouseProduct->stock = $warehouseProduct->stock + $this->qty;
            $warehouseProduct->update();

            $stock = $warehouseProduct->stock;
        } else {
            $this->create_stock("in");
            $stock = 0;
        }

        $this->create_stock_history("in", $stock);
        $this->stock_report();
    }

    public function check_stock($item_id, $warehouse_id)
    {
        $stockCheck = WarehouseStockModel::where('item_id', $item_id)
            ->where('warehouse_id', $warehouse_id)
            ->first();

        return $stockCheck;
    }

    public function create_stock($type = "out")
    {
        $warehouseProduct = new WarehouseStockModel();
        $warehouseProduct->item_id = $this->product;
        $warehouseProduct->company_id = Auth::user()->company_id;
        $warehouseProduct->warehouse_id = $this->warehouse;
        $warehouseProduct->stock = ($type == "in" ?: "-") . $this->qty;
        $warehouseProduct->save();
    }

    private function create_stock_history($flag = "in", $stock)
    {
        $stockHistory = new WarehouseStockHistory();
        $stockHistory->item_id = $this->product;
        $stockHistory->warehouse_id = $this->warehouse;
        $stockHistory->available_stock = $stock;
        $stockHistory->flag = $flag;
        $stockHistory->date = $this->date;

        if ($flag == "in") {
            $stockHistory->previous_stock = $stock - $this->qty;
        } else {
            $stockHistory->previous_stock = $stock + $this->qty;
        }

        $stockHistory->save();

    }


    public function stock_report()
    {
        $item_id = $this->product;
        $warehouse_id = $this->warehouse;
        $qty = $this->qty;
        $date = $this->date;
        $type = strtolower($this->type);
        $checkDate = $date;

        $stockReport = WarehouseStockReport::where('date', $checkDate)
            ->where('item_id', $item_id)
            ->where('warehouse_id', $warehouse_id)
            ->first();

        if ($type == "initial") {
            $stockIntital = WarehouseStockReport::where('date', "<", $checkDate)
                ->where('item_id', $item_id)
                ->where('warehouse_id', $warehouse_id)
                ->orderBy('date', 'ase')->get();

            foreach ($stockIntital as $value) {
                if ($value->load_qty == 0 && $value->unload_qty == 0) {
                    $value->delete();
                }

            }
        }


        $item = Item::find($item_id);
        $qty = create_float_format($qty);


        $currentStock = WarehouseStockReport::where('item_id', $item_id)
            ->where('warehouse_id', $warehouse_id)
            ->whereDate('date', '<', $checkDate)
            ->orderBy('date', 'DESC')
            ->first();

        $todayStock = WarehouseStockReport::where('item_id', $item_id)
            ->where('warehouse_id', $warehouse_id)
            ->whereDate('date', '<=', $checkDate)
            ->orderBy('date', 'DESC')->first();


        $openingStock = $currentStock ? $currentStock->opening_stock + $currentStock->load_qty - $currentStock->unload_qty - $currentStock->damage_qty + $currentStock->overflow_qty : ($todayStock ? $todayStock->opening_stock : 0);

        $stock = [];
        $stock['opening_stock'] = create_float_format($openingStock);
        if ($stockReport == null) {
            $stock['warehouse_id'] = $warehouse_id;
            $stock['company_id'] = $item->company_id;
            $stock['item_id'] = $item_id;
            $stock['date'] = $checkDate;
        }
//
        switch ($type) {
            case "initial":
                $stock['opening_stock'] = $qty;
                break;
            case "purchase":
                $stock['load_qty'] = $stockReport ? $stockReport->load_qty + $qty : $qty;
                break;
            case "sale":
                $stock['unload_qty'] = $stockReport ? $stockReport->unload_qty + $qty : $qty;
                break;
            case "transfer":
                $stock['transfer_qty'] = $stockReport ? $stockReport->transfer_qty + $qty : $qty;
                break;
            case "damage":
                $stock['damage_qty'] = $stockReport ? $stockReport->damage_qty + $qty : $qty;
                break;
            case "loss":
                $stock['damage_qty'] = $stockReport ? $stockReport->damage_qty + $qty : $qty;
                break;
            case "overflow":
                $stock['overflow_qty'] = $stockReport ? $stockReport->overflow_qty + $qty : $qty;
                break;
//            default:
//                $stock['sale_qty'] = 0;
//                $stock['purchase_qty'] = 0;
        }


        if (!$stockReport) {
            WarehouseStockReport::create($stock);
        } else {
            $stockReport->update($stock);
        }

//        $this->update_stock_report($item->id);
        $this->update_stock_report();
    }

    public function update_stock_report($item_id = "", $start_date = "", $warehouse_id = "")
    {
        if (!$item_id)
            $item_id = $this->product;

        if (!$start_date)
            $start_date = $this->date;

        if (!$warehouse_id)
            $warehouse_id = $this->warehouse;

        $warehousesArray = [];
        if ($item_id != "") {
            $warehousesArray = WarehouseStockModel::where('item_id', $item_id)
                ->pluck("warehouse_id")->toArray();
        }

        if ($warehouse_id == "") {
            if(count($warehousesArray)>0)
                $warehouses = Warehouse::whereIn('id',$warehousesArray)->select('id')->get();
            else
                $warehouses = Warehouse::select('id')->get();

        } else {
            $warehouses = Warehouse::where('id', $warehouse_id)->select('id')->get();
        }
//
//        dd($item_id."-----".$start_date."-----".$warehouse_id);

        foreach ($warehouses as $warehouse) {

            $warehouse_id = $warehouse->id;

            $stock_report = WarehouseStockReport::where('warehouse_id', $warehouse_id);

            if ($start_date != "") {
                $start_date = db_date_format($start_date);
                $stock_report = $stock_report->where('date', ">=", $start_date);
            }

            $warehouse_dates = WarehouseHistory::where(function ($query) use ($warehouse_id) {
                return $query->where('warehouse_id', $warehouse_id)
                    ->orWhere(function ($query1) use ($warehouse_id) {
                        return $query1->where('model', "Transfer")->where('model_id', $warehouse_id);
                    });
            });


            if ($item_id != "") {
                $stock_report = $stock_report->where('item_id', $item_id);
                $warehouse_dates = $warehouse_dates->where('item_id', $item_id);

                $warehouse_items[] = $item_id;
            } else {
                $warehouse_items = $warehouse_dates->groupBy('item_id')->pluck("item_id")->toArray();
            }

            $stock_dates = $stock_report->groupBy('date')->pluck("date")->toArray();
            $warehouse_dates = $warehouse_dates->whereNotIn('date', $stock_dates)->groupBy('date')->orderBy('date', 'asc')->pluck("date")->toArray();

            foreach ($warehouse_items as $wareItems) {

                $item_id = $wareItems;

                if(count($warehouse_dates)>0)
                {
                    $this->update_daily_stock_date_not_found($warehouse_dates, $item_id, $warehouse_id);
                }

                $initial = WarehouseHistory::where('model', 'Initial')
                    ->where('item_id', $item_id)
                    ->where('warehouse_id', $warehouse_id)
                    ->first();

                if($initial)
                {
                    WarehouseStockReport::where('warehouse_id', $warehouse_id)
                        ->where('item_id', $item_id)->where('date', '<', $initial->date)->delete();
                }

                $stock_report = WarehouseStockReport::where('warehouse_id', $warehouse_id);
                $stock_report = $stock_report->where('item_id', $item_id);

                if ($start_date != "") {
                    $stock_report = $stock_report->where('date', ">=", $start_date);
                }

                $stock_report = $stock_report->orderBy('date', 'asc')->get();

                $selected_item_id = "";
                foreach ($stock_report as $key => $value) {

                    $checkStockreport = WarehouseStockReport::find($value->id);
                    if($checkStockreport)
                    {
                        WarehouseStockReport::where('item_id', $value->item_id)
                            ->where('warehouse_id', $value->warehouse_id)
                            ->whereDate('date', '=', $value->date)
                            ->where('id', '!=', $value->id)
                            ->delete();
                    }

                    $initial = WarehouseHistory::where('model', 'Initial')
                        ->where('item_id', $item_id)
                        ->where('warehouse_id', $warehouse_id)
                        ->where('date', "=", $value->date)
                        ->first();

                    if ($value->item_id != $selected_item_id) {

                        if ($selected_item_id > 0) {
                            $stock = WarehouseStockModel::where('item_id', $selected_item_id)
                                ->where('warehouse_id', $warehouse_id)->first();

                            if ($stock) {
                                $stock->stock = $closing_qty;
                                $stock->update();
                            }
                        }


                        if($initial)
                            $opening_qty = $initial->qty;
                        else
                            $opening_qty = $value->opening_stock;

                    } else {
                        $opening_qty = $closing_qty;
                    }

                    $selected_item_id = $value->item_id;

                    $load_qty = WarehouseHistory::where('model', 'PurchaseDetail')
                        ->where('date', $value->date)
                        ->where('item_id', $selected_item_id)
                        ->where('warehouse_id', $warehouse_id)
                        ->sum('qty');

                    $transfer_qty = WarehouseHistory::where('model', 'Transfer')
                        ->where('date', $value->date)
                        ->where('item_id', $selected_item_id)
                        ->where('warehouse_id', $warehouse_id)
                        ->sum('qty');

                    $gain_qty = WarehouseHistory::where('model', 'Overflow')
                        ->where('date', $value->date)
                        ->where('item_id', $selected_item_id)
                        ->where('warehouse_id', $warehouse_id)
                        ->sum('qty');


                    $unload_qty = WarehouseHistory::where('model', 'SaleDetail')
                        ->where('date', $value->date)
                        ->where('item_id', $selected_item_id)
                        ->where('warehouse_id', $warehouse_id)
                        ->sum('qty');

                    $transfer_qty_to = WarehouseHistory::where('model', 'Transfer')
                        ->where('date', $value->date)
                        ->where('item_id', $selected_item_id)
                        ->where('model_id', $warehouse_id)
                        ->sum('qty');


                    $loss_qty = WarehouseHistory::where(function ($query) {
                        $query->where('model', 'Damage')
                            ->orWhere('model', 'Loss');
                    })
                        ->where('date', $value->date)
                        ->where('item_id', $selected_item_id)
                        ->where('warehouse_id', $warehouse_id)
                        ->sum('qty');


                    $unload_qty = $transfer_qty_to + $unload_qty;

                    $stock = WarehouseStockReport::find($value->id);

                    if($stock)
                    {
                        $stock->opening_stock = (float) $opening_qty;
                        $stock->load_qty = (float) ($load_qty + $transfer_qty);
                        $stock->unload_qty = (float) $unload_qty;
                        $stock->transfer_qty = (float) $transfer_qty_to;
                        $stock->damage_qty = (float) $loss_qty;
                        $stock->overflow_qty = (float) $gain_qty;

                        $load_qty = $transfer_qty + $load_qty + $gain_qty;
                        $unload_qty = $unload_qty + $loss_qty;

                        $closing_qty = (float)$opening_qty + (float)$load_qty - (float)$unload_qty;

                        $closing_qty = create_float_format($closing_qty);

                        $stock->closing_stock = $closing_qty;
                        $stock->update();
                    }

                    if (count($stock_report) - 1 == $key) {
                        $updateStock = WarehouseStockModel::where('item_id', $selected_item_id)
                            ->where('warehouse_id', $warehouse_id)->first();

                        if ($updateStock) {
                            $updateStock->stock = $closing_qty;
                            $updateStock->update();
                        }
                    }
                }
            }
        }

    }


    public function stockOut()
    {
        $warehouseProduct = $this->check_stock($this->product, $this->warehouse);

        if ($warehouseProduct) {
            $warehouseProduct->stock = $warehouseProduct->stock - $this->qty;
            $warehouseProduct->update();

            $stock = $warehouseProduct->stock;
        } else {
            $this->create_stock();
            $stock = 0;
        }

        $this->create_stock_history("out", $stock);
        $this->stock_report();
    }

    public function update_daily_stock_date_not_found($warehouse_dates, $item_id, $warehouse_id)
    {
        $item  = Item::find($item_id);

        foreach ($warehouse_dates as $date) {

            $warehouse_HistoryStock = WarehouseStockReport::where('item_id', $item_id)
                ->where('warehouse_id', $warehouse_id)
                ->whereDate('date', '=', $date)
                ->first();

            if(!$warehouse_HistoryStock)
            {
                $initial = WarehouseHistory::where('model', 'Initial')
                    ->where('item_id', $item_id)
                    ->where('warehouse_id', $warehouse_id)
                    ->where('date', "=", $date)
                    ->first();

                if ($initial) {
                    $openingStock = $initial->qty;
                } else {
                    $currentStock = WarehouseStockReport::where('item_id', $item_id)
                        ->where('warehouse_id', $warehouse_id)
                        ->whereDate('date', '<', $date)
                        ->orderBy('date', 'DESC')
                        ->first();

                    $openingStock = $currentStock ? $currentStock->closing_stock : 0 ;
                }

                $stock = [];
                $stock['opening_stock'] = $openingStock;
                $stock['warehouse_id'] = $warehouse_id;
                $stock['company_id'] = $item->company_id;
                $stock['item_id'] = $item_id;
                $stock['date'] = $date;


                $load_qty = WarehouseHistory::where('model', 'PurchaseDetail')
                    ->where('date', $date)
                    ->where('item_id', $item_id)
                    ->where('warehouse_id', $warehouse_id)
                    ->sum('qty');

                $transfer_qty = WarehouseHistory::where('model', 'Transfer')
                    ->where('date', $date)
                    ->where('item_id', $item_id)
                    ->where('warehouse_id', $warehouse_id)
                    ->sum('qty');

                $gain_qty = WarehouseHistory::where('model', 'Overflow')
                    ->where('date', $date)
                    ->where('item_id', $item_id)
                    ->where('warehouse_id', $warehouse_id)
                    ->sum('qty');

                $unload_qty = WarehouseHistory::where('model', 'SaleDetail')
                    ->where('date', $date)
                    ->where('item_id', $item_id)
                    ->where('warehouse_id', $warehouse_id)
                    ->sum('qty');

                $transfer_qty_to = WarehouseHistory::where('model', 'Transfer')
                    ->where('date', $date)
                    ->where('item_id', $item_id)
                    ->where('model_id', $warehouse_id)
                    ->sum('qty');


                $loss_qty = WarehouseHistory::where(function ($query) {
                    $query->where('model', 'Damage')
                        ->orWhere('model', 'Loss');
                })
                    ->where('date', $date)
                    ->where('item_id', $item_id)
                    ->where('warehouse_id', $warehouse_id)
                    ->sum('qty');


                $unload_qty = $transfer_qty_to + $unload_qty;

                $stock['load_qty'] = $load_qty + $transfer_qty;
                $stock['unload_qty'] = $unload_qty;
                $stock['transfer_qty'] = $transfer_qty_to;
                $stock['damage_qty'] = $loss_qty;
                $stock['overflow_qty'] = $gain_qty;

                $load_qty = $transfer_qty + $load_qty + $gain_qty;
                $unload_qty = $unload_qty + $loss_qty;
                $closing_qty = (float)$openingStock + (float)$load_qty - (float)$unload_qty;
                $closing_qty = create_float_format($closing_qty);

                $stock['closing_stock'] = $closing_qty;

                WarehouseStockReport::create($stock);
            }

        }
    }
}
