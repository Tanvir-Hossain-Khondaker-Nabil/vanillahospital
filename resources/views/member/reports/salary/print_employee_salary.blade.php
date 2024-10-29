<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 8/8/2023
 * Time: 4:57 PM
 */

$employee =  \Auth::user()->employee;
?>


@include('member.reports.print_head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="overflow: hidden; clear: both;">

            <table cellspacing="0" width="100%" class="table" style="margin-bottom: 15px; border: 0;">

                <tr>
                    <th>Name</th>
                    <td colspan="3" class="text-left"> {{ $employee->uc_full_name }}</td>
                </tr>
                <tr>
                    <th>Employee ID</th>
                    <td class="text-left">{{ $employee->employeeID }}</td>
                    <th>Designation</th>
                    <td class="text-left">{{ $employee->designation->name }}</td>
                </tr>
            </table>

            <table cellspacing="0" width="100%" class="table">
            <thead>
                <tr>
                    <th>#SL</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Gross Salary</th>
                    <th>W/D</th>
                    <th>Absent</th>
                    <th>Present</th>
                    <th>E/W</th>
                    <th>Total W/D</th>
                    <th>Total as per Attendance</th>
                    <th class="hidden">PerDay</th>
                    <th>Adv Realisation</th>
                    <th>Bonus</th>
                    <th>Net Payable</th>
                    <th>Paid Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach($salaries as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->en_year }}</td>
                    <td>{{ $value->en_month }}</td>
                    <td class="text-center">{{ create_money_format($value->base_salary) }}</td>
                    <td>{{ countWorkingDaysInMonth($value->en_year, $value->en_month) }}</td>
                    <td>{{ $value->total_absent }}</td>
                    <td>{{ $value->total_present }}</td>
                    <td>{{ $value->extra_work }}</td>
                    <td>{{ $value->total_work_day }}</td>
                    <td>{{ create_money_format( $value->total_att_amount) }}</td>
                    <td>{{  create_money_format($value->advance_payment) }}</td>
                    <td>{{  create_money_format($value->festival_bonus) }}</td>
                    <td>{{ create_money_format($value->net_payable) }}</td>
                    <td>
                       {{ $value->sign == 1 ? "Paid" : "Unpaid"}}
                    </td>
                </tr>
            @endforeach
            @if(count($salaries) == 0)
                <tr>
                    <td class="text-center" colspan="12">No Report available</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

