<?php

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            'Sonali Bank Limited',
            'Janata Bank Limited',
            'Agrani Bank Limited',
            'Rupali Bank Limited',
            'BASIC Bank Limited',
            'Bangladesh Development Bank Limited',
            'Bangladesh Krishi Bank',
            'Rajshahi Krishi Unnayan Bank',
            'Probashi Kallyan Bank',
            'AB Bank Limited',
            'Bangladesh Commerce Bank Limited',
            'Bank Asia Limited',
            'BRAC Bank Limited',
            'City Bank Limited',
            'Community Bank Bangladesh Limited',
            'Dhaka Bank Limited',
            'Dutch-Bangla Bank Limited',
            'Eastern Bank Limited',
            'IFIC Bank Limited',
            'Jamuna Bank Limited',
            'Meghna Bank Limited',
            'Mercantile Bank Limited',
            'Midland Bank Limited',
            'Modhumoti Bank Limited',
            'Mutual Trust Bank Limited',
            'National Bank Limited',
            'National Credit & Commerce Bank Limited',
            'NRB Bank Limited',
            'One Bank Limited',
            'Padma Bank Limited',
            'Premier Bank Limited',
            'Prime Bank Limited',
            'Pubali Bank Limited',
            'Shimanto Bank Ltd',
            'South Bangla Agriculture and Commerce Bank Limited',
            'Standard Bank Limited',
            'Trust Bank Limited',
            'United Commercial Bank Ltd',
            'Uttara Bank Limited',
            'Southeast Bank Ltd',
            'Al-Arafah Islami Bank Limited',
            'EXIM Bank Limited',
            'First Security Islami Bank Limited',
            'ICB Islamic Bank Limited',
            'Islami Bank Bangladesh Limited',
            'Shahjalal Islami Bank Limited',
            'Social Islami Bank Limited',
            'Union Bank Limited',
            'Bank Al-Falah Limited',
            'Citibank N.A',
            'Commercial Bank of Ceylon PLC',
            'Habib Bank Limited',
            'HSBC',
            'National Bank of Pakistan',
            'Standard Chartered Bank',
            'State Bank of India',
            'Woori Bank'
        ];

        foreach ($array as $key => $item) {
            $banks = Bank::create([
                'display_name' => $item,
                'status' => 'active',
                'member_id' => 1,
                'created_by' => 1,
            ]);
        }
    }
}
