<?php

namespace App\Http\Controllers\Member;

use App\Http\Traits\SharerCashAndAccount;
use App\Http\Traits\TransactionTrait;
use App\Models\AccountType;
use App\Models\EmployeeInfo;
use App\Models\EmployeeSalary;
use App\Models\SalaryManagement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EmployeeSalaryController extends Controller
{
    use TransactionTrait, SharerCashAndAccount;

    public function employee_salary_paid(Request $request)
    {
        $data['emp_salary'] = '';
        if($request->salary_id)
        {
            $data['emp_salary'] = $emp_salary = SalaryManagement::findOrFail($request->salary_id);

            if($emp_salary->given_status == 1)
            {
                $status = ['type' => 'warning', 'message' => 'Already Salary Paid'];
            }

        }else{

        }

        $data['employees'] = EmployeeInfo::get()->pluck('employee_name_id', 'id');

        return view('member.employee.salary_distribute', $data);
    }


    public function save_employee_salary_paid(Request $request)
    {

        $employee = EmployeeInfo::findOrFail($request->employee_id);

        if($employee->account_type_id == null)
        {
            $request['name'] = $employee->employee_name_id;
            $save = $this->set_account_head($request, '');
            $employee->update(['account_type_id'=>$save->id]);


            $inputs = [];
            $inputs['cash_or_bank_id'] = null;
            $inputs['supplier_id'] = null;
            $inputs['date'] = $employee->join_date;
            $inputs['amount'] = $inputs['initial_balance'] = 0;
            $set_transaction = $this->set_transaction($inputs);

            $account_type = new AccountType();
            $against_account_type = $account_type->where('name', 'current_liabilities')->first();

            $inputs['account_name'] = $save->display_name;
            $inputs['account_type_id'] = $save->id;
            $inputs['to_account_name'] = '';
            $inputs['against_account_type_id'] = $against_account_type->id;
            $inputs['against_account_name'] = "Balance B/F";
            $inputs['sharer_name'] = '';
            $inputs['payment_method_id'] = 1;
            $inputs['transaction_id'] = $set_transaction;
            $inputs['transaction_method'] = 'Initial';
            $inputs['description'] = '';

            $inputs['transaction_type'] = 'dr';
            $transactionDr = $this->createDebitAmount($inputs);

        }


        $employee = EmployeeInfo::findOrFail($request->employee_id);
        $salary_employee = SalaryManagement::findOrFail($request->salary_id);

        $paid_amount = create_float_format($request->paid_amount);
        $net_payable = create_float_format($salary_employee->net_payable);
        $advDescription = "";

        $description = "ACC-Num#".$employee->bank_account." ".($employee->bank_id ? $employee->bank->display_name."-" : "").($employee->bank_branch_id ? $employee->bank_branch->branch_name:"");


        if($paid_amount>=$net_payable)
        {

            if($paid_amount>$net_payable)
            {
                $paidExtra = $paid_amount-$net_payable;

                $month = array_search($salary_employee->en_month, get_months());

                $next = Carbon::create($salary_employee->en_year, $month+1, 1, 0,0,0)->addMonth();


                $salaryHasEmployee = SalaryManagement::where("emp_id", $salary_employee->emp_id)
                    ->where('en_month', $next->format("F"))
                    ->where('en_year', $next->format("Y"))
                    ->first();

                if (!$salaryHasEmployee) {

                    $workingDays = countWorkingDaysInMonth($next->format("Y"), $next->format("m"));

                    $salaryData = [];
                    $salaryData['emp_id'] = $salary_employee->emp_id;
                    $salaryData['emp_name'] = $salary_employee->employee->first_name . " " . $salary_employee->employee->last_name;
                    $salaryData['emp_designation'] = $salary_employee->employee->designation->name;
                    $salaryData['en_month'] = $next->format("F");
                    $salaryData['en_year'] = $next->format("Y");
                    $salaryData['work_day'] = $workingDays;
                    $salaryData['company_id'] = $salary_employee->employee->company_id;
                    $salaryData['advance_payment'] = $paidExtra;

                    SalaryManagement::create($salaryData);

                } else {
                    $advDescription = "Advance ";
                    $salaryHasEmployee->advance_payment = $salaryHasEmployee->advance_payment+$paidExtra;
                    $salaryHasEmployee->net_payable = $salaryHasEmployee->net_payable-$paidExtra;
                    $salaryHasEmployee->save();
                }
            }

            $salaryUpdate = $salary_employee->update(['sign'=>1, 'given_status'=>1]);

        }else{
            $advDescription = "Advance ";
            $salary_employee->advance_payment = $paid_amount;
            $salary_employee->net_payable = $salary_employee->net_payable-$paid_amount;
            $salary_employee->save();
        }

        $inputs = [];
        $inputs['transaction_method'] = 'Payment';
        $inputs['cash_or_bank_id'] = null;
        $inputs['supplier_id'] = null;
        $inputs['date'] = $date =  db_date_format($request->given_date);
        $inputs['amount'] = $inputs['initial_balance'] = $paid_amount;
        $set_transaction = $this->set_transaction($inputs);

        $account_type = AccountType::find($employee->account_type_id);
        $against_account_type = $account_type->where('name', 'salary')->first();

        $inputs['account_name'] = $account_type->display_name;
        $inputs['account_type_id'] = $account_type->id;
        $inputs['to_account_name'] = '';
        $inputs['against_account_type_id'] = $against_account_type->id;
        $inputs['against_account_name'] = $against_account_type->display_name;
        $inputs['sharer_name'] = '';
        $inputs['payment_method_id'] = 1;
        $inputs['transaction_id'] = $set_transaction;
        $inputs['transaction_method'] = 'Payment';
        $inputs['description'] = $advDescription." Salary Paid". ($description?" INTO ".$description:"");

        $inputs['transaction_type'] = 'cr';
        $transactionDr = $this->createCreditAmount($inputs);


        $inputs['account_name'] = $against_account_type->display_name;
        $inputs['account_type_id'] = $against_account_type->id;
        $inputs['to_account_name'] = '';
        $inputs['against_account_type_id'] = $account_type->id;
        $inputs['against_account_name'] = $account_type->display_name;
        $inputs['transaction_type'] = 'dr';
        $transactionDr = $this->createDebitAmount($inputs);



        $employeeSalary = [];
        $employeeSalary['employee_id'] = $employee->id;
        $employeeSalary['transaction_id'] = $set_transaction;
        $employeeSalary['salary'] = $employee->salary;
        $employeeSalary['deduct_salary'] = 0;
        $employeeSalary['given_date'] = $date;
        $employeeSalary['given_salary'] = $paid_amount;
        $employeeSalary['salary_id'] = $request->salary_id;
        $employeeSalary['created_by'] = Auth::user()->id;
        $employeeSalary['member_id'] = Auth::user()->member_id;
        $employeeSalary['company_id'] = Auth::user()->company_id;

        EmployeeSalary::create($employeeSalary);

        $status = ['type' => 'success', 'message' => ' Salary Paid Successfully'];

        return redirect()->back()->with('status', $status);

    }






}
