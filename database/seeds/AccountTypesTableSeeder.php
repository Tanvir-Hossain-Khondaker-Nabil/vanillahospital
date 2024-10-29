<?php

use Illuminate\Database\Seeder;
use App\Models\AccountType;

class AccountTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        AccountType::truncate();

        $array = [
            'Assets' => Null,
            'Current Assets' => 1,
            'Bank' => 2,
            'Cash' => 2,
            'Accounts Receivable' => 2,
            'Inventory' => 2,
            'Fixed Assets' => 1,
            'Other Assets' => 1,
            'Liabilities' => Null,
            'Current Liabilities' => 9,
            'Credit Accounts' => 10,
            'Accounts Payable' => 10,
            'Long-term Liabilities' => 9,
            'Equity' => Null,
            'Invested Capital' => 14,
            'Owner Distribution' => 14,
            'Retained Earnings' => 14,
            'Sales' => Null,
            'Other Revenue' => Null,
            'Income' => 19,
            'Interest Income' => 19,
            'Cost of Goods Sold' => Null,
            'Expenses' => Null,
            'Advertising' => 23,
            'Software & Subscriptions' => 23,
            'Office Expenses' => 23,
            'Insurance' => 23,
            'Accounting Services' => 23,
            'Meals & Entertainment' => 23,
            'Travel' => 23,
            'Depreciation Expense' => 23,
            'Other Expenses' => Null,
            'Interest Expense' => 32,
            'In Transit' => 2,
            'Ask My Accountant' => Null,
            'Purchase' => Null,
            'Shipping Cost' => 23,
            'Discount' => 23,
            'Appoinment' => 20,
            'OPD' => 20,

        ];

        foreach ($array as $key => $item) {
            $accountType = AccountType::create([
                'display_name' => $key,
                'name' => snake_case($key),
                'parent_id' => $item,
                'member_id' => 1
            ]);
        }
    }
}