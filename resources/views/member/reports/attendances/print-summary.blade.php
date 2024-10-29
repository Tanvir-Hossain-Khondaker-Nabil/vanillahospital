<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 8/8/2023
 * Time: 4:56 PM
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
                <th>Present</th>
                <th>Extra Day</th>
                <th>Absent</th>
                <th>Weekend</th>
                <th>Holiday</th>
                <th>Leave</th>
            </tr>
            </thead>
            <tbody>
            @foreach($attendances as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->employeeID }}</td>
                    <td>{{ $value->UNAME }}</td>
                    <td>{{ $value->presentday }}</td>
                    <td>{{ $value->extraday }}</td>
                    <td>{{ $value->absentday }}</td>
                    <td>{{ $value->Weekend }}</td>
                    <td>{{ $value->Holiday }}</td>
                    <td>{{ $value->leaveday }}</td>
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

