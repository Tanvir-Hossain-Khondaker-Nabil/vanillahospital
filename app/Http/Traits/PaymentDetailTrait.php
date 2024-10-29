<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/6/2019
 * Time: 2:45 PM
 */

namespace App\Http\Traits;


use App\Models\PaymentDetail;
use App\Models\PaymentMethod;

trait PaymentDetailTrait
{
    public $data;
    public $savePaymentDetails;

    public function checkingPaymentMethod($input, $key)
    {
        $payment_method_id = $input['payment_method_id'];
        $method = PaymentMethod::find($payment_method_id);

        $this->savePaymentDetails = new PaymentDetail();
        $this->savePaymentDetails->transaction_details_id = $input['transaction_details_id'];

        if($method){

            if(in_array(strtolower($method->name), ['check', 'master card', 'credit card', 'bkash', 'paypal']))
            {
                $this->savePaymentDetails->issuer_name = $input["issuer_name_$key"];
            }


            switch (strtolower($method->name)){

                case "check":
                    $output = $this->storeAsCheck($input, $key);
                    break;
                case "master card":
                    $output = $this->storeAsCard($input, $key);
                    break;
                case "credit card":
                    $output = $this->storeAsCard($input, $key);
                    break;
                case "bkash":
                    $output = $this->storeAsMobileBanking($input, $key);
                    break;
                case "paypal":
                    $output = $this->storeAsPayPal($input, $key);
                    break;
                default:
                    $output = false;
                    break;

            }
        }else{
            $output = true;
        }


        return $output;
    }


    public function storeAsCheck($input = [], $key)
    {
        $this->savePaymentDetails->number = $input["check_number_$key"];
        $this->savePaymentDetails->date = db_date_format($input["issue_date_$key"]);
        $this->savePaymentDetails->pass_date = db_date_format($input["pass_date_$key"]);
        $this->savePaymentDetails->provide_date = db_date_format($input["provide_date_$key"]);

        return $this->savePaymentDetails->save();
    }

    public function storeAsCard($input = [], $key)
    {
        $this->savePaymentDetails->number = $input["card_number_$key"];
        $this->savePaymentDetails->date = db_date_format($input["expire_date_$key"]);

        return $this->savePaymentDetails->save();
    }

    public function storeAsPayPal($input = [], $key)
    {
        $this->savePaymentDetails->email = $input["payby_email_$key"];

        return $this->savePaymentDetails->save();
    }

    public function storeAsMobileBanking($input = [], $key)
    {
        $this->savePaymentDetails->number = $input["mobile_number_$key"];;

        return $this->savePaymentDetails->save();
    }
}
