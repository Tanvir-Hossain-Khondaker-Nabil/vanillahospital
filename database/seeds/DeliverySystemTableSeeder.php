<?php

use Illuminate\Database\Seeder;
use App\Models\DeliveryType;

class DeliverySystemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            'Cash on Delivery',
            'Courier'
        ];

        foreach ($array as $key => $item) {
            $delivery_type = DeliveryType::create([
                'display_name' => $item,
                'name' => strtolower(str_replace(' ','_', $item)),
                'status' => 'active',
                'member_id' => 1,
                'company_id' => 1,
            ]);

        }
    }
}
