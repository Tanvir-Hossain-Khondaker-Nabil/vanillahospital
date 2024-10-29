<?php

namespace App\Http\Controllers\Member;

use App\Http\Traits\CompanyInfoTrait;
use App\Models\AttendanceMaster;
use App\Models\Company;
use App\Models\SalaryManagement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalaryManagementController extends Controller
{

    use CompanyInfoTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $year = $request->year;
        $month = $request->month;

        $salaries = [];


        if (isset($request->year) && isset($request->month)) {
            $this->getSalaries($month, $year);
            $data['working_days'] = countWorkingDaysInMonth($year, $month);
            $salaries = SalaryManagement::where('en_month', $month)->where('en_year', $year)->get();
        }


        $data['month'] = $month ?? null;
        $data['year'] = $year ?? null;
        $data['years'] = AttendanceMaster::select('atYear')->groupBy('atyear')
            ->orderBy('atyear', 'desc')->pluck('atYear');
        $data['months'] = AttendanceMaster::select('atmonth')->groupBy('atmonth')
            ->orderBy(DB::raw('month(attend_date)'), 'desc')->pluck("atmonth");
        $data['salaries'] = $salaries;
        $data['full_url'] = $request->fullUrl();

        return view('member.salary.management', $data);
    }

    public function getSalaries($month, $year)
    {

//        $result = DB::table('attendence_master as a')
//            ->selectRaw("e.deleted_at, d.name as designation_name, a.Badgenumber, a.employee_id, e.company_id as emp_company_id
//                        e.id as emp_id, e.employeeID, e.salary, e.first_name, e.last_name,
//                        a.in_time, a.out_time, a.over_time, a.attend_status,
//                        a.atmonth, a.atyear,
//                        COUNT(CASE WHEN a.attend_status = 'P' THEN a.attend_status END) AS presentday,
//                        COUNT(CASE WHEN a.attend_status_extra = 'E' THEN a.attend_status_extra END) AS extraday,
//                        COUNT(CASE WHEN a.attend_status = 'A' THEN a.attend_status END) AS absentday,
//                        COUNT(CASE WHEN a.attend_status = 'W' THEN a.attend_status END) AS Weekend,
//                        COUNT(CASE WHEN a.attend_status = 'H' THEN a.attend_status END) AS Holiday,
//                        COUNT(CASE WHEN a.attend_status = 'L' THEN a.attend_status END) AS leaveday,
//                        DAY(LAST_DAY(a.attend_date)) AS MonthDay")
//            ->join('employee_info as e', 'a.Badgenumber', '=', 'e.employeeID')
//            ->join('designations as d', 'd.id', '=', 'e.designation_id')
//            ->where('a.atmonth', '=', "'".$month."'")
//            ->where('a.atyear', '=', "'".$year."'")
//            ->where('a.isLock', '=', 0)
//            ->whereNotNull('e.deleted_at')
//            ->groupBy('a.Badgenumber')
//            ->orderBy(DB::raw('a.Badgenumber + 0'), 'ASC')
//            ->get();

        $query = "SELECT e.deleted_at, d.name AS designation_name, a.Badgenumber, a.employee_id,
                    e.company_id AS emp_company_id, e.salary_system,
                    e.id AS emp_id, e.employeeID, e.salary, e.first_name, e.last_name,
                    a.in_time, a.out_time, a.over_time, a.attend_status,
                    a.atmonth, a.atyear,
                    COUNT(CASE WHEN a.attend_status = 'P' THEN a.attend_status END) AS presentday,
                    COUNT(CASE WHEN a.attend_status_extra = 'E' THEN a.attend_status_extra END) AS extraday,
                    COUNT(CASE WHEN a.attend_status = 'A' THEN a.attend_status END) AS absentday,
                    COUNT(CASE WHEN a.attend_status = 'W' THEN a.attend_status END) AS Weekend,
                    COUNT(CASE WHEN a.attend_status = 'H' THEN a.attend_status END) AS Holiday,
                    COUNT(CASE WHEN a.attend_status = 'L' THEN a.attend_status END) AS leaveday,
                    DAY(LAST_DAY(a.attend_date)) AS MonthDay,
                    SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(a.out_time, a.in_time)))) AS total_hours_worked
                    FROM `attendence_master` AS `a`
                    INNER JOIN `employee_info` AS `e` ON `a`.`Badgenumber` = `e`.`employeeID`
                    INNER JOIN `designations` AS `d` ON `d`.`id` = `e`.`designation_id`
                    WHERE `a`.`atmonth` = '$month' AND `a`.`atyear` = '$year'
                        AND `a`.`isLock` = 1 AND `e`.`deleted_at` IS NULL
                    GROUP BY `a`.`Badgenumber`
                    ORDER BY a.Badgenumber + 0 ASC";


        $result = DB::select($query);

        $workingDays = countWorkingDaysInMonth($year, $month);


        foreach ($result as $value) {

            $row = (array)$value;

            $total_hours_worked =  $row['total_hours_worked'];
            $parts = explode(":", $total_hours_worked);

            // Convert to minutes (assuming "hour:min:sec" format)
            $hours = $parts[0] + $parts[1]/60 + $parts[2] / 60;
            $total_hours_worked = $hours;

//            $inTime =  Carbon::parse($row['in_time']);
//            $outTime =  Carbon::parse($row['out_time']);
//
//            $hoursWorked = $outTime->diffInHours($inTime);

//            $perDay = (intval($row['salary'])) / $workingDays;
//            $absent_detection = $row['absentday'] * $perDay;
//            $extra_work = $row['extraday'];
//            $total_attendance = ($row['presentday'] + $extra_work);
//            $total_attendance_amount = $total_attendance * $perDay;
//            $payable = ($total_attendance * $perDay);
//            $over_time_amount = $extra_work * $perDay;
//
//            $per_hour = $perDay / 8;
//            $overTime_and_absent = $row['presentday'] - $workingDays;


            $salaryHasEmployee = SalaryManagement::where("emp_id", $value->emp_id)
                ->where('en_month', $value->atmonth)
                ->where('en_year', $value->atyear)
                ->first();

            $company = Company::find($value->emp_company_id);
            $tax = $company->tax;

            if($value->salary_system == "Daily")
            {
                $perDay = $row['salary'];
                $absent_amount = $value->absentday * $perDay;
                $extra_work = $salaryHasEmployee ? $salaryHasEmployee->extra_work : $value->extraday;
                $total_attendance = ($value->presentday + $extra_work);
                $total_attendance_amount = $total_attendance * $perDay;
                $payable = ($total_attendance * $perDay);
                $over_time_amount = $extra_work * $perDay;
                $tax_amount = $total_attendance_amount*$tax/100;

                $per_hour = $perDay / 8;
                $overTime_and_absent = $value->presentday - $workingDays;

            }elseif($value->salary_system == "Monthly")
            {
                $perDay = $row['salary'] / $workingDays;
                $absent_amount = $value->absentday * $perDay;
                $extra_work = $salaryHasEmployee ? $salaryHasEmployee->extra_work : $value->extraday;
                $total_attendance = ($value->presentday + $extra_work);
                $total_attendance_amount = $total_attendance * $perDay;
                $payable = ($total_attendance * $perDay);
                $over_time_amount = $extra_work * $perDay;
                $tax_amount = $total_attendance_amount*$tax/100;

                $per_hour = $perDay / 8;
                $overTime_and_absent = $value->presentday - $workingDays;

            }elseif ($value->salary_system == "Hourly")
            {
                $per_hour = $row['salary'];
                $absent_amount = $value->absentday;
                $extra_work = $salaryHasEmployee ? $salaryHasEmployee->extra_work : $value->extraday;
                $total_attendance = ($value->presentday + $extra_work);
                $total_attendance_amount = ($hours * $per_hour);
                $payable = ($hours * $per_hour);
                $tax_amount = $total_attendance_amount*$tax/100;

            }else{

                $absent_amount = $value->absentday;
                $extra_work = $salaryHasEmployee ? $salaryHasEmployee->extra_work : $value->extraday;
                $total_attendance = ($value->presentday + $extra_work);
                $total_attendance_amount = $row['salary'];
                $payable = $row['salary'];
                $tax_amount = $row['salary']*$tax/100;
            }

            $inputs = [];

            if (!$salaryHasEmployee) {

                $inputs['emp_id'] = $value->emp_id;
                $inputs['emp_name'] = $value->first_name . " " . $value->last_name;
                $inputs['emp_designation'] = $value->designation_name;
                $inputs['en_month'] = $value->atmonth;
                $inputs['en_year'] = $value->atyear;
                $inputs['total_present'] = $value->presentday;
                $inputs['total_absent'] = $value->absentday;
                $inputs['total_weekend'] = $value->Weekend;
                $inputs['holiday'] = $value->Holiday;
                $inputs['base_salary'] = $value->salary;
                $inputs['absent_amount'] = $absent_amount;
                $inputs['over_time'] = 0;
                $inputs['over_time_amount'] = $over_time_amount;
                $inputs['festival_bonus'] = 0;
                $inputs['net_payable'] = $payable;
                $inputs['advance_payment'] = 0;
                $inputs['total_hours_worked'] = $total_hours_worked;
                $inputs['total_actual_hours'] = 0;
                $inputs['total_overtime'] = 0;
                $inputs['salary_system'] = $value->salary_system;
                $inputs['total_work_day'] = $total_attendance;
                $inputs['total_att_amount'] = create_float_format($total_attendance_amount);
                $inputs['work_day'] = $workingDays;
                $inputs['extra_work'] = $extra_work;
                $inputs['tax'] = $tax;
                $inputs['tax_amount'] = $tax_amount;
                $inputs['company_id'] = $value->emp_company_id;

                SalaryManagement::create($inputs);

            } else {

//                $total_work_day = $value->presentday + $salaryHasEmployee->extra_work;

//                $salary = $value->salary;
//                $day = $workingDays;
//                $perday = $salary / $day;
//                $total_att_amount = $perday * $total_work_day;
//                $absent_amount = $value->absentday * $perday;


                $net_payable = ($total_attendance_amount + $salaryHasEmployee->festival_bonus)
                    - $salaryHasEmployee->advance_payment;

                $inputs['tax'] = $tax;
                $inputs['tax_amount'] = $tax_amount;
                $inputs['total_present'] = $value->presentday;
                $inputs['absent_amount'] = $absent_amount;
                $inputs['total_absent'] = $value->absentday;
                $inputs['base_salary'] = $value->salary;
                $inputs['net_payable'] = $net_payable;
                $inputs['company_id'] = $value->emp_company_id;
                $inputs['salary_system'] = $value->salary_system;
                $inputs['total_att_amount'] = create_float_format($total_attendance_amount);

                if($salaryHasEmployee->given_status == 0 )
                    $salaryHasEmployee->update($inputs);
            }


        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $year = $request->year;
        $month = $request->month;

        $salaries = [];

        if (isset($request->year) && isset($request->month)) {
            $data['working_days'] = countWorkingDaysInMonth($year, $month);
            $salaries = SalaryManagement::where('en_month', $month)->where('en_year', $year)->get();
        }

        $data['month'] = $month ?? null;
        $data['year'] = $year ?? null;
        $data['years'] = AttendanceMaster::select('atYear')->groupBy('atyear')
            ->orderBy('atyear', 'desc')->pluck('atYear');
        $data['months'] = AttendanceMaster::select('atmonth')->groupBy('atmonth')
            ->orderBy(DB::raw('month(attend_date)'), 'desc')->pluck("atmonth");
        $data['salaries'] = $salaries;
        $data['full_url'] = $request->fullUrl();

        $title = "Employee Salary Report";
        $data['report_title'] = $title."<br/> ".$month.", ".$year;

        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);
            if ($request->type == "print") {
                return view('member.reports.salary.print_all_emp_salary', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.salary.print_all_emp_salary', $data);
                $file_name = file_name_generator($title);
                return $pdf->download($file_name . ".pdf");
            }

        } else {

            return view('member.salary.update', $data);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        $year = $request->year;
        $month = $request->month;

        $data['month'] = $month ?? null;
        $data['year'] = $year ?? null;
        $data['emp_salary'] = $emp_salary = SalaryManagement::findOrFail($id);

        $title = "Pay Slip Report - " . $emp_salary->emp_name . " (" . $emp_salary->employee->employeeID . ")";
        $data['report_title'] = "Pay Slip Report"."<br/> ".$month.", ".$year;


        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);
            if ($request->type == "print") {
                return view('member.salary.print_employee_pay_slip', $data);
//                return view('member.salary.print_employee_pay_slip_copy', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.salary.print_employee_pay_slip', $data);
                $file_name = file_name_generator($title);
                return $pdf->download($file_name . ".pdf");
            }
        } else {
            return view('member.salary.print_employee_pay_slip', $data);
//            return view('member.salary.print_employee_pay_slip_copy', $data);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function emp_paid_status(Request $request)
    {
        $rules = [
            'salary_id' => 'required',
            'paid' => 'required'
        ];

        $this->validate($request, $rules);

        $salary_id = $request->salary_id;
        $paid = $request->paid;

        $data = SalaryManagement::findOrFail($salary_id)->update(['sign' => $paid]);

        if ($data) {
            $response = ["message" => "Update Successfully", 'status' => 'success'];
        } else {

            $response = ["message" => "Unable to update", 'status' => 'danger'];
        }

        return response($response);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function emp_update_salary(Request $request)
    {
        $rules = [
            'salary_id' => 'required',
            'bonus' => 'required',
            'extra_work' => 'required',
            'advance' => 'required',
            'p_day' => 'required'
        ];

        $this->validate($request, $rules);

        $salary_id = $request->salary_id;
        $bonus = $request->bonus;
        $advance = $request->advance;
        $extra_work = $request->extra_work;
        $p_day = $request->p_day;

        $data = SalaryManagement::findOrFail($salary_id);

        $workingDays = countWorkingDaysInMonth($data->en_year, $data->en_month);

        $inputs = [];
        $inputs['festival_bonus'] = $bonus;
        $inputs['advance_payment'] = $advance;
        $inputs['extra_work'] = $extra_work;
        $inputs['p_day'] = $p_day;

        $total_work_day = $data->total_present + $extra_work;

        $salary = $data->base_salary;
        $day = $workingDays;
        $perday = $salary / $day;
        $total_att_amount = $perday * $total_work_day;
        $p_day_amount = $perday * $p_day;
//        $absent_amount = $data->absentday * $perday;
        $net_payable = ($total_att_amount + $bonus) - $advance;


        $inputs['p_day_amount'] = $p_day_amount;
        $inputs['net_payable'] = $net_payable;
        $inputs['total_att_amount'] = number_format($net_payable, 0);

        $data->update($inputs);

        if ($data) {
            $response = [
                "message" => "Update Successfully",
                'status' => 'success',
                'net_payable' => create_money_format($net_payable)
            ];
        } else {

            $response = ["message" => "Unable to update", 'status' => 'danger'];
        }

        return response($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function employee_salary(Request $request)
    {

        $year = $request->year ?? date("Y");
        $month = $request->month ?? '';

        if (Auth::user()->employee) {
            $salaries = SalaryManagement::where('emp_id', Auth::user()->employee->id)
                ->where('en_year', $year);
            if ($month) {
                $salaries = $salaries->where('en_month', $month);
            }

            $salaries = $salaries->get();

        } else {
            $salaries = [];
        }


        $data['month'] = $month ?? null;
        $data['year'] = $year ?? null;
        $data['years'] = AttendanceMaster::select('atYear')->groupBy('atyear')
            ->orderBy('atyear', 'desc')->pluck('atYear');
        $data['months'] = AttendanceMaster::select('atmonth')->groupBy('atmonth')
            ->orderBy(DB::raw('month(attend_date)'), 'desc')->pluck("atmonth");
        $data['salaries'] = $salaries;
        $data['full_url'] = $request->fullUrl();

        $title = "Salary History Report - " . Auth::user()->employee->employeeID;
        $data['report_title'] = "Salary History Report"." - ".($month ? $month.", ": "").$year;

        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);
            if ($request->type == "print") {
                return view('member.reports.salary.print_employee_salary', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.salary.print_employee_salary', $data);
                $file_name = file_name_generator($title);
                return $pdf->download($file_name . ".pdf");
            }
        } else {
            return view('member.salary.employee-salary', $data);
        }


    }
}

