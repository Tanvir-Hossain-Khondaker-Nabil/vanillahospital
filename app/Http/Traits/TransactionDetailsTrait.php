<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/10/2019
 * Time: 5:19 PM
 */

namespace App\Http\Traits;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait TransactionDetailsTrait
{

    /**
     * @param bool $member By Default Condition False
     * @param bool $company By Default Condition False
     * @param integer $page By Default 0
     * @param bool $tr_payment_method By Default False
     * @param bool $updated_user By Default False
     * @param bool $tr_category By Default False
     * @param string $select_column By Default Select Transaction Details // Set (all) Show All
     * @param bool $group_tr_code By Default False
     * @param bool $group_tr_type By Default False
     * @param string $order By Default order by DESC
     * @param string $condition By Default Null
     * @param string $condition_col
     * @param string $value
     * @param array $multiple_codition
     * @return \Illuminate\Database\Query\Builder
     */


    public function transaction_full_details(
        $member = false, $company=false, $page = 0, $tr_payment_method=false, $updated_user=false, $tr_category=false,
        $select_column="", $group_tr_code=false, $group_tr_type=false, $order = 'DESC', $condition='',
        $condition_col='', $value='', $multiple_condition = [], $get=true
    )
    {
        $query = DB::table('transactions')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('users', 'transactions.created_by', '=', 'users.id')
            ->leftJoin('journal_entry_details', 'transaction_details.id', '=', 'journal_entry_details.transaction_details_id')
            ->leftJoin('cash_or_bank_accounts', 'transactions.cash_or_bank_id', '=', 'cash_or_bank_accounts.id')
           ->leftJoin('account_types', 'transaction_details.account_type_id', '=', 'account_types.id')
            ->leftJoin('account_types as cash_ac_type', 'transaction_details.against_account_type_id', '=', 'cash_ac_type.id')
            ->leftJoin('account_types as journal_ac_type', 'journal_entry_details.account_type_id', '=', 'journal_ac_type.id')
            ->leftJoin('suppliers_or_customers', 'transactions.supplier_id', 'suppliers_or_customers.id');

        if ($updated_user == true)
            $query = $query->join('users', 'transactions.updated_by', '=', 'users.id');

        if ($tr_payment_method == true)
            $query = $query->leftJoin('payment_methods', 'payment_methods.id', '=', 'transaction_details.payment_method_id');

        if ($tr_category == true)
            $query = $query->leftJoin('transaction_categories', 'transaction_categories.id', '=', 'transaction_details.transaction_category_id');

        if ($member == true)
            $query = $query->where('transactions.member_id', Auth::user()->member_id);

        if ($company != false)
            $query = $query->where('transactions.company_id', Auth::user()->company_id);

        if (!empty($condition) && !empty($value) && !empty($condition_col))
            $query = $query->where($condition_col, $condition, $value);

        if (!empty($multiple_condition)) {
            $query = $query->where($multiple_condition);
        }
        $query = $query->where('transaction_details.amount','>',0);

        if ($select_column == 'all')
            $query = $query->select(DB::raw('transaction_details.*, SUM(transaction_details.amount) as total_amount'), 'transactions.*', 'transaction_details.*','transaction_details.description as tr_descriptions', 'cash_or_bank_accounts.*', 'cash_ac_type.display_name as cash_account_type_name', 'account_types.*', 'account_types.display_name as account_type_name', 'transaction_categories.*', 'transaction_categories.display_name as transaction_category_name', 'users.full_name as created_user', 'users.full_name as updated_user', 'suppliers_or_customers.name as sharer_name');
        elseif ($select_column == 'tr-data')
            $query = $query->select(DB::raw('transaction_details.*, SUM(transaction_details.amount) as total_amount'), 'transactions.*', 'transaction_details.*','transaction_details.description as tr_descriptions', 'cash_or_bank_accounts.*', 'cash_ac_type.display_name as cash_account_type_name', 'account_types.display_name as account_type_name', 'users.full_name as created_user', 'suppliers_or_customers.name  as sharer_name ');
        else
            $query = $query->select('transactions.*', 'transaction_details.*','transaction_details.description as tr_descriptions', 'cash_or_bank_accounts.*', 'cash_or_bank_accounts.account_type_id as cash_account_type_id', 'cash_ac_type.display_name as cash_account_type_name', 'transaction_details.account_type_id as tr_account_type_id', 'account_types.id as account_code', 'account_types.display_name as account_type_name', 'users.full_name as created_user', 'suppliers_or_customers.name  as sharer_name');


        if ($group_tr_type == true)
            $query = $query->groupBy('transaction_details.transaction_type');

        if ($group_tr_code == true)
            $query = $query->groupBy('transactions.transaction_code');

        if ($order == "DESC")
            $query = $query->orderBy('transactions.id', 'DESC');
        else
            $query = $query->orderBy('transactions.id', 'ASC');


        if ($get == true){

            if ($page > 0)
                $query = $query->paginate($page);
            elseif ($page == 'ajax')
                $query = $query;
            else
                $query = $query->get();

        } else {
            $query = $query;
        }

        return $query;
    }



}
