<?php

use App\Models\AccountType;
use Illuminate\Database\Seeder;

class ReconciliationAccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            'Reconciliation' => Null,
            'Un-presented Cheque' => 39,
            'Dishonored Cheque' => 39,
            'Electricity Bill' => 39,
            'Bank Charge' => 39,
            'Bank Service Charge' => 39,
            'NSF Check' => 39,
            'Bank Collection Fee' => 39,
        ];

        foreach ($array as $key => $item) {
            $accountType = AccountType::create([
                'display_name' => $key,
                'name' => snake_case($key),
                'parent_id' => $item,
                'member_id' => 1,
            ]);
        }


        $account_types = array(
            array('id' => '929','display_name' => 'Advance Deposits & Prepayments','name' => 'advance_deposits&_prepayments','parent_id' => '2','member_id' => '1','company_id' => '2','status' => 'active','created_at' => '2020-09-26 23:00:02','updated_at' => '2020-09-26 23:00:02','class_id' => NULL,'deleted_at' => NULL),
            array('id' => '949','display_name' => 'Due from Affiliated company','name' => 'due_from_affiliated_company','parent_id' => '2','member_id' => '1','company_id' => '2','status' => 'active','created_at' => '2020-10-18 23:44:36','updated_at' => '2020-10-18 23:44:36','class_id' => NULL,'deleted_at' => NULL),
            array('id' => '955','display_name' => 'Due to Affiliated company','name' => 'due_to_affiliated_company','parent_id' => '10','member_id' => '1','company_id' => '2','status' => 'active','created_at' => '2020-10-20 18:14:43','updated_at' => '2020-10-20 18:14:43','class_id' => NULL,'deleted_at' => NULL),
            array('id' => '956','display_name' => 'Income Tax Payble','name' => 'income_tax_payble','parent_id' => '10','member_id' => '1','company_id' => '2','status' => 'active','created_at' => '2020-10-20 19:02:31','updated_at' => '2020-10-20 19:02:31','class_id' => NULL,'deleted_at' => NULL),
            array('id' => '970','display_name' => 'Liabilities for Expenses','name' => 'liabilities_for_expenses','parent_id' => '10','member_id' => '1','company_id' => '2','status' => 'active','created_at' => '2020-10-24 22:34:53','updated_at' => '2020-10-24 22:34:53','class_id' => NULL,'deleted_at' => NULL),
            array('id' => '971','display_name' => 'Fixed Deposits Receipts','name' => 'fixed_deposits_receipts','parent_id' => '2','member_id' => '1','company_id' => '2','status' => 'active','created_at' => '2020-10-24 22:43:46','updated_at' => '2020-10-24 22:43:46','class_id' => NULL,'deleted_at' => NULL)
        );

        foreach ($account_types as $key => $item) {
            $accountType = AccountType::create([
                'display_name' => $item['display_name'],
                'name' => $item['name'],
                'parent_id' => $item['parent_id'],
                'member_id' => 1,
            ]);

        }

    }
}
