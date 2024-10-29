<?php

namespace App\Http\Controllers\Member;

use App\DataTables\ProjectExpensesDataTable;
use App\DataTables\ProjectExpenseTypesDataTable;
use App\Http\Traits\CompanyInfoTrait;
use App\Http\Traits\SharerCashAndAccount;
use App\Http\Traits\TransactionTrait;
use App\Models\AccountHeadDayWiseBalance;
use App\Models\AccountType;
use App\Models\CashOrBankAccount;
use App\Models\Project;
use App\Models\ProjectExpense;
use App\Models\ProjectExpenseDetail;
use App\Models\ProjectExpenseType;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\DNS1D;

class ProjectExpenseController extends Controller
{
    use SharerCashAndAccount, TransactionTrait, CompanyInfoTrait;

    public function index(ProjectExpensesDataTable  $dataTable)
    {
        $data['projects'] = Project::authCompany()->pluck('project', 'id')->toArray();
        return $dataTable->render('member.project_expenses.index', $data);
    }


    public function create()
    {
        $data['expenses'] = ProjectExpenseType::authCompany()->select('display_name', 'id')->get();
        $data['projects'] = Project::authCompany()->select('project', 'id')->get();
        $data['accounts'] = CashOrBankAccount::authMember()->authCompany()->active()->get();

        return view('member.project_expenses.create', $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules());

        $countExpense = ProjectExpense::groupBy('date')->groupBy('project_id')->count() + 1;

        $project = Project::findOrFail($request->project_id);

        $account_type_id = $project->account_type_id;
        if(!$project->account_type_id)
        {
            $request['name'] = $project->project;
            $request['member_id'] = Auth::user()->member_id;
            $request['parent_id'] = 19;

            $expenseAccount = $this->set_account_head($request);

            $project->account_type_id=$expenseAccount->id;
            $project->save();
            $account_type_id = $expenseAccount->id;
        }

        $inputs = [];
        $inputs['date'] = $expenseDate = db_date_format($request->date);
        $inputs['project_id'] = $request->project_id;
        $inputs['created_by'] = $userId = Auth::user()->id;
        $inputs['company_id'] = Auth::user()->company_id;
        $inputs['code'] = code_generate($request->project_id, $userId, $countExpense);
        $inputs['total_amount'] = array_sum($request->amount);
        $projectExpense = ProjectExpense::create($inputs);

        $expenses = $request->expense_id;
        $amounts = $request->amount;

        DB::beginTransaction();
        try {

        $description = "";
        $total_amount = 0;
            foreach ($expenses as $key => $value) {

                $expenseType = ProjectExpenseType::find($value);

                $details = [];
                $details['project_expense_id'] = $projectExpense->id;
                $details['project_expense_type_id'] = $value;
                $details['amount'] = $amounts[$key];

                ProjectExpenseDetail::create($details);
                $total_amount += $amounts[$key];
                $description .= $expenseType->display_name." ".$amounts[$key]. (count($expenses)-1 != $key ? "," : "");
            }

            $description = "Project Expenses: code #".$projectExpense->code." => ".$description;

            $trans = [];
            $trans['date'] = $expenseDate;
            $trans['initial_balance'] = $total_amount;
            $trans['transaction_method'] = "Payment";
            $set_transaction = $this->set_transaction($trans);

            $transaction = Transactions::find($set_transaction);
            $transaction->project_expense_id = $projectExpense->id;
            $transaction->save();

            $account_type = AccountType::find($account_type_id);
            $against_account_type = $account_type->where('name', 'expenses')->first();

            $inputs['account_name'] = $account_type->display_name;
            $inputs['account_type_id'] = $account_type->id;
            $inputs['to_account_name'] = '';
            $inputs['amount'] = $total_amount;
            $inputs['against_account_type_id'] = $against_account_type->id;
            $inputs['against_account_name'] = $against_account_type->display_name;
            $inputs['sharer_name'] = '';
            $inputs['payment_method_id'] = 1;
            $inputs['transaction_id'] = $set_transaction;
            $inputs['transaction_method'] = 'Payment';
            $inputs['description'] = $description;

            $inputs['transaction_type'] = 'cr';
            $transactionDr = $this->createCreditAmount($inputs);


            $inputs['account_name'] = $against_account_type->display_name;
            $inputs['account_type_id'] = $against_account_type->id;
            $inputs['to_account_name'] = '';
            $inputs['against_account_type_id'] = $account_type->id;
            $inputs['against_account_name'] = $account_type->display_name;
            $inputs['transaction_type'] = 'dr';
            $transactionDr = $this->createDebitAmount($inputs);

            ProjectExpense::where('code', $projectExpense->code)->update(['transaction_id'=>$set_transaction]);

            $status = ['type' => 'success', 'message' => 'Project Expense Add Successfully'];

        } catch (\Exception $e) {

            $status = ['type' => 'danger', 'message' => 'Unable to update'];
            DB::rollBack();
        }

        DB::commit();

         if ($status['type'] == 'success') {
             return redirect()->route('member.project_expenses.show', $projectExpense->id)->with('status', $status);
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
        $data['project_expenses'] =  $project_expenses = ProjectExpense::findOrFail($id);

        $d = new DNS1D();
        $d->setStorPath(__DIR__."/cache/");
        $barcode = $project_expenses->code;
        $data['barcode'] = '<img src="data:image/png;base64,' . $d->getBarcodePNG($barcode, "C128", 1, 50) . '" alt="' . $project_expenses->code . '"   />';
        $data = $this->company($data);


        $data['report_title'] = "Project Expense -".$barcode;
        if ($request->based == "print") {
            return view('member.project_expenses.print', $data);
        } else if($request->based == "download") {
            $pdf = PDF::loadView('member.project_expenses.print', $data);
            return $pdf->download($barcode . ".pdf");

        }else {
            return view('member.project_expenses.show', $data);
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
        $data['project_expenses'] =  $project_expenses = ProjectExpense::findOrFail($id);
        $data['expenses'] = ProjectExpenseType::authCompany()->select('display_name', 'id')->get();
        $data['projects'] = Project::authCompany()->select('project', 'id')->get();
        $data['accounts'] = CashOrBankAccount::authMember()->authCompany()->active()->get();

        return view('member.project_expenses.edit', $data);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request  $request, $id)
    {
        $project_expense = ProjectExpense::findOrFail($id);

        $this->validate($request, $this->validationRules());

        $countExpense = ProjectExpense::groupBy('date')->groupBy('project_id')->count() + 1;

        $project = Project::findOrFail($request->project_id);

        $account_type_id = $project->account_type_id;
        if(!$project->account_type_id)
        {
            $request['name'] = $project->project;
            $request['member_id'] = Auth::user()->member_id;

            $expenseAccount = $this->set_account_head($request);

            $project->account_type_id=$expenseAccount->id;
            $project->save();
            $account_type_id = $expenseAccount->id;
        }

        $inputs = [];
        $inputs['date'] = $expenseDate = db_date_format($request->date);
        $inputs['project_id'] = $request->project_id;
        $inputs['updated_by'] = Auth::user()->id;
        $inputs['total_amount'] = array_sum($request->amount);
        $project_expense->update($inputs);

        $expenses = $request->expense_id;
        $amounts = $request->amount;

        ProjectExpenseDetail::where('project_expense_id',$id)->delete();

        DB::beginTransaction();
        try {

            $description = "";
            $total_amount = 0;
            foreach ($expenses as $key => $value) {

                $expenseType = ProjectExpenseType::find($value);

                $details = [];
                $details['project_expense_id'] = $project_expense->id;
                $details['project_expense_type_id'] = $value;
                $details['amount'] = $amounts[$key];

                ProjectExpenseDetail::create($details);
                $total_amount += $amounts[$key];
                $description .= $expenseType->display_name." ".$amounts[$key]. (count($expenses)-1 != $key ? "," : "");
            }

            $description = "Project Expenses: code #".$project_expense->code." => ".$description;

            $transaction = Transactions::find($project_expense->transaction_id);
            $transaction->date = $expenseDate;
            $transaction->amount = $total_amount;
            $transaction->project_expense_id = $project_expense->id;
            $transaction->save();
            $this->delete_transaction_detail($transaction);

            $set_transaction = $transaction->id;

            $account_type = AccountType::find($account_type_id);
            $against_account_type = $account_type->where('name', 'expenses')->first();

            $inputs['account_name'] = $account_type->display_name;
            $inputs['account_type_id'] = $account_type->id;
            $inputs['to_account_name'] = '';
            $inputs['amount'] = $total_amount;
            $inputs['against_account_type_id'] = $against_account_type->id;
            $inputs['against_account_name'] = $against_account_type->display_name;
            $inputs['sharer_name'] = '';
            $inputs['payment_method_id'] = 1;
            $inputs['transaction_id'] = $set_transaction;
            $inputs['transaction_method'] = 'Payment';
            $inputs['description'] = $description;

            $inputs['transaction_type'] = 'cr';
            $transactionDr = $this->createCreditAmount($inputs);


            $inputs['account_name'] = $against_account_type->display_name;
            $inputs['account_type_id'] = $against_account_type->id;
            $inputs['to_account_name'] = '';
            $inputs['against_account_type_id'] = $account_type->id;
            $inputs['against_account_name'] = $account_type->display_name;
            $inputs['transaction_type'] = 'dr';
            $transactionDr = $this->createDebitAmount($inputs);

            ProjectExpense::where('code', $project_expense->code)->update(['transaction_id'=>$set_transaction]);

            $status = ['type' => 'success', 'message' => 'Project Expense Add Successfully'];

        } catch (\Exception $e) {

            $status = ['type' => 'danger', 'message' => 'Unable to update'];
            DB::rollBack();
        }

        DB::commit();

        if ($status['type'] == 'success') {
            return redirect()->route('member.project_expenses.show', $project_expense->id)->with('status', $status);
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
        $project = ProjectExpense::findOrFail($id);

        $modal = Transactions::findOrFail($project->transaction_id);

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

        $project->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully Deleted',
            ]
        ], 200);
    }

    public function validationRules()
    {
        $validator = [
            "project_id" => "required|numeric",
            "date" => "required|date_format:m/d/Y",
            "expense_id" => "required|array|min:1",
            "expense_id.*" => "required|numeric|min:1",
            "amount" => "required|array",
            "amount.*" => "nullable|numeric",
        ];

        return $validator;
    }


}
