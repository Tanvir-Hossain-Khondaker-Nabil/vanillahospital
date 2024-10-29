<?php

namespace App\Http\Controllers\Member;

use App\Http\Services\AttendanceProcess;
use App\Http\Traits\CompanyInfoTrait;
use App\Models\AttendanceMaster;
use App\Models\EmployeeInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

class AttendanceController extends Controller
{

    use CompanyInfoTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['employees'] = EmployeeInfo::get();

        return view('member.attendances.create', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function process_attendance()
    {
        return view('member.attendances.process');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generate_attendance()
    {


        $employees = EmployeeInfo::latest()->get();

        foreach ( $employees as $employee)
        {
            $empIdName = $employee->employeeID." - ".$employee->uc_full_name;

            $join_date = $employee->join_date;
            $days = Carbon::now()->diffInDays($join_date);

            for ($i=0; $i<=$days; $i++)
            {
                $inputs = [];
                $inputs['Badgenumber'] = $employee->employeeID;
                $inputs['employee_id'] = $employee->user_id;
                $inputs['in_time'] = "08:00:00";
                $inputs['out_time'] = "19:00:00";
                $inputs['attend_date'] = $date = Carbon::parse($join_date)->addDay($i);
                $inputs['isLock'] = 1;
                $inputs['attend_status'] = "P";
                $inputs['atmonth'] = $date->format('F');
                $inputs['atyear'] = $date->format('Y');
                $inputs['company_id'] = $employee->company_id;

                $attend = AttendanceMaster::where('employee_id', $employee->user_id)
                    ->where("attend_date", $date)->first();

                if($attend){
                    $attend->update($inputs);
                }else{

                    AttendanceMaster::create($inputs);
                }

                $attendance = new AttendanceProcess();
                $attendance->set($employee, $date);
                $attendance->update_shift_late_time_status();
            }


        }


        $status = ['type' => 'success', 'message' => 'Generate Attendance Processed Successfully'];

        return $status;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'start_date' => 'required',
            'employee_id' => 'required',
            'time_in' => 'required',
            'time_out' => 'after:time_in|required',
        ];

        $this->validate($request, $rules);

        $employee = EmployeeInfo::findOrFail($request->employee_id);
        $empIdName = $employee->employeeID." - ".$employee->uc_full_name;

        $inputs = [];
        $inputs['Badgenumber'] = $employee->employeeID;
        $inputs['employee_id'] = $employee->user_id;
        $inputs['in_time'] = $request->time_in;
        $inputs['out_time'] = $request->time_out;
        $inputs['attend_date'] = $date = db_date_format($request->start_date);
        $inputs['isLock'] = 1;
        $inputs['attend_status'] = "P";
        $inputs['atmonth'] = Carbon::parse($date)->format('F');
        $inputs['atyear'] = Carbon::parse($date)->format('Y');
        $inputs['company_id'] = $employee->company_id;

       $attend = AttendanceMaster::where('employee_id', $employee->user_id)
           ->where("attend_date", $date)->first();

        if($attend){
            $attend->update($inputs);
        }else{

            AttendanceMaster::create($inputs);
        }

        $attendance = new AttendanceProcess();
        $attendance->set($employee, $date);
        $attendance->update_shift_late_time_status();

        $status = ['type' => 'success', 'message' => 'Manual Attendance Processed Successfully for Date: '.$date.' and Employee: '.$empIdName];

        return back()->with('status', $status);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_process_attendance(Request $request)
    {

        $rules = [
            'attend_date' => 'required',
        ];

        $this->validate($request, $rules);

        $date = db_date_format($request->attend_date);

        AttendanceMaster::where('attend_date', $date)->where('isLock', 0)->delete();

        $process = new AttendanceProcess();
        $process->set('', $date);
        $process->insert();

        $status = ['type' => 'success', 'message' => 'Attendance Processed Successful for Date: '.$date ];

        return back()->with('status', $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function attendance_checkinout(Request $request)
    {
//        $request['attend_date'] = "2023-02-20";
        $date = db_date_format($request->attend_date);

        $process = new AttendanceProcess();
        $process->set('', $date);
        $attendences = $process->get_attendance();
//        $attendences = $process->getAttendanceByEmployeeDate();
//        dd($attendences);

        $data['employees'] = EmployeeInfo::get();
        $data['attendances'] = $attendences;
        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");

        $data['report_title'] = $title = "Attendance Checkinout - ". formatted_date_string($date);

        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);
            if ($request->type == "print") {
                return view('member.reports.attendances.print-checkinout', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.attendances.print-checkinout', $data);
                $file_name = file_name_generator($title);
                return $pdf->download($file_name . ".pdf");
            }

        } else {

            return view('member.attendances.checkinout', $data);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function attendance_master(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $employeeID = $request->employee_id;

        $process = new AttendanceProcess();
        $process->set('', date("Y-m-d"));

        if(isset($request->employee_id))
            $attendences = $process->get_attendance($employeeID, $month, $year);
        else
            $attendences = [];


        $workingDays = 0;
        if($month != "" && $year !='')
            $workingDays = countWorkingDaysInMonth($year, $month);

        $data['years'] = AttendanceMaster::select('atYear')->groupBy('atyear')
            ->orderBy('atyear', 'desc')->pluck('atYear');
        $data['months'] = AttendanceMaster::select('atmonth')->groupBy('atmonth')
            ->orderBy(DB::raw('month(attend_date)'), 'desc')->pluck("atmonth");

        $data['employees'] = EmployeeInfo::get();
        $data['workingDays'] = $workingDays;
        $data['attendances'] = $attendences;
        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");

         $title = "Attendance Master";

        if($month)
            $title = $title." - ".$month.", ".$year;

        $data['report_title'] = $title;

        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);
            if ($request->type == "print") {
                return view('member.reports.attendances.print-master', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.attendances.print-master', $data);
                $file_name = file_name_generator($title);
                return $pdf->download($file_name . ".pdf");
            }

        } else {

            return view('member.attendances.master', $data);
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function attendance_summary(Request $request)
    {
     
        $year = $request->year;
        $month = $request->month;

        $process = new AttendanceProcess();
        $process->set('', date("Y-m-d"));

        $attendences = [];
        if($month != "" && $year!="")
            $attendences = $process->getSummary($month, $year);

        $data['years'] = AttendanceMaster::select('atYear')->groupBy('atyear')
            ->orderBy('atyear', 'desc')->pluck('atYear');
        $data['months'] = AttendanceMaster::select('atmonth')->groupBy('atmonth')
            ->orderBy(DB::raw('month(attend_date)'), 'desc')->pluck("atmonth");
        $data['employees'] = EmployeeInfo::get();
        $data['attendances'] = $attendences;
        $data['full_url'] = $request->fullUrl() . ($request->fullUrl() == $request->url() ? "?" : "&");

        $title = "Attendance Summary";

        if($month)
            $title = $title." - ".$month.", ".$year;

        $data['report_title'] = $title;

        if ($request->type == "print" || $request->type == "download") {

            $data = $this->company($data);
            if ($request->type == "print") {
                return view('member.reports.attendances.print-summary', $data);
            } else if ($request->type == "download") {
                $pdf = PDF::loadView('member.reports.attendances.print-summary', $data);
                $file_name = file_name_generator($title);
                return $pdf->download($file_name . ".pdf");
            }

        } else {

            return view('member.attendances.summary', $data);
        }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}