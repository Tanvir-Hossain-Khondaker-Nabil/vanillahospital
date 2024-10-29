<?php

use Illuminate\Database\Seeder;
use App\Models\TransactionCategory;

class TransactionCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            'Regular Income' => 'Income',
            'Selling Software' => 'Income',
            'Account Transfer' => 'Income',
            'Home equity' => 'Income',
            'Rent & Royalties' => 'Income',
            'Equities' => 'Income',
            'Salary' => 'Income',
            'Other Income' => 'Income',
            'Software Customization' => 'Income',
            'Expense Refund' => 'Income',
            'Owner Contribution' => 'Income',
            'Interest Income' => 'Income',
            'Advertising' => 'Expense',
            'Staff Entertaining' => 'Expense',
            'Office Equipment' =>'Expense',
            'Paypal' => 'Expense',
            'Salary' => 'Expense',
            'Payroll Taxes' => 'Expense',
            'Phone' => 'Expense',
            'Postage' => 'Expense',
            'Repairs & Maintenance' => 'Expense',
            'Rent' => 'Expense',
            'Supplies' => 'Expense',
            'Taxes and Licenses' => 'Expense',
            'Transfer Funds' => 'Expense',
            'Travel' => 'Expense',
            'Utilities' => 'Expense',
            'Vehicle, Machinery & Equipment Rental or Leasing' => 'Expense',
            'Wages' => 'Expense',
            'Health Care' => 'Expense',
            'Owner Draws' => 'Expense',
            'Other Business Property Leasing' => 'Expense',
            'Bank and Credit Card Interest' => 'Expense',
            'Car and Truck' => 'Expense',
            'Commissions and Fees' => 'Expense',
            'Contract Labor' => 'Expense',
            'Contributions' => 'Expense',
            'Cost of Goods Sold' => 'Expense',
            'Credit Card Interest' => 'Expense',
            'Loans' => 'Expense',
            'Depreciation' => 'Expense',
            'Dividend Payments' => 'Expense',
            'Employee Benefit Programs' => 'Expense',
            'Entertainment' => 'Expense',
            'Gift' => 'Expense',
            'Insurance' => 'Expense',
            'Legal, Accountant & Other Professional Services' => 'Expense',
            'Meals' => 'Expense',
            'Mortgage Interest' => 'Expense',
            'Non-Deductible Expense' => 'Expense'
        ];

        foreach ($array as $key => $item) {
            $transactionCategory = TransactionCategory::create([
                'name' => strtolower(str_replace(' ', '_', $key)),
                'display_name' =>$key,
                'type' => $item
            ]);
        }
    }
}
