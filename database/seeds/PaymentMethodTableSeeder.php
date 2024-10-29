<?php


use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $array = [
//            'Cash', 'Paypal', 'Cheque', 'ATM Withdrawal', 'Credit Card', 'Debit', 'BKash', 'Internet Banking', 'Master Card', 'Rocket', 'Upay', 'Nagad'
//        ];

        $array = [
            'Cash', 'Bank'
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
