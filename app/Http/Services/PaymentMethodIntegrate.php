<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 3/9/2023
 * Time: 10:57 AM
 */

namespace App\Http\Services;


use App\Models\PaymentMethod;

class PaymentMethodIntegrate
{
    public function posPaymentMethodSave()
    {
        $array = [
            'Credit Card', 'Debit', 'BKash', 'Rocket', 'Upay', 'Nagad'
        ];

        foreach ($array as $key => $item) {
            PaymentMethod::create([
                'name' => $item,
                'short_name' => strtolower(str_replace(' ','_', $item)),
                'status' => 'active'
            ]);

        }
    }
}
