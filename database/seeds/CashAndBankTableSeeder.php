<?php

use Illuminate\Database\Seeder;
use App\Models\CashOrBankAccount;
use Faker\Factory as Faker;

class CashAndBankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
//        for ($i=1; $i<6; $i++) {
//            $inputs = [];
//            $inputs['title'] = $faker->userName;
//            $inputs['phone'] = $faker->phoneNumber;
//            $inputs['contact_person'] = $faker->name;
//            $inputs['member_id'] = 1;
//            $inputs['created_by'] = 1;
//            $inputs['status'] = 'active';
//            CashOrBankAccount::create($inputs);
//        }

//        $bankName = array('City Bank', 'Dutch Bangla Bank', 'Islami Bank', 'UCB', 'Dhaka Bank', 'Sonali Bank');
//        for ($i=0; $i<6; $i++) {
//            $inputs = [];
//            $inputs['title'] = $bankName[$i];
//            $inputs['phone'] = $faker->phoneNumber;
//            $inputs['contact_person'] = $faker->name;
//            $inputs['account_number'] = $faker->bankAccountNumber;
//            $inputs['initial_balance'] = $faker->randomFloat(2,20000,200000);
//            $inputs['current_balance'] = $inputs['initial_balance'];
//            $inputs['member_id'] = 1;
//            $inputs['created_by'] = 1;
//            $inputs['status'] = 'active';
//            CashOrBankAccount::create($inputs);
//        }

    }
}
