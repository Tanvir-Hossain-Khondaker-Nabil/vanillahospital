<?php

namespace App\Http\Controllers\Member;

use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use App\Models\DueCollectionHistory;
use App\Models\Purchase;
use App\Models\SupplierOrCustomer;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PurchaseDueController extends PurchaseController
{
    public function due_list()
    {
        $data['modal'] = Purchase::authMember()->authCompany()->where('due_amount', '>', 0)->paginate(15);

        return view('member.purchase.due_list', $data);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function due_payment($id)
    {
        $purchase = $data['purchase'] = Purchase::where('id', $id)->where('due_amount','>',0)->first();
        if($purchase)
        {
            return view('member.purchase.due_payment', $data);
        }else{
            $status = [
                'type' => 'danger',
                'message' => trans('purchse.purchase_id').": ".$id.", ".trans('purchase.this_purchase_dont_have_any_due')
            ];
            return redirect()->back()->with('status', $status);
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function receive_due_payment(Request $request, $id)
    {
        $purchaseId = Purchase::find($id);
        $amount = create_float_format($request->due);
        $collection_date = db_date_format($request->date);


        if($purchaseId && $amount <= $purchaseId->due_amount)
        {

            DB::beginTransaction();
            try{

                $save = new DueCollectionHistory();
                $inputs = [];
                $inputs['inventory_type'] = 'Purchase';
                $inputs['inventory_type_id'] = $purchaseId->id;
                $inputs['sharer_id'] = $purchaseId->supplier_id;
                $inputs['amount'] = create_float_format($request->due);
                $inputs['collection_date'] = $collection_date;
                $save->create($inputs);

                $purchase = [];
                $purchase['due_amount'] = create_float_format($purchaseId->amt_to_pay-($purchaseId->paid_amount+$request->due));
                $purchase['paid_amount'] = create_float_format($purchaseId->paid_amount+$request->due);
                $purchaseId->update($purchase);

                $account = CashOrBankAccount::find($purchaseId->cash_or_bank_id);
                $sharer = SupplierOrCustomer::find($purchaseId->supplier_id);
                $inputs['sharer_name'] = $sharer->name;

                // Update Cash and Bank Account Balance
                $this->bankAccountBalanceUpdate("Purchases", $account, $amount);

                if(isset($request->supplier_id)) {
                    $this->sharerBalanceUpdate("Purchases", $sharer, $amount);
                }

                $inputs['transaction_code'] = transaction_code_generate();
                $save_transaction = new Transactions();
                $save_transaction->transaction_code = $inputs['transaction_code'];
                $save_transaction->supplier_id = $purchaseId->supplier_id;
//                $save_transaction->purchase_id = $purchaseId->id;
                $save_transaction->cash_or_bank_id = $purchaseId->cash_or_bank_id;
                $save_transaction->date = $inputs['date'] = $collection_date;
                $save_transaction->amount = create_float_format($amount);
                $save_transaction->notation = "";
                $save_transaction->transaction_method = $inputs['transaction_method'] = "Purchases";
                $save_transaction->save();

                $inputs['against_account_type_id'] = $sharer->account_type->id;
                $inputs['against_account_name'] = $sharer->account_type->display_name;
                $inputs['account_name'] = $account->account_type->display_name;
                $inputs['to_account_name'] = '';
                $inputs['transaction_id'] = $save_transaction->id;
                $inputs['amount'] = $amount;
                $inputs['account_type_id'] = $account->account_type->id;
                $inputs['transaction_type'] = 'cr';
                $inputs['payment_method_id'] = $purchaseId->payment_method_id;
                $inputs['description'] = "Purchase due payment"." Purchase Id : ".$purchaseId->id;
                $this->createCreditAmount($inputs);

                $inputs['account_type_id'] = $sharer->account_type->id;
                $inputs['account_name'] = $sharer->account_type->display_name;
                $inputs['against_account_name'] = $account->account_type->display_name;
                $inputs['against_account_type_id'] = $account->account_type->id;
                $inputs['transaction_type'] = 'dr';
                $this->createDebitAmount($inputs);

                $status = ['type' => 'success', 'message' => trans('purchase.purchase_due_payment_done_successfully')];

            }catch (\Exception $e){

                $status = ['type' => 'danger', 'message' => trans('common.unable_to_update')];
                DB::rollBack();
            }

            DB::commit();



        }else{

            $status = ['type' => 'danger', 'message' => trans('purchase.unable_to_find_due_payment_purchase_id')];
        }


        return redirect()->route('member.purchase.due_list')->with('status', $status);
    }

}
