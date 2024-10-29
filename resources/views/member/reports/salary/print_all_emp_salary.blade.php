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
                <th>Designation</th>
                <th>Gross Salary</th>
                <th>W/D</th>
                <th>Absent</th>
                <th>Present</th>
                <th>E/W</th>
                <th>P/D</th>
                <th>Total W/D</th>
                <th>Total as per Attendance</th>
                {{--<th class="hidden">PerDay</th>--}}
                <th>Adv Realisation</th>
                <th>Bonus</th>
                <th>Net Payable</th>
                <th>Paid Status</th>
                <th>Sign</th>
            </tr>
            </thead>
            <tbody>
            @foreach($salaries as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->employee->employeeID }}</td>
                    <td>{{ $value->emp_name }}</td>
                    <td>{{ $value->emp_designation }}</td>
                    <td class="text-center">{{ create_money_format($value->base_salary) }}</td>
                    <td>{{ $working_days }}</td>
                    <td>{{ $value->total_absent }}</td>
                    <td>{{ $value->total_present }}</td>
                    <td>{{ $value->extra_work }}</td>
                    <td>{{ $value->p_day }}</td>
                    <td>{{ $value->total_work_day }}</td>
                    <td>{{ create_money_format( $value->total_att_amount) }}</td>
                    <td>{{  create_money_format($value->advance_payment) }}</td>
                    <td>{{  create_money_format($value->festival_bonus) }}</td>
                    <td>{{ create_money_format($value->total_att_amount) }}</td>
                    <td class='paid_status'>
                        {{ $value->sign == 1 ? "Paid" : "Unpaid" }}
                    </td>

                    <td>

                    </td>
                </tr>
            @endforeach
            @if(count($salaries) == 0)
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

