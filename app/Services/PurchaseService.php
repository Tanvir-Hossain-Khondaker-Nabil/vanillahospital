<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 11/10/2021
 * Time: 4:46 PM
 */

namespace App\Services;


use App\Models\FiscalYear;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function averagePurchasePrice($product_id)
    {
        $fiscal_year = FiscalYear::find(Auth::user()->company->fiscal_year_id);
        $from_date = $fiscal_year->start_date;
        $to_date = $fiscal_year->end_date;

        $item_price = PurchaseDetail::select(DB::raw('SUM(total_price)/SUM(qty) as price_qty'))
            ->whereBetween('date', [$from_date, $to_date])
            ->where('item_id', $product_id)
            ->groupBy('item_id')->first();

        $item_price = $item_price ? $item_price['price_qty'] : 0;

        return $item_price;

    }


    public function purchasePriceByQuotation($id, $product_id)
    {
        $itemPrice = PurchaseDetail::where('item_id', $product_id)
            ->whereHas('purchases', function (Builder $query) use($id){
            $query->where("quotation_id", $id);
        })->latest()->first();

        $itemPrice = $itemPrice ? $itemPrice->price : 0;

        return $itemPrice ;
    }
}
