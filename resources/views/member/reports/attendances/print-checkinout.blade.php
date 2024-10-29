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
                       else if ($value->attend_status == 'P') {
                           //$value->late <= '00:00:00'
                           // $value->late = '';
                           $value->attend_status = 'Present';
                       }
                       else if ($value->late <= '00:00:00') {

                           $value->late = '';
                       }

                       if ($value->attend_status == 'W') {
                         $value->intime = 'Weekend';
                         $value->outtime = 'Weekend';
                         $value->late = '';
                       } else if ($value->attend_status == 'A') {
                         $value->intime = 'Absent';
                         $value->outtime = 'Absent';
                         $value->late = 'Absent';
                       }else if ($value->attend_status == 'L') {
                         $value->intime = 'Leave';
                         $value->outtime = 'Leave';
                         $value->late = 'Leave';
                       }else if ($value->attend_status == 'H') {
                         $value->intime = 'Holiday';
                         $value->outtime = 'Holiday';
                         $value->late = 'Holiday';
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
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

