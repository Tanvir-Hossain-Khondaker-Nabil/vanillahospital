<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 5/4/2023
 * Time: 9:38 AM
 */

?>
<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/22/2019
 * Time: 4:58 PM
 */



$total  = 0;
?>


@include('member.reports.print_head')

<style>
    .table tbody tr td, .table thead tr th, .table tbody tr th, .table thead tr td {
        font-size: 10px;
    }
</style>

<body>
<div id="page-wrap">

    @include('member.reports.company')
    <div style="width: 100%; text-align: center; margin-bottom: 20px;">
        @if(isset($report_date))<h3>{!! $report_date !!} </h3>@endif
        @if(isset($employee))


                <h3><label style="width: 200px;">Employee Name: </label>{!! $employee->uc_full_name !!} </h3><h3><label style="width: 200px;">Employee ID:</label> {!! $employee->employeeID !!}<h3><label style="width: 200px;">Employee Phone:</label> {!! $employee->phone2 !!}</h3> </h3>
            @endif

    </div>


    <div style="width: 100%; display: flex; flex-wrap: nowrap; overflow: hidden; clear: both;">
        <table class="table table-striped" id="dataTable">

            <tbody>
            <tr>
                <th>SL#</th>
                <th class="text-left" width="180px"> Employee Name</th>
                <th class="text-left" width="180px"> Employee Designation</th>
                <th class="text-right"  > Amount</th>
                <th class="text-right"  >Paid Date</th>
            </tr>
            @foreach($modal as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td class="text-left">{{ $value->employee->employee_name_id }}</td>
                    <td class="text-left">{{ $value->employee->designation->name }}</td>

                    <td class="text-right">{{ create_money_format($value->amount)  }}</td>
                    <td class="text-right">{{ db_date_month_year_format($value->paid_date)  }}</td>

                </tr>

                @php
                    $total += $value->amount;
                @endphp
            @endforeach
            <tr>
                <th colspan="3" class="text-right">Total Paid</th>
                <th class="text-right">{{ create_money_format($total) }}</th>
                <td colspan="1"></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

