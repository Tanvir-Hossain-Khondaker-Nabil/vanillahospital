<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use \App\Models\SupplierOrCustomer;


class SharerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $faker = Faker::create();
//        for ($i=1; $i<11; $i++) {
//            $inputs = [];
//            $inputs['name'] = $faker->firstName." ".$faker->lastName;
//            $inputs['phone'] = $faker->phoneNumber;
//            $inputs['address'] = $faker->address;
//            $inputs['email'] = $faker->email;
//            $type = array_random(['both','customer','supplier'], 1);
//            $inputs['customer_type'] = $type[0];
//            if($inputs['customer_type']=="customer")
//            {
//                $inputs['customer_initial_balance'] = $faker->randomFloat(2,20000,200000);
//                $inputs['customer_current_balance'] = $inputs['customer_initial_balance'];
//            }elseif($inputs['customer_type']=="supplier"){
//                $inputs['supplier_initial_balance'] = $faker->randomFloat(2,20000,200000);
//                $inputs['supplier_current_balance'] = $inputs['supplier_initial_balance'];
//            }else{
//                $inputs['customer_initial_balance'] = $faker->randomFloat(2,20000,200000);
//                $inputs['customer_current_balance'] = $inputs['customer_initial_balance'];
//                $inputs['supplier_initial_balance'] = $faker->randomFloat(2,20000,200000);
//                $inputs['supplier_current_balance'] = $inputs['supplier_initial_balance'];
//            }
//
//            $inputs['member_id'] = 1;
//            $inputs['created_by'] = 1;
//            $inputs['status'] = 'active';
//            SupplierOrCustomer::create($inputs);
//        }
    }
}
