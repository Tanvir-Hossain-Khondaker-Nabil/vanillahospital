<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 9/9/2021
 * Time: 7:16 PM
 */

namespace App\Http\Traits;


use App\Models\DealerStock;
use App\Models\DealerStockReport;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

trait DealerStockTrait
{
    public function dealerStockIn($item_id, $qty, $dealer_id, $flag="stock in")
    {
        $data = [];
        $data['item_id'] = $item_id;
        $data['dealer_id'] = $dealer_id;
        $data['created_by'] = Auth::user()->id;
        $data['member_id'] = Auth::user()->member_id;
        $data['company_id'] = Auth::user()->company_id;

        $qty = create_float_format($qty);

        $stockCheck = DealerStock::where('item_id', $item_id)->where('dealer_id', $dealer_id)->first();
        if(!$stockCheck)
        {
            $data['stock'] = $qty;
            DealerStock::create($data);

        }else{

            $data['stock'] = $c_stock = create_float_format($qty+$stockCheck->stock, 3);
            $stockCheck->update($data);
        }

    }

    public function dealerStockOut($item_id, $qty, $dealer_id, $flag="Stock Out")
    {
        $stockCheck = DealerStock::where('item_id', $item_id)->where('dealer_id', $dealer_id)->first();
        $qty = create_float_format($qty);

        $data = [];
        $data['stock'] = $c_stock = create_float_format($stockCheck->stock-$qty, 3);
        $stockCheck->update($data);

    }


    public function dealer_check_stock($item_id, $dealer_id)
    {
        $stockCheck = DealerStock::where('item_id', $item_id)->where('dealer_id', $dealer_id)->first();

        if(!$stockCheck)
            return 0;
        else
            return create_float_format($stockCheck->stock);
    }


    public function dealer_stock_report($item_id, $qty, $type, $date, $dealer_id)
    {
        $checkDate =  $date;

        $stockReport = DealerStockReport::where('date', $checkDate)->where('item_id', $item_id)->first();

        $item = Item::find($item_id);
        $qty = create_float_format($qty, 2);


        $currentStock = DealerStockReport::where('item_id', $item_id)->whereDate('date','<' ,$checkDate)->orderBy('date','DESC')->first();


        $stock = [];
//        dd(count($stockReport));
        if( $stockReport==null ) {
            $stock['opening_stock'] = $currentStock ? create_float_format($currentStock->opening_stock+$currentStock->purchase_qty-$currentStock->sale_qty+$currentStock->sale_return_qty-$currentStock->purchase_return_qty,2) : 0;
            $stock['item_id'] = $item_id;
            $stock['product_code'] = $item->productCode;
            $stock['product_name'] = $item->item_name;
            $stock['date'] = $checkDate;
            $stock['company_id'] = Auth::user()->company_id;
            $stock['dealer_id'] = $dealer_id;

        }else{
            $stock['opening_stock'] = $currentStock ? create_float_format($currentStock->opening_stock+$currentStock->purchase_qty-$currentStock->sale_qty+$currentStock->sale_return_qty-$currentStock->purchase_return_qty, 2) : $stockReport->opening_stock;
        }

        switch ($type)
        {
            case "purchase":
                $stock['purchase_qty'] = $stockReport ? $stockReport->purchase_qty+$qty : $qty;
                break;
            case "purchase return":
                $stock['purchase_return_qty'] = $stockReport ? $stockReport->purchase_return_qty+$qty : $qty;
                break;
            case "sale":
                $stock['sale_qty'] = $stockReport ? $stockReport->sale_qty+$qty : $qty;
                break;
            case "sale return":
                $stock['sale_return_qty'] = $stockReport ? $stockReport->sale_return_qty+$qty : $qty;
                break;
            case "delete sale":
                $stock['sale_qty'] = $stockReport ? $stockReport->sale_qty-$qty : 0;
                break;
            case "delete purchase":
                $stock['purchase_qty'] = $stockReport ? $stockReport->purchase_qty-$qty : 0;
                break;
            case "delete initial":
                $stock['opening_stock'] = $stockReport->opening_stock-$qty;
                break;
            case "initial":
                $stock['opening_stock'] = $qty;
                break;
//            default:
//                $stock['sale_qty'] = 0;
//                $stock['purchase_qty'] = 0;
        }


        if(!$stockReport)
        {
            DealerStockReport::create($stock);
        }else{
            $stockReport->update($stock);
        }

        $this->update_stock_report($item->id, $checkDate);

    }

}
