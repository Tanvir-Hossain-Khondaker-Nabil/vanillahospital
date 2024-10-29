<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 8/8/2023
 * Time: 4:57 PM
 */

?>


@include('member.reports.print_head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="overflow: hidden; clear: both;">
        <table cellspacing="0" width="100%" class="table">
            <thead>
            <tr>
                <th>#SL</th>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Date</th>
                <th>Shift</th>
                <th>In Time</th>
                <th>Out Time</th>
                <th>Late Time</th>
                <th>Attend Status</th>
                <th>Late Status</th>
            </tr>
            </thead>
            <tbody>
            @php
                $total_present = 0;
                $total_absent = 0;
                $total_late = 0;
                $total_leave = 0;
            @endphp
            @foreach($attendances as $key => $value)

                @php

                    /*   if($value->attend_status == 'P' && $value->shift_type == 0)
                       {
                       }else if($value->attend_status == 'P' && $value->shift_type == 1)
                       {
                       }else if($value->attend_status == 'P' && $value->shift_type_adv == 1)
                       {
                       }else if($value->attend_status == 'P' && $value->shift_type_adv == 0)
                       {
                       } */


                    if ($value->late == 'N'){
                        $value->late = '';
                    }
                     if ($value->attend_status == 'Present') {
                        //$value->late <= '00:00:00'
                        // $value->late = '';
//                        $value->attend_status = 'Present';
                        $total_present++;
                    }
                     if ($value->late <= '00:00:00') {

                        $value->late = '';
                    }

                    if ($value->attend_status == 'Weekend') {
//                      $value->intime = 'Weekend';
//                      $value->outtime = 'Weekend';
                      $value->late = '';
                    } else if ($value->attend_status == 'Absent') {
//                      $value->intime = 'Absent';
//                      $value->outtime = 'Absent';
//                      $value->late = 'Absent';
                      $total_absent++;
                    }else if ($value->attend_status == 'Leave') {
//                      $value->intime = 'Leave';
//                      $value->outtime = 'Leave';
//                      $value->late = 'Leave';
                      $total_leave++;
                    }else if ($value->attend_status == 'Holiday') {
//                      $value->intime = 'Holiday';
//                      $value->outtime = 'Holiday';
//                      $value->late = 'Holiday';
                    }


                @endphp

                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->employeeID }}</td>
                    <td>{{ $value->UNAME }}</td>
                    <td>{{ date('M d, Y', strtotime($value->attend_date ))}}</td>
                    <td>{{ $value->shift }}</td>
                    <td>{{ $value->intime }}</td>
                    <td>{{ $value->outtime }}</td>
                    <td>{{ $value->late }}</td>
                    <td>{{ $value->attend_status }}</td>
                    <td>{{ $value->late_status }}</td>
                </tr>
            @endforeach

                @if(count($attendances) == 0)
                    <tr>
                        <td class="text-left" colspan="4">No Report available</td>
                    </tr>
                @else
                    <tr>
                        <th> Total Working Days</th>
                        <th> {{ $workingDays }}</th>
                        <th> Total Present</th>
                        <th> {{ $total_present }}</th>
                        <th> Total Absent</th>
                        <th> {{ $total_absent }}</th>
                        <th> Total Leave</th>
                        <th> {{ $total_leave }}</th>
                        <th> Total Late</th>
                        <th> {{ $total_late }}</th>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

