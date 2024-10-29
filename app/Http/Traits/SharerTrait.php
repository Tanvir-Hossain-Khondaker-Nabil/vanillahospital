<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 10/7/2019
 * Time: 3:59 PM
 */

namespace App\Http\Traits;


use App\Models\AccountHeadDayWiseBalance;
use App\Models\SupplierOrCustomer;

trait SharerTrait
{
    public function updateSharerBalance($inputs)
    {
        $sharer = new SupplierOrCustomer();
        $sharer_balance = $sharer->where('account_type_id', $inputs['account_type_id'])->first();
        $cash = AccountHeadDayWiseBalance::where('account_type_id', $inputs['account_type_id'])->orderBy('date','desc')->first();

        if($sharer_balance && $cash){
//            if($sharer_balance->customer_type == "customer"){
////                $sharer_balance->customer_current_balance = $inputs['amount'];
////                $sharer_balance->customer_current_balance = $inputs['transaction_type'] == "dr" ? $sharer_balance->customer_current_balance+$inputs['amount'] : $sharer_balance->customer_current_balance-$inputs['amount'];
//                $sharer_balance->customer_current_balance = $cash->balance;
//
//            }elseif ($sharer_balance->customer_type == "supplier"){
////                $sharer_balance->supplier_current_balance = $inputs['amount'];
////                $sharer_balance->supplier_current_balance = $inputs['transaction_type'] == "dr" ? $sharer_balance->supplier_current_balance+$inputs['amount'] : $sharer_balance->supplier_current_balance-$inputs['amount'];
//                $sharer_balance->supplier_current_balance = $cash->balance;
//
//            }else{
//                $sharer_balance->customer_current_balance = $inputs['amount'];
//                $sharer_balance->customer_current_balance = $inputs['transaction_type'] == "dr" ? $sharer_balance->customer_current_balance+$inputs['amount'] : $sharer_balance->customer_current_balance-$inputs['amount'];
                $sharer_balance->customer_current_balance = $cash->balance;
//                $sharer_balance->supplier_current_balance = $inputs['amount'];
//                $sharer_balance->supplier_current_balance = $inputs['transaction_type'] == "dr" ? $sharer_balance->supplier_current_balance+$inputs['amount'] : $sharer_balance->supplier_current_balance-$inputs['amount'];
                $sharer_balance->supplier_current_balance = $cash->balance;
//            }


            $sharer_balance->update();
        }


        return 1;
    }

    public function createSharer()
    {

    }
}
