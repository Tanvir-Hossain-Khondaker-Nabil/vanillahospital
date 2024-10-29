<?php

use Illuminate\Database\Seeder;
use App\Models\GLAccountClass;

class AccountClassTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            'Assets',
            'Liabilities',
            'Income',
            'Expense',
            'Equity',
        ];

        foreach ($array as  $item) {
             GLAccountClass::create([
                'name' => $item,
                'class_type' => $item,
                'status' => 'Active'
            ]);

        }
    }
}
