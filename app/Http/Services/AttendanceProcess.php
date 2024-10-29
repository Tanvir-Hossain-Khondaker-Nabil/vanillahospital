<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 8/1/2023
 * Time: 4:33 PM
 */

namespace App\Http\Services;


use App\Models\AdvanceShift;
use App\Models\AttendanceMaster;
use App\Models\EmpLeave;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceProcess
{
    public $empID;
    public $attend_date;
    public $company_id;

    public function __construct()
    {

    }

    public function update_shift_late_time_status()
    {
        $empQuery = "";

        if($this->empID)
        {
            $empQuery = "AND e.employeeID = '$this->empID'";
        }

        $query = "SELECT
                    a.employee_id AS emp_id,
                    (
                    SELECT
                        DATE_FORMAT(shifts.late, '%r')
                    FROM
                        shifts
                    WHERE
                        shifts.id = e.shift_id
                ) AS late_time_set,
                CASE WHEN a.attend_status = 'P' THEN TIMEDIFF(
                    a.in_time,
                    (
                    SELECT
                        shifts.late
                    FROM
                        shifts
                    WHERE
                        shifts.id = e.shift_id
                )
                ) WHEN a.attend_status = 'E' THEN '' ELSE ''
                END AS late_time,
                CASE WHEN a.attend_status = 'P' AND a.in_time >(
                    SELECT
                        shifts.late
                    FROM
                        shifts
                    WHERE
                        shifts.id = e.shift_id
                ) THEN 'Late' WHEN a.attend_status = 'E' THEN ''
                END AS late_status,
                (
                    SELECT
                        CONCAT(
                            DATE_FORMAT(shifts.time_in, '%r'),
                            ' - ',
                            DATE_FORMAT(shifts.time_out, '%r')
                        )
                    FROM
                        shifts
                    WHERE
                        shifts.id = e.shift_id
                ) AS shift
                FROM
                    attendence_master a,
                    employee_info e
                WHERE
                    e.user_id = a.employee_id
                    AND a.attend_date = '$this->attend_date'
                GROUP BY
                    e.employeeID";


        $updateList = DB::select($query);

        foreach ($updateList as $value)
        {
            $attendanceUpdate = AttendanceMaster::where('employee_id',$value->emp_id)
                ->where("attend_date", $this->attend_date)->first();

            $inputs = [];
            $inputs['shift'] = $value->shift;
            $inputs['late_time_set'] = $value->late_time_set;
            $inputs['late_time'] = $value->late_time;
            $inputs['late_status'] = $value->late_status;

            $attendanceUpdate->update($inputs);
        }

        return true;
    }


    private function db_statement()
    {
        $list  = DB::select($this->update_shift_late_time_status());

        return $list;
    }

    public function set($emp, $edate)
    {
        if($emp)
        {
            $this->empID = $emp->user_id;
            $this->company_id = $emp->company_id;
        }else{
            $this->company_id = Auth::user()->company_id;
        }

        $this->attend_date = $edate;
    }


    public function get()
    {
        return $this->db_statement();
    }

    public function insert()
    {
        $date = $this->attend_date;

//        (SELECT employee_info.user_id FROM employee_info WHERE employee_info.employeeID = checkinout.Badgenumber),

        $query = "INSERT INTO attendence_master(
                    employee_id, attend_date, in_time, in_time_extra, out_time, out_time_extra,
                    CHECKTIME, over_time, attend_status, lateness, shift, atmonth, atyear, isLock,
                    extraOT, OriginalOT, Badgenumber, out_time_night, company_id
                )
                SELECT
                    USERID,
                    DATE(`CHECKTIME`) ADATE,
                    MIN(TIME(`CHECKTIME`)) INTIME,
                    MIN(TIME(`CHECKTIME`)) INTIMEEXTRA,
                    MAX(TIME(`CHECKTIME`)) OUTIME,
                    MAX(TIME(`CHECKTIME`)) OUTIMEEXTRA,
                    CHECKTIME,
                    0.00,
                    (
                    CASE WHEN MIN(TIME(`CHECKTIME`)) != 0 THEN 'P' WHEN DAYNAME(`CHECKTIME`)
                    =(
                        SELECT weekends.name FROM weekends WHERE weekends.company_id = '$this->company_id'
                    ) THEN 'W' WHEN(
                    SELECT
                        COUNT(`title`) AS holidays
                    FROM
                        `holidays`
                    WHERE
                        '$date' BETWEEN holidays.start_date AND holidays.end_date
                ) > 0 THEN 'H' ELSE 'A'
                    END
                ) AS Seassion,
                '' Lateness,
                '' Shift,
                MONTHNAME('$date'),
                YEAR('$date'),
                FALSE,
                0 ExOT,
                0 OrgOT,
                Badgenumber,
                '00:00:00',
                '$this->company_id'
                FROM
                    `checkinout`
                WHERE
                    DATE(CHECKTIME) = '$date'
                AND USERID IN(
                    SELECT DISTINCT
                        USERID
                    FROM
                        checkinout
                ) AND USERID NOT IN(
                    SELECT
                        attendence_master.employee_id
                    FROM
                        attendence_master
                    WHERE
                        attendence_master.attend_date = '$date'
                )
                GROUP BY
                    USERID,
                    DATE(CHECKTIME)
                ORDER BY
                    DATE(CHECKTIME),
                    USERID";

        DB::statement($query);


        $query2 = "INSERT INTO attendence_master(
                        employee_id,
                        attend_date,
                        in_time,
                        in_time_extra,
                        out_time,
                        out_time_extra,
                        over_time,
                        attend_status,
                        lateness,
                        shift,
                        atmonth,
                        atyear,
                        isLock,
                        extraOT,
                        OriginalOT,
                        CHECKTIME,
                        Badgenumber,
                        out_time_night,
                        company_id
                    )
                    SELECT
                        (select USERID from userinfo where userinfo.Badgenumber = employee_info.employeeID),
                        '$date',
                        '00:00:00',
                        '00:00:00',
                        '00:00:00',
                        '00:00:00',
                        '0.00',
                        (
                            CASE WHEN DAYNAME('$date') =(
                            SELECT
                                weekends.name
                            FROM
                                weekends
                            WHERE
                                weekends.company_id = '$this->company_id'
                        ) THEN 'W' WHEN(
                        SELECT
                            COUNT(`title`) AS holidays
                        FROM
                            `holidays`
                        WHERE
                            '$date' BETWEEN holidays.start_date AND holidays.end_date
                    ) > 0 THEN 'H' ELSE 'A'
                        END
                    ),
                    '',
                    '',
                    MONTHNAME('$date'),
                    YEAR('$date'),
                    FALSE,
                    0.00,
                    0.00,
                    '0000-00-00 00:00:00',
                    employeeID,
                    '00:00:00',
                    '$this->company_id'
                    FROM
                        employee_info
                    WHERE
                        employeeID NOT IN(
                        SELECT DISTINCT
                            (Badgenumber)
                        FROM
                            checkinout
                        WHERE
                            DATE(CHECKTIME) = '$date'
                    ) AND employeeID NOT IN(
                        SELECT DISTINCT
                            (Badgenumber)
                        FROM
                            attendence_master
                        WHERE
                            attend_date = '$date'
                    )";


        DB::statement($query2);


        $this->update_emp_leave();
        $this->set('', $date);
        $this->update_shift_late_time_status();
        $this->update_out_time_night();


        return true;

    }

    public function update_emp_leave()
    {
        $query3 = "SELECT emp_id FROM emp_leave WHERE DATE '$this->attend_date' BETWEEN emp_leave.start_date AND emp_leave.end_date";

        $lists = DB::select($query3);

        foreach ($lists as $value)
        {
            AttendanceMaster::where('employee_id',$value->emp_id)
                ->where("attend_date", $this->attend_date)->update([ 'attend_status' => 'L']);
        }
    }

    public function update_advance_shift()
    {
        $query3 = "SELECT * FROM advance_shift WHERE DATE '$this->attend_date' BETWEEN advance_shift.start_date AND advance_shift.end_date";

        $lists = DB::select($query3);

        foreach ($lists as $value)
        {
            $inputs = [];
            $inputs['attend_status_extra'] = "E";
            $inputs['adv_shift_id'] = $value->shift_id;
            $inputs['shift_type'] = $value->shift_type;

            AttendanceMaster::where('employee_id',$value->emp_id)
                ->where("attend_date", $this->attend_date)->update($inputs);
        }
    }

    public function update_out_time_night()
    {
        $lists = AttendanceMaster::where('attend_date', $this->attend_date)->get();

        foreach ($lists as $value)
        {

            $modifiedDate = Carbon::parse($this->attend_date)->subDay()->toDateString();

            $inputs = [];
            $inputs['out_time_night'] = $value->in_time;

            AttendanceMaster::where('employee_id',$value->emp_id)
                ->where("attend_date", $modifiedDate)->update($inputs);
        }
    }


//    public function get_attendance()
//    {
//        $query = "SELECT
//                        a.employee_id,
//                        u.Badgenumber,
//                        u.USERID,
//                        CONCAT(e.first_name, ' ', e.last_name) AS UNAME,
//                        a.attend_date,
//                    CASE
//                        WHEN a.attend_status = 'A' THEN 'Absent'
//                        WHEN a.attend_status = 'W' THEN 'Weekend'
//                        WHEN a.attend_status = 'P' THEN 'Present'
//                        WHEN a.attend_status = 'H' THEN 'Holiday'
//                        WHEN a.attend_status = 'L' THEN 'Leave'
//                    END AS attend_status,
//
//                    CASE
//                        WHEN a.attend_status = 'P' THEN DATE_FORMAT(a.in_time, '%r')
//                    END AS intimePrev,
//
//                    CASE
//                        WHEN a.attend_status = 'P' AND a.shift_type = 0
//                            THEN DATE_FORMAT(a.in_time, '%r')
//                        WHEN a.attend_status = 'P' AND a.shift_type = 1
//                            THEN DATE_FORMAT(a.out_time, '%r')
//                        WHEN a.attend_status = 'E' AND a.shift_type_adv = 1
//                            THEN DATE_FORMAT(a.in_time_extra, '%r')
//                        WHEN a.attend_status = 'E' AND a.shift_type_adv = 0
//                            THEN DATE_FORMAT(a.in_time_extra, '%r')
//                        END AS intime,
//                    CASE
//                        WHEN a.attend_status = 'A' THEN 'Absent'
//                        WHEN a.attend_status = 'W' THEN 'Weekend'
//                        WHEN a.attend_status = 'P' THEN 'Present'
//                        WHEN a.attend_status = 'H' THEN 'Holiday'
//                        WHEN a.attend_status = 'L' THEN 'Leave'
//                        WHEN a.attend_status = 'E' THEN 'Extra Day'
//                    END AS attend_status,
//
//                    CASE
//                        WHEN a.attend_status = 'P' AND DATE_FORMAT(a.in_time, '%r') > '00:00:00' AND DATE_FORMAT(a.in_time, '%r') = DATE_FORMAT(a.out_time, '%r')
//                            THEN 'Out Time Missing'
//                        WHEN a.attend_status = 'P'
//                            THEN DATE_FORMAT(a.out_time, '%r')
//                    END AS outtimePrev,
//                    CASE
//                    WHEN a.attend_status = 'P' AND DATE_FORMAT(a.in_time, '%r') > '00:00:00' AND DATE_FORMAT(a.in_time, '%r') = DATE_FORMAT(a.out_time, '%r') THEN 'Out Time Missing'
//                    WHEN a.attend_status = 'P' AND a.shift_type = 0 THEN DATE_FORMAT(a.out_time, '%r')
//                    WHEN a.attend_status = 'P' AND a.shift_type = 1 THEN DATE_FORMAT(a.out_time_night, '%r')
//                    WHEN a.attend_status = 'E' AND a.shift_type_adv = 1 THEN DATE_FORMAT(a.out_time_night, '%r')
//                    WHEN a.attend_status = 'E' AND a.shift_type_adv = 0 THEN DATE_FORMAT(a.out_time_extra, '%r')
//                    END AS outtime,
//                    (
//                        SELECT
//                            DATE_FORMAT(shifts.late, '%r')
//                        FROM
//                            shifts
//                        WHERE
//                            shifts.id = e.shift_id
//                    ) AS s_time_old,
//                    late_time_set AS s_time,
//                    CASE WHEN a.attend_status = 'P' THEN TIMEDIFF(
//                        a.in_time,
//                        (
//                        SELECT
//                            shifts.late
//                        FROM
//                            shifts
//                        WHERE
//                            shifts.id = e.shift_id
//                    )
//                    ) WHEN a.attend_status = 'E' THEN 'N' ELSE 'N'
//                    END AS late_old,
//                    late_time AS late,
//                    CASE WHEN a.attend_status = 'P' AND 0 > TIMEDIFF(
//                        a.in_time,
//                        (
//                        SELECT
//                            shifts.late
//                        FROM
//                            shifts
//                        WHERE
//                            shifts.id = e.shift_id
//                    )
//                    ) THEN 0 > TIMEDIFF(
//                        a.in_time,
//                        (
//                        SELECT
//                            shifts.late
//                        FROM
//                            shifts
//                        WHERE
//                            shifts.id = e.shift_id
//                    )
//                    ) WHEN 0 < TIMEDIFF(
//                        a.in_time,
//                        (
//                        SELECT
//                            shifts.late
//                        FROM
//                            shifts
//                        WHERE
//                            shifts.id = e.shift_id
//                    )
//                    ) THEN 'N'
//                    END AS latenew,
//                    CASE WHEN a.in_time >(
//                        SELECT
//                            shifts.late
//                        FROM
//                            shifts
//                        WHERE
//                            shifts.id = e.shift_id
//                    ) THEN 'Late'
//                    END AS late_statusPrev,
//                    CASE WHEN a.attend_status = 'P' AND a.in_time >(
//                        SELECT
//                            shifts.late
//                        FROM
//                            shifts
//                        WHERE
//                            shifts.id = e.shift_id
//                    ) THEN 'Late' WHEN a.attend_status = 'E' THEN ''
//                    END AS late_status_old,
//                    late_status,
//                    (
//                        SELECT
//                            CONCAT(
//                                DATE_FORMAT(shifts.time_in, '%r'),
//                                ' - ',
//                                DATE_FORMAT(shifts.time_out, '%r')
//                            )
//                        FROM
//                            shifts
//                        WHERE
//                            shifts.id = e.shift_id
//                    ) AS shift_old,
//                    shift
//                    FROM
//                        userinfo u,
//                        attendence_master a,
//                        employee_info e
//                    WHERE
//                        a.Badgenumber = e.employeeID AND a.attend_date = '$this->attend_date'
//                    GROUP BY
//                        a.Badgenumber
//                    ORDER BY
//                        a.Badgenumber + 0";
//
//
//        $attends = DB::select($query);
//
//        return $attends;
//
//    }

    public function getAttendanceByEmployeeDate($employeeID=null)
    {
        $data = AttendanceMaster::where("attend_date", $this->attend_date);

        if($employeeID)
            $data = $data->where('Badgenumber', $employeeID);

        $data = $data
            ->select('*',
                DB::raw('DATE_FORMAT(in_time, "%r") as in_time_str'),
                DB::raw('DATE_FORMAT(out_time, "%r") as out_time_str'),
                DB::raw('DATE_FORMAT(in_time_extra, "%r") as in_time_extra_str'),
                DB::raw('DATE_FORMAT(out_time_night, "%r") as out_time_night_str'),
                DB::raw('DATE_FORMAT(out_time_extra, "%r") as out_time_extra_str')
            )->get();

        return $data;
    }

    public function get_attendance($employeeID=null, $month=null, $year=null)
    {

        $condition = "";

        if($employeeID)
            $condition = $condition." a.Badgenumber = '$employeeID' and";
        if($month)
            $condition = $condition." a.atmonth = '$month' and";
        if($year)
            $condition = $condition." a.atyear = '$year' and";

        if($condition=="")
        {
            $condition = "a.attend_date = '$this->attend_date' and";
        }


        $query = "SELECT
                        a.employee_id,
                        e.employeeID,
                        u.id,
                        CONCAT(e.first_name, ' ', e.last_name) AS UNAME,
                        a.attend_date,
                    CASE
                        WHEN a.attend_status = 'A' THEN 'Absent'
                        WHEN a.attend_status = 'W' THEN 'Weekend'
                        WHEN a.attend_status = 'P' THEN 'Present'
                        WHEN a.attend_status = 'H' THEN 'Holiday'
                        WHEN a.attend_status = 'L' THEN 'Leave'
                        END AS attend_status,
                    CASE
                        WHEN a.attend_status = 'P' THEN DATE_FORMAT(a.in_time, '%r')
                        END AS intimePrev,
                    CASE
                        WHEN a.attend_status = 'P' AND a.shift_type = 0
                            THEN DATE_FORMAT(a.in_time, '%r')
                        WHEN a.attend_status = 'P' AND a.shift_type = 1
                            THEN DATE_FORMAT(a.out_time, '%r')
                        WHEN a.attend_status = 'E' AND a.shift_type_adv = 1
                            THEN DATE_FORMAT(a.in_time_extra, '%r')
                        WHEN a.attend_status = 'E' AND a.shift_type_adv = 0
                            THEN DATE_FORMAT(a.in_time_extra, '%r')
                        END AS intime,
                    CASE
                        WHEN a.attend_status = 'A' THEN 'Absent'
                        WHEN a.attend_status = 'W' THEN 'Weekend'
                        WHEN a.attend_status = 'P' THEN 'Present'
                        WHEN a.attend_status = 'H' THEN 'Holiday'
                        WHEN a.attend_status = 'L' THEN 'Leave'
                        WHEN a.attend_status = 'E' THEN 'Extra Day'
                        END AS attend_status,
                    CASE
                        WHEN a.attend_status = 'P' AND DATE_FORMAT(a.in_time, '%r') > '00:00:00'
                            AND DATE_FORMAT(a.in_time, '%r') = DATE_FORMAT(a.out_time, '%r')
                            THEN 'Out Time Missing'
                        WHEN a.attend_status = 'P' THEN DATE_FORMAT(a.out_time, '%r')
                        END AS outtimePrev,
                    CASE
                        WHEN a.attend_status = 'P' AND DATE_FORMAT(a.in_time, '%r') > '00:00:00'
                            AND DATE_FORMAT(a.in_time, '%r') = DATE_FORMAT(a.out_time, '%r')
                            THEN 'Out Time Missing'
                        WHEN a.attend_status = 'P' AND a.shift_type = 0
                            THEN DATE_FORMAT(a.out_time, '%r')
                        WHEN a.attend_status = 'P' AND a.shift_type = 1
                            THEN DATE_FORMAT(a.out_time_night, '%r')
                        WHEN a.attend_status = 'E' AND a.shift_type_adv = 1
                            THEN DATE_FORMAT(a.out_time_night, '%r')
                        WHEN a.attend_status = 'E' AND a.shift_type_adv = 0
                            THEN DATE_FORMAT(a.out_time_extra, '%r')
                        END AS outtime,
                    (SELECT DATE_FORMAT(shifts.late, '%r') FROM shifts
                        WHERE  shifts.id = e.shift_id ) AS s_time_old,
                    late_time_set AS s_time,
                    CASE WHEN a.attend_status = 'P' THEN TIMEDIFF(a.in_time,
                        ( SELECT  shifts.late FROM shifts
                            WHERE shifts.id = e.shift_id )
                    ) WHEN a.attend_status = 'E' THEN 'N' ELSE 'N'
                    END AS late_old,
                    late_time AS late,
                    CASE WHEN a.attend_status = 'P' AND 0 > TIMEDIFF(
                        a.in_time,
                        ( SELECT   shifts.late FROM shifts   WHERE shifts.id = e.shift_id )
                    ) THEN 0 > TIMEDIFF(
                        a.in_time,
                        ( SELECT   shifts.late FROM shifts   WHERE shifts.id = e.shift_id )
                    ) WHEN 0 < TIMEDIFF(
                        a.in_time,
                         ( SELECT   shifts.late FROM shifts   WHERE shifts.id = e.shift_id )
                    ) THEN 'N'
                    END AS latenew,
                    CASE WHEN a.in_time >
                      ( SELECT shifts.late FROM shifts   WHERE shifts.id = e.shift_id ) THEN 'Late'
                    END AS late_statusPrev,
                    CASE WHEN a.attend_status = 'P' AND a.in_time >
                      ( SELECT   shifts.late FROM shifts   WHERE shifts.id = e.shift_id )
                      THEN 'Late' WHEN a.attend_status = 'E' THEN ''
                    END AS late_status_old,
                    late_status,
                (
                SELECT
                CONCAT(DATE_FORMAT(shifts.time_in, '%r'),' - ',DATE_FORMAT(shifts.time_out, '%r'))
                FROM
                    shifts
                WHERE
                    shifts.id = e.shift_id
                ) AS shift_old,
                    shift
                    FROM
                        users u,
                        attendence_master a,
                        employee_info e
                    WHERE
                         $condition a.Badgenumber = e.employeeID
                    GROUP BY
                        e.employeeID, a.attend_date
                    ORDER BY
                        a.attend_date ";

        $attends = DB::select($query);

        return $attends;

    }


    public function getSummary($month, $year)
    {
//        $query = "SELECT
//                        e.employeeID,
//                        a.Badgenumber,
//                        CONCAT(e.first_name,' ',e.last_name) AS UNAME,
//                        a.attend_status,
//                        COUNT(CASE WHEN a.attend_status = 'P' THEN a.attend_status END) AS presentday,
//                        COUNT(CASE WHEN a.attend_status_extra = 'E' THEN a.attend_status_extra END) AS extraday,
//                        COUNT(CASE WHEN a.attend_status = 'A' THEN a.attend_status END) AS absentday,
//                        COUNT(CASE WHEN a.attend_status = 'W' THEN a.attend_status END) AS Weekend,
//                        COUNT(CASE WHEN a.attend_status = 'H' THEN a.attend_status END) AS Holiday,
//                        COUNT(CASE WHEN a.attend_status = 'L' THEN a.attend_status END) AS leaveday,
//                        DAY(LAST_DAY(a.attend_date)) AS MonthDay
//                FROM userinfo u, attendence_master a,employee_info e
//                where a.Badgenumber = e.employeeID
//                    AND a.atmonth = '$month' AND a.atyear = '$year'
//                GROUP BY a.Badgenumber ORDER BY a.Badgenumber asc";


        $query = AttendanceMaster::select(
            'employee_info.employeeID',
            'attendence_master.Badgenumber',
            DB::raw("CONCAT(employee_info.first_name, ' ', employee_info.last_name) AS UNAME"),
            'attendence_master.attend_status',
            DB::raw("COUNT(CASE WHEN attendence_master.attend_status = 'P' THEN attendence_master.attend_status END) AS presentday"),
            DB::raw("COUNT(CASE WHEN attendence_master.attend_status_extra = 'E' THEN attendence_master.attend_status_extra END) AS extraday"),
            DB::raw("COUNT(CASE WHEN attendence_master.attend_status = 'A' THEN attendence_master.attend_status END) AS absentday"),
            DB::raw("COUNT(CASE WHEN attendence_master.attend_status = 'W' THEN attendence_master.attend_status END) AS Weekend"),
            DB::raw("COUNT(CASE WHEN attendence_master.attend_status = 'H' THEN attendence_master.attend_status END) AS Holiday"),
            DB::raw("COUNT(CASE WHEN attendence_master.attend_status = 'L' THEN attendence_master.attend_status END) AS leaveday"),
            DB::raw("DAY(LAST_DAY(attendence_master.attend_date)) AS MonthDay")
        )
            ->join('employee_info', 'attendence_master.Badgenumber', '=', 'employee_info.employeeID')
            ->where('attendence_master.atmonth', $month)
            ->where('attendence_master.atyear', $year)
            ->groupBy('attendence_master.Badgenumber')
            ->orderBy('attendence_master.Badgenumber', 'asc')
            ->get();


        return $query;
    }



}
