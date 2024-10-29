<?php

namespace App\Http\Controllers\Member;

use App\Http\Traits\CompanyInfoTrait;
use App\Models\EmpLeave;
use App\Models\EmployeeInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Department;
use App\Models\Designation;
use Barryvdh\DomPDF\Facade as PDF;

class HrController extends Controller
{
    use CompanyInfoTrait;

    public function employee_passport_expires(Request $request)
    {
        $data['departments'] = Department::authCompany()->where('active_status', 1)->get();
        $data['designations'] = Designation::authCompany()->where('active_status', 1)->get();
        $data['countries'] = Country::get()->pluck('countryName', 'id');
        $data['years'] = EmployeeInfo::get_passport_years();

        $today =  date("Y-m-d");
        $query = EmployeeInfo::authCompany();


        $country_id = $request->get('country_id');
        $department_id = $request->get('department_id');
        $designation_id = $request->get('designation_id');
        $month = $request->get('month');
        $year = $request->get('year');
        $expire_date = $request->get('expire_date');

        if( $country_id > 0 )
        {
            $query = $query->where('nationality', $country_id);
        }

        if( $department_id > 0 )
        {
            $query = $query->where('department_id', $department_id);
        }
        if( $designation_id > 0 )
        {
            $query = $query->where('designation_id', $designation_id);
        }

        
        if( $month > 0 && $year>0)
        {
            $query = $query->whereMonth('passport_expire', $month)->whereYear('passport_expire', $year);
        }elseif( $year>0 ){
            $query = $query->whereYear('passport_expire', $year);
        }elseif( $month != "" ){
            $query = $query->whereMonth('passport_expire', $month);
        }

        if($expire_date != "")
        {
            $query = $query->whereDate('passport_expire',"<=", db_date_format($expire_date));
        }else{
            if( $month == '' && $year == "")
            {
                $query = $query->whereDate('passport_expire',"<=", $today);
            }
        }
        


        $data['full_url'] = $request->fullUrl();
        $data['report_title'] = $title = "Passport Expired Employee List";
        $data['passport_expires'] = $query->get();


        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);
            if ($request->type == "print") {
                return view('member.hr_manage.print-passport_expires', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.hr_manage.print-passport_expires', $data);
                $file_name = file_name_generator($title);
                return $pdf->download($file_name . ".pdf");
            }

        } else {
            return view('member.hr_manage.passport_expires', $data);
        }
    }


    public function employee_visa_expires(Request $request)
    {
        
        $data['departments'] = Department::authCompany()->where('active_status', 1)->get();
        $data['designations'] = Designation::authCompany()->where('active_status', 1)->get();
        $data['countries'] = Country::get()->pluck('countryName', 'id');
        $data['years'] = EmployeeInfo::get_passport_years();

        $today =  date("Y-m-d");
        $query = EmployeeInfo::authCompany();


        $country_id = $request->get('country_id');
        $department_id = $request->get('department_id');
        $designation_id = $request->get('designation_id');
        $month = $request->get('month');
        $year = $request->get('year');
        $expire_date = $request->get('expire_date');

        if( $country_id > 0 )
        {
            $query = $query->where('nationality', $country_id);
        }

        if( $department_id > 0 )
        {
            $query = $query->where('department_id', $department_id);
        }
        if( $designation_id > 0 )
        {
            $query = $query->where('designation_id', $designation_id);
        }

        
        if( $month > 0 && $year>0)
        {
            $query = $query->whereMonth('visa_expire', $month)->whereYear('visa_expire', $year);
        }elseif( $year>0 ){
            $query = $query->whereYear('visa_expire', $year);
        }elseif( $month != "" ){
            $query = $query->whereMonth('visa_expire', $month);
        }

        if($expire_date != "")
        {
            $query = $query->whereDate('visa_expire',"<=", db_date_format($expire_date));
        }else{
            if( $month == '' && $year == "")
            {
                $query = $query->whereDate('visa_expire',"<=", $today);
            }
        }


        $data['visa_expires'] = $query->get();
        $data['full_url'] = $request->fullUrl();
        $data['report_title'] = $title = "Visa Expired Employee List";

        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);
            if ($request->type == "print") {
                return view('member.hr_manage.print-visa_expires', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.hr_manage.print-visa_expires', $data);
                $file_name = file_name_generator($title);
                return $pdf->download($file_name . ".pdf");
            }

        } else {
            return view('member.hr_manage.visa_expires', $data);
        }

    }

    public function employee_on_leaves(Request $request)
    {
        $today =  date("Y-m-d");

        $data['departments'] = Department::authCompany()->where('active_status', 1)->get();
        $data['designations'] = Designation::authCompany()->where('active_status', 1)->get();
        $data['countries'] = Country::get()->pluck('countryName', 'id');
        $data['years'] = EmployeeInfo::get_passport_years();

        $country_id = $request->get('country_id');
        $department_id = $request->get('department_id');
        $designation_id = $request->get('designation_id');


        $query = EmployeeInfo::authCompany();

        if( $country_id > 0 )
        {
            $query = $query->where('nationality', $country_id);
        }

        if( $department_id > 0 )
        {
            $query = $query->where('department_id', $department_id);
        }

        if( $designation_id > 0 )
        {
            $query = $query->where('designation_id', $designation_id);
        }

        $empolyees = $query->pluck("id")->toArray();

        $month = $request->get('month');
        $year = $request->get('year');
        $start = $request->get('start_date');
        $end = $request->get('end_date');

        if($start != ""){
            $start_date = db_date_format($start);
        }else{
            $start_date = $today;
        }

        if($end != ""){
            $end_date = db_date_format($end);
        }else{
            $end_date = $today;
        }

        $on_leaves =  EmpLeave::whereIn('emp_id', $empolyees);
        
        if($start != "" || $end != ""){
            $on_leaves = $on_leaves->where(function($query)use($start_date, $end_date){
                return $query
                    ->whereBetween('start_date', [$start_date, $end_date])
                    ->orWhereDate('end_date', ">=", $start_date)
                    ->orWhereDate('end_date', "<=", $end_date );
            })->where('status', 1);
        }else{
        
            $on_leaves = $on_leaves->where(function($query)use($today){
                return $query
                    ->whereDate('start_date', '=', $today)
                    ->orWhereDate('end_date', ">=", $today);
            })->where('status', 1);
        }
        

        $data['on_leaves'] = $on_leaves->get();

        $data['full_url'] = $request->fullUrl();
        $data['report_title'] = $title = "On Leave Employee List";

        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);
            if ($request->type == "print") {
                return view('member.hr_manage.print-on_leave_list', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.hr_manage.print-on_leave_list', $data);
                $file_name = file_name_generator($title);
                return $pdf->download($file_name . ".pdf");
            }

        } else {
            return view('member.hr_manage.on_leave_list', $data);
        }
    }


    public function employee_next_attends(Request $request)
    {

        $today =  date("Y-m-d");
        $data['next_attends'] = EmpLeave::where(function($query)use($today){
            return $query
                ->where('start_date', '=', $today)
                ->orwhere('end_date', '=',$today);
        })->where('status', 1)->get();
        $data['full_url'] = $request->fullUrl();

        $data['report_title'] = $title = "Next Day Attend Employee List";

        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);
            if ($request->type == "print") {
                return view('member.hr_manage.print-next_attend_list', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.hr_manage.print-next_attend_list', $data);
                $file_name = file_name_generator($title);
                return $pdf->download($file_name . ".pdf");
            }

        } else {
            return view('member.hr_manage.next_attend_list', $data);
        }

    }


}
