<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/22/2019
 * Time: 4:58 PM
 */

$total_dr = 0;
$total_cr = 0;
$total_opening =0;
?>


@include('member.reports.print_head')

<body>
<div id="page-wrap">

    @include('member.reports.company')
    <div style="width: 100%; text-align: center; margin-bottom: 20px;">
        @if(isset($report_date))<h3>{!! $report_date !!} </h3>@endif
    </div>


    <div style="width: 100%; display: flex; flex-wrap: nowrap; overflow: hidden; clear: both;">
        <table class="table table-striped" id="dataTable">
            <tbody>
            <tr>
                <th rowspan="2"  width="20px">SL#</th>
                <th rowspan="2"  width="20px">GL Code</th>
                <th  rowspan="2">Particulars</th>
                @if($opening_balance)<th class="text-center"  rowspan="2">Opening Balance</th>@endif
                @if($total_dr_Cr)
                    <th colspan="2" class="text-center"> Transactions</th>
                @endif
                <th class="text-center"  rowspan="2">Closing Balance</th>
                <th class="text-center"  rowspan="2" width="65px">Last Payment Date</th>
            </tr>
            <tr>
                @if($total_dr_Cr)
                    <th class="text-center">Debit</th>
                    <th class="text-center">Credit</th>
                @endif
            </tr>
            @php $i = 1; @endphp
            @foreach($modal as $key => $value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $value->id }}</td>
                        <td>{{ $value->display_name }}</td>
                        @if($opening_balance)<td class="text-right">{{ $value->opening_balance != 0 ? create_money_format($value->opening_balance) : "" }}</td>@endif
                        @if($total_dr_Cr)
                            <td class="text-right">{{ $value->total_dr>0 ? create_money_format($value->total_dr) : "" }}</td>
                            <td class="text-right">{{ $value->total_cr>0 ? create_money_format($value->total_cr) : "" }}</td>
                        @endif
                        <td class="text-right">{{ $value->closing_balance }}</td>
                        <td class="text-center">{{ db_date_month_year_format($value->last_payment_date) }}</td>
                    @php
                        $i++;
                    @endphp
                </tr>
                @php
                    $total_dr += $value->total_dr;
                    $total_cr += $value->total_cr;
                    $total_opening += $value->opening_balance;
                @endphp
            @endforeach

            <tr>
                @php
                    $colspan=2;
                @endphp
                <th class="text-center" colspan="{{ $colspan }}"> Total </th>
                @if($opening_balance)<th class="text-right">{{ ($total_opening  > 0 ? create_money_format($total_opening)." Dr" : create_money_format( $total_opening*(-1) )." Cr")  }}</th>@endif
                @if($total_dr_Cr)
                    <th class="text-right">{{ $total_dr  > 0 ? create_money_format($total_dr) . " Dr" : ""   }}</th>
                    <th class="text-right">{{ $total_cr > 0 ? create_money_format($total_cr) . " Cr" : "" }}</th>
                @endif
                <th class="text-right">{{ $total_closing_balance<0 ? create_money_format($total_closing_balance*(-1))." Cr" : create_money_format($total_closing_balance)." Dr" }}</th>
                <td></td>
            </tr>

            </tbody>
        </table>
    </div>
</div>
</body>
</html>

