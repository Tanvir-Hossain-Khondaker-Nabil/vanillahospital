<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

?>


@include('member.reports.print_head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="overflow: hidden; clear: both;">
        <table cellspacing="0" width="100%" class="table">
            <tr>
                <th>#SL</th>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach($next_attends as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->employee->employeeID }}</td>
                    <td>{{ $value->employee->uc_full_name }}</td>
                    <td>{{ $value->employee->phone2 }}</td>
                    <td>{{ $value->start_date ? create_date_format($value->start_date,'/') : '' }}</td>
                    <td>{{ $value->end_date ? create_date_format($value->end_date,'/') : '' }}</td>
                </tr>
            @endforeach
            @if(count($next_attends) == 0)
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
