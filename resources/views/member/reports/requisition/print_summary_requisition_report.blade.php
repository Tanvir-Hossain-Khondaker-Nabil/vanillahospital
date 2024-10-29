<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/22/2019
 * Time: 4:58 PM
 */


$total_p_req = $total_s_req = $total_s = $total_p = 0;
$total = 0;
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
                    <th class="text-left" width="180px"> Product Name</th>
                    <th class="text-right"  >Purchase Requisition</th>
                    <th class="text-right"  >Purchase Amount</th>
                    <th class="text-right"  >Sale Requisition</th>
                    <th class="text-right"  >Sale Amount</th>
                    <th class="text-right"  >Net Profit/Loss</th>
                </tr>
                @php $i = 1; @endphp
                @foreach($modal as $key => $value)

                @php
                    $profit = $value->p_req_total-$value->p_total+$value->s_req_total-$value->s_total;
                @endphp
                        <tr>
                            <td>{{ $i }}</td>
                            <td class="text-left">{{ $value->item_name }}</td>
                            <td class="text-right">{{ create_money_format($value->p_req_total) }}</td>
                            <td class="text-right">{{ create_money_format($value->p_total) }}</td>
                            <td class="text-right">{{ create_money_format($value->s_req_total) }}</td>
                            <td class="text-right">{{ create_money_format($value->s_total) }}</td>
                            <td class="text-right">{{ create_money_format($profit) }}</td>

                            @php
                                $i++;
                                $total += $profit;
                                $total_p_req += $value->p_req_total;
                                $total_p += $value->p_total;
                                $total_s_req += $value->s_req_total;
                                $total_s += $value->s_total;
                            @endphp
                        </tr>
                        @php
                        @endphp
                        @endforeach
                        <tr>

                            <th class="text-right" colspan="2"> Gross Profit/Loss  </th>
                             <th class="text-center" > {{ create_money_format($total_p_req) }} </th>
                             <th class="text-center" > {{ create_money_format($total_p) }} </th>
                             <th class="text-center" > {{ create_money_format($total_s_req) }} </th>
                             <th class="text-center" > {{ create_money_format($total_s) }} </th>
                             <th class="text-center" > {{ create_money_format($total) }} </th>
                           
                        </tr>
                </tbody>
        </table>
    </div>
</div>
</body>
</html>

