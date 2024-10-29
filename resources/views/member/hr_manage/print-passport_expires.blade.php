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
                <th>Designation</th>
                <th>Expired Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach($passport_expires as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->employeeID }}</td>
                    <td>{{ $value->uc_full_name }}</td>
                    <td>{{ $value->phone2 }}</td>
                    <td>{{ $value->designation->name }}</td>
                    <td>{{ $value->passport_expire ? create_date_format($value->passport_expire,'/') : '' }}</td>
                </tr>
            @endforeach
            @if(count($passport_expires) == 0)
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
