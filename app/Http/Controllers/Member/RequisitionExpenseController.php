<?php

namespace App\Http\Controllers\Member;

use App\DataTables\RequisitionExpensesDataTable;
use App\Http\Services\ExpenseRequisitionTransaction;
use App\Http\Traits\CompanyInfoTrait;
use App\Http\Traits\SharerCashAndAccount;
use App\Http\Traits\TransactionTrait;
use App\Models\AccountHeadDayWiseBalance;
use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use App\Models\EmployeeInfo;
use App\Models\ProjectExpenseDetail;
use App\Models\ProjectExpenseType;
use App\Models\RequisitionExpense;
use App\Models\RequisitionExpenseDetail;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\DNS1D;

class RequisitionExpenseController extends Controller
{

    use SharerCashAndAccount, TransactionTrait, CompanyInfoTrait;

    public function index(RequisitionExpensesDataTable  $dataTable)
    {
        $data['employees'] = EmployeeInfo::authCompany()->get();
        return $dataTable->render('member.requisition_expenses.index', $data);
    }


    public function create()
    {
        $data['employees'] = EmployeeInfo::authCompany()->get();
        $data['expenses'] = AccountType::where('name','expenses')->orwhere('parent_id', 23)->authMember()->authCompany()->active()->get();

        return view('member.requisition_expenses.create', $data);
    }

    public function store(Request $request)
    {

        if(Auth::user()->hasRole(['user']))
            $request['employee_id'] = Auth::user()->employee->id;

        $this->validate($request, $this->validationRules());

        $countExpense = RequisitionExpense::groupBy('date')->groupBy('employee_id')->count() + 1;

        $employee = EmployeeInfo::findOrFail($request->employee_id);

        $inputs = [];
        $inputs['date'] = $expenseDate = db_date_format($request->date);
        $inputs['employee_id'] = $request->employee_id ?? Auth::user()->employee->id;
        $inputs['created_by'] = $userId = Auth::user()->id;
        $inputs['company_id'] = Auth::user()->company_id;
        $inputs['code'] = $code = code_generate($inputs['employee_id'], $userId, $countExpense);
        $inputs['total_amount'] = array_sum($request->amount);
        $expense = RequisitionExpense::create($inputs);

        $expenses = $request->expense_id;
        $amounts = $request->amount;

        DB::beginTransaction();
        try {

            foreach ($expenses as $key => $value) {

                $expenseType = AccountType::find($value);

                $details = [];
                $details['requisition_expense_id'] = $expense->id;
                $details['account_type_id'] = $value;
                $details['date'] = $expenseDate;
                $details['amount'] = $amounts[$key];

                RequisitionExpenseDetail::create($details);

             }

            $status = ['type' => 'success', 'message' => 'Requisition Expense Add Successfully'];

        } catch (\Exception $e) {

            $status = ['type' => 'danger', 'message' => 'Unable to update'];
            DB::rollBack();
        }

        DB::commit();

        if ($status['type'] == 'success') {
            return redirect()->route('member.requisition_expenses.show', $expense->id)->with('status', $status);
        } else {
            return redirect()->back()->with('status', $status);
        }


    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
      
        $data['requisition_expenses'] =  $requisition_expenses = RequisitionExpense::findOrFail($id);

        $d = new DNS1D();
        $d->setStorPath(__DIR__."/cache/");
        $barcode = $requisition_expenses->code;
        $data['barcode'] = '<img src="data:image/png;base64,' . $d->getBarcodePNG($barcode, "C128", 1, 50) . '" alt="' . $requisition_expenses->code . '"   />';
        $data = $this->company($data);

        $data['accounts'] = CashOrBankAccount::authMember()->authCompany()->active()->get();
        $data['report_title'] = "Requisition Expense -".$barcode;
        if ($request->based == "print") {
            return view('member.requisition_expenses.print', $data);
        } else if($request->based == "download") {
            $pdf = PDF::loadView('member.requisition_expenses.print', $data);
            return $pdf->download($barcode . ".pdf");

        }else {
            return view('member.requisition_expenses.show', $data);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['requisition_expenses'] =  $requisition_expenses = RequisitionExpense::findOrFail($id);
        $data['employees'] = EmployeeInfo::authCompany()->get();
        $data['expenses'] = AccountType::where('name','expenses')->orwhere('parent_id', 23)->authMember()->authCompany()->active()->get();

        $status = ['type' => 'danger', 'message' => 'Requisition Expense: Transaction Completed'];

        if(!$requisition_expenses->transaction_id)
            return view('member.requisition_expenses.edit', $data);
        else
            return redirect()->route('member.requisition_expenses.show', $id)->with('status', $status);

    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request  $request, $id)
    {
        $this->validate($request, $this->validationRules());

        $expense = RequisitionExpense::find($id);

        $employee = EmployeeInfo::findOrFail($request->employee_id);

        $inputs = [];
        $inputs['date'] = $expenseDate = db_date_format($request->date);
        $inputs['employee_id'] = $request->employee_id ?? Auth::user()->employee->id;
        $inputs['total_amount'] = array_sum($request->amount);
        $inputs['updated_by'] = Auth::user()->id;
        $expense->update($inputs);

        $expenses = $request->expense_id;
        $amounts = $request->amount;

        DB::beginTransaction();
        try {

            RequisitionExpenseDetail::where('requisition_expense_id', $expense->id)->delete();

            foreach ($expenses as $key => $value) {

                $expenseType = AccountType::find($value);

                $details = [];
                $details['requisition_expense_id'] = $expense->id;
                $details['account_type_id'] = $value;
                $details['date'] = $expenseDate;
                $details['amount'] = $amounts[$key];

                RequisitionExpenseDetail::create($details);

            }

            $status = ['type' => 'success', 'message' => 'Requisition Expense Add Successfully'];

        } catch (\Exception $e) {

            $status = ['type' => 'danger', 'message' => 'Unable to update'];
            DB::rollBack();
        }

        DB::commit();

        if ($status['type'] == 'success') {
            return redirect()->route('member.requisition_expenses.show', $expense->id)->with('status', $status);
        } else {
            return redirect()->back()->with('status', $status);
        }


    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $requisition = RequisitionExpense::findOrFail($id);

        $modal = Transactions::findOrFail($requisition->transaction_id);

        if($modal)
        {

            $this->transactionRevertAmount($modal->id);
            $data = [];
            foreach ( $modal->transaction_details as $key=>$value) {
                $data[$key]['account_type_id'] = $value->account_type_id;
                $data[$key]['date'] =  $value->date;
                $data[$key]['company_id'] =  $value->company_id;
            }

            $modal->transaction_details()->delete();
            $modal->delete();

            foreach ( $data as $value) {
                $inputs = [];
                $inputs['account_type_id'] = $value['account_type_id'];
                $inputs['date'] =  $value['date'];


                $transactionCheck = TransactionDetail::where('date', $value['date'])->where('account_type_id', $value['account_type_id'])->where('company_id', $value['company_id'])->get();

                if(count($transactionCheck)<1)
                {
                    AccountHeadDayWiseBalance::where('date', $inputs['date'])->where('account_type_id', $value['account_type_id'])->where('company_id', $value['company_id'])->delete();
                }

                $this->updateAccountHeadBalanceByDate($inputs);
                $this->updateSharerBalance($inputs);
                $this->updateCashBankBalance($inputs);
            }
        }

        $requisition->expenseDetails()->delete();
        $requisition->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully Deleted',
            ]
        ], 200);
    }

    public function validationRules()
    {
        $validator = [
            "employee_id" => "required|numeric",
            "date" => "required|date_format:m/d/Y",
            "expense_id" => "required|array|min:1",
            "expense_id.*" => "required|numeric|min:1",
            "amount" => "required|array",
            "amount.*" => "nullable|numeric",
        ];

        return $validator;
    }


    public function approved(Request  $request)
    {
        $id = $request->requisition_expense_id;
        $account_id = $request->account_id;
        $expenseDate = db_date_format($request->date);

        $expense = RequisitionExpense::find($id);

        $employee = EmployeeInfo::findOrFail($expense->employee_id);

        if($expense)
        {

            $trans = [];
            $trans['date'] = $expenseDate;
            $trans['cash_or_bank_id'] = $account_id;
            $trans['initial_balance'] = $total_amount = $expense->total_amount;
            $trans['transaction_method'] = "Payment";
            $set_transaction = $this->set_transaction($trans);

            $cash_bank = CashOrBankAccount::find($account_id);


            $description  = "";
            $inputs = [];
            $inputs['date'] = $expenseDate;
            foreach ($expense->expenseDetails as $key => $value) {

                $expenseType = AccountType::find($value->account_type_id);

                $inputs['account_name'] = $expenseType->display_name;
                $inputs['account_type_id'] = $expenseType->id;
                $inputs['to_account_name'] = '';
                $inputs['amount'] = $value->amount;
                $inputs['sharer_name'] = '';
                $inputs['payment_method_id'] = 1;
                $inputs['transaction_id'] = $set_transaction;
                $inputs['transaction_method'] = 'Payment';
                $inputs['against_account_type_id'] = $cash_bank->account_type_id;
                $inputs['against_account_name'] = $cash_bank->account_type->display_name;
                $inputs['transaction_type'] = 'dr';
                $inputs['description'] = "Expenses by ".$expense->employee->employee_name_id.": code #".$expense->code;
                $transactionDr = $this->createDebitAmount($inputs);

                $description .= $expenseType->display_name." ".$value->amount. (count($expense->expenseDetails)-1 != $key ? "," : "");
            }

            $description = "Expenses by ".$expense->employee->employee_name_id.": code #".$expense->code." => ".$description;

            $inputs['account_name'] = $cash_bank->account_type->display_name;
            $inputs['account_type_id'] = $cash_bank->account_type_id;
            $inputs['to_account_name'] = '';
            $inputs['amount'] = $total_amount;
            $inputs['sharer_name'] = '';

            unset($inputs['against_account_type_id']);
            unset($inputs['against_account_name']);

            $inputs['payment_method_id'] = 1;
            $inputs['transaction_id'] = $set_transaction;
            $inputs['transaction_method'] = 'Payment';
            $inputs['description'] = $description;

            $inputs['transaction_type'] = 'cr';
            $transactionDr = $this->createCreditAmount($inputs);

            $update = [];
            $update['accept_status'] = 1;
            $update['transaction_id'] = $set_transaction;
            $expense->update($update);

            $transaction = Transactions::find($set_transaction);
            $transaction->requisition_expense_id = $id;
            $transaction->save();


            $status = ['type' => 'success', 'message' => 'Requisition Expense Approved'];
        }else{

            $status = ['type' => 'danger', 'message' => 'Requisition Expense not found'];
        }

        return redirect()->back()->with('status', $status);

    }


}