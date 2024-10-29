<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/22/2019
 * Time: 4:58 PM
 */


$total_opening = $total_qty = $total_item_price = 0;
$total_dr = 0;
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
            <h2>
                @if(isset($division)){{ $division->name }}@endif
                @if(isset($district)){{ ', '.$district->name  }}@endif
                @if(isset($upazilla)){!!  ', '.$upazilla->name !!}@endif
                @if(isset($union)){!! ', <br/>'.$union->name  !!}@endif
                @if(isset($area)){{ ', '.$area->name }}@endif
            </h2>
    </div>


    <div style="width: 100%; display: flex; flex-wrap: nowrap; overflow: hidden; clear: both;">
        <table class="table table-striped" id="dataTable">
            <tbody>
            <tr>
                <th>SL#</th>
                <th class="text-left">Customer Name</th>
                {{--<th >Phone</th>--}}
                <th class="text-left" width="140px"> Product Name</th>
                <th class="text-right"  >Opening Balance</th>
                <th class="text-center"> Total Sales Qty</th>
                <th class="text-center"> Average Price</th>
                <th class="text-center"> Total Sales Price</th>
                <th class="text-right"  >Collection Balance</th>
                {{--<th class="text-right"  >Closing Balance</th>--}}
                <th class="text-center" >Last Payment Date </th>
            </tr>
            @php $i = 1; @endphp
            @foreach($modal as $key => $value)

                <tr>
                    <td>{{ $i }}</td>
                    <td class="text-left">{{ $value->name }}</td>
                    {{--<td>{{ $value->phone }}</td>--}}
                    {{--                                        <td>{{ $value->address }}</td>--}}


                    <td class="text-left">{{ $value->item_name }}</td>
                    <td class="text-right">{{ $value->opening_balance ? ($value->opening_balance > 0 ? create_money_format($value->opening_balance) . " Dr" : ($value->opening_balance < 0 ? create_money_format($value->opening_balance * (-1)) . " Cr" : "")) : "" }}</td>

                    <td class="text-right">{{ $value->item_total_qty }}</td>
                    <td class="text-right">{{ $value->item_price ? create_money_format($value->item_price) : ''  }}</td>
                    <td class="text-right">{{ $value->item_total_price ? create_money_format($value->item_total_price) : '' }}</td>

                    <td class="text-right">{{  $value->total_dr ? create_money_format($value->total_dr) : "" }}</td>
                    {{--<td class="text-right">{{ $value->closing_balance }}</td>--}}

                    <td class="text-center" width="70px">{{ db_date_month_year_format($value->last_payment_date) }}</td>

                    @php
                        $i++;
                    @endphp
                </tr>
                @php
                    $total_dr += $value->total_dr;
                    $total_qty += $value->item_total_qty;
                    $total_item_price += $value->item_total_price;
                    $total_opening += $value->opening_balance;
                @endphp
            @endforeach
            <tr>

                <th class="text-center" colspan="3"> Total </th>
                <th class="text-right">{{ ($total_opening  > 0 ? create_money_format($total_opening)." Dr" : create_money_format( $total_opening*(-1) )." Cr")  }}</th>

                <th class="text-center" > {{ $total_qty }} </th>
                <th></th>
                <th class="text-center" > {{ create_money_format($total_item_price) }} </th>
                <th class="text-center" > {{ create_money_format($total_dr) }} </th>
                {{--<th class="text-right" >{{ $total_closing_balance<0 ? create_money_format($total_closing_balance*(-1))." Cr" : create_money_format($total_closing_balance)." Dr" }}</th>--}}
                <td></td>
            </tr>

            </tbody>
        </table>
    </div>
</div>
</body>
</html>

