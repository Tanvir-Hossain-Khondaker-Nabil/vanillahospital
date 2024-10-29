<?php

namespace App\Http\Controllers\Member\Reports;

use App\Models\Area;
use App\Models\Company;
use App\Models\Designation;
use App\Models\District;
use App\Models\EmployeeAttendence;
use App\Models\EmployeeInfo;
use App\Models\Region;
use App\Models\StoreVisit;
use App\Models\Thana;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttendanceReportController extends BaseReportController
{

    public function index(Request $request)
    {
        $inputs = $request->all();

        $query = new EmployeeAttendence();

        $query = $this->searchVisitDate($inputs, $query, 'visit_date');


        if (!empty($inputs['employee_id'])) {
            $data['employee'] = EmployeeInfo::find($inputs['employee_id']);
            $query = $query->where('employee_id', $inputs['employee_id']);
        }

        if (!empty($inputs['designation_id'])) {
            $employee = EmployeeInfo::where('designation_id', $inputs['designation_id'])->pluck('id')->toArray();
            $query = $query->whereIn('employee_id', $employee);
        }

        if (!empty($inputs['region_id'])) {
            $employee = EmployeeInfo::where('region_id', $inputs['region_id'])->pluck('id')->toArray();
            $query = $query->whereIn('employee_id', $employee);
        }
        if (!empty($inputs['district_id'])) {
            $employee = EmployeeInfo::where('district_id', $inputs['district_id'])->pluck('id')->toArray();
            $query = $query->whereIn('employee_id', $employee);
        }
        if (!empty($inputs['thana_id'])) {
            $employee = EmployeeInfo::where('thana_id', $inputs['thana_id'])->pluck('id')->toArray();
            $query = $query->whereIn('employee_id', $employee);
        }
        if (!empty($inputs['area_id'])) {
            $employee = EmployeeInfo::where('area_id', $inputs['area_id'])->pluck('id')->toArray();
            $query = $query->whereIn('employee_id', $employee);
        }

        $data['modal'] = $query->with('employee')->get();

        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");


        $start = Carbon::parse($this->fromDate);
        $end = Carbon::parse($this->toDate);

        $data['total_days'] = 0;

        if($start->month == $end->month)
        $data['total_days'] = cal_days_in_month(CAL_GREGORIAN, $start->month, $start->year);

        $data['start_month'] = $start->month;
        $data['end_month'] = $end->month;

        $report_title = "Employee Attendance Report<br/> Date: ".$this->fromDate." to ".$this->toDate." <br/> " ;

        $data['companies'] = Company::get()->pluck('company_name', 'id');
        $data['employees'] = EmployeeInfo::get()->pluck('employee_name_id', 'id');
        $data['designations'] = Designation::active()->get()->pluck('name', 'id');
        $data['regions'] = Region::active()->get()->pluck('name','id');
        $data['districts'] = District::active()->get()->pluck('name','id');
        $data['thanas'] = Thana::active()->get()->pluck('name','id');
        $data['areas'] = Area::active()->get()->pluck('name','id');

        $data['report_title'] = $report_title;

        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);
            if ($request->type == "print") {

                return View('member.reports.employee.print_attendance_report', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.employee.print_attendance_report', $data);
                $file_name = file_name_generator($report_title);

                return $pdf->download($file_name . ".pdf");
            }

        } else {
            return view('member.reports.employee.attendance_report', $data);
        }



    }



    private function searchVisitDate($inputs, $query, $column="visit_date"){

        $fromDate = $toDate = '';
        if (!empty($inputs['from_date']))
            $inputs['from_date'] = $fromDate = db_date_format($inputs['from_date']);

        if (!empty($inputs['to_date']))
            $inputs['to_date'] = $toDate = db_date_format($inputs['to_date']);

        if ($fromDate == null && $toDate != "") {
            $query = $query->whereDate($column, '<=', $toDate);
        } elseif ($fromDate != null && $toDate == null) {
            $query = $query->whereDate($column, '>=', $fromDate);
        } elseif ($fromDate != null && $toDate != null) {
            $query = $query->whereBetween($column, [$fromDate, $toDate]);
        } else {
            $start = new Carbon('first day of this month');
            $fromDate = db_date_format($start);
            $query = $query->whereDate($column, '>=', $fromDate);
        }

        $this->fromDate = db_date_month_year_format($fromDate);
        $this->toDate = db_date_month_year_format($toDate);

        return $query;
    }


}
