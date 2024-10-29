<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/22/2019
 * Time: 4:58 PM
 */



 $sales_total = $req_total = 0;
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
        @if(isset($salesman))<h3>Salesman:{!! $salesman->full_name !!} <h3>{!! $salesman->phone !!} <h3>{!! $salesman->email !!}  </h3>@endif
    
    </div>


    <div style="width: 100%; display: flex; flex-wrap: nowrap; overflow: hidden; clear: both;">
        <table class="table table-striped" id="dataTable">
           
            <tbody>
                <tr>
                    <th>SL#</th>
                    <th class="text-left" width="180px"> Req.Inv Date</th>
                    <th class="text-right"  >Req.Inv No</th>
                    <th class="text-right"  >Req. Amount</th>
                    <th class="text-right"  >Dealer</th>
                    <th class="text-right"  >Sales Date</th>
                    <th class="text-right"  >Sales Inv No</th>
                    <th class="text-right"  >Sales Amount</th>

                    @foreach($modal as $key => $value)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td class="text-left">{{ create_date_format($value->date, '/') }}</td>
                            <td class="text-right">{{ $value->id }}</td>
                            <td class="text-right">{{ create_money_format($value->total_price) }}</td>
                            <td class="text-right">{{ $value->dealer ? $value->dealer->name : "" }}</td>
                            <td class="text-right">{{ $value->sales ? create_date_format($value->sales->date) : ""}}</td>
                            <td class="text-right">{{ $value->sales ? create_money_format($value->sales->id) : "" }}</td>
                            <td class="text-right">{{ $value->sales ? create_money_format($value->sales->total_price) : "" }}</td>

                        </tr>

                        @php
                            $req_total += $value->total_price;
                            $sales_total += $value->sales ? $value->sales->total_price : 0;
                        @endphp
                @endforeach
                        <tr>

                            <th class="text-right" colspan="3"> Total </th>
                             <th class="text-center" colspan="2"> {{ create_money_format($req_total) }} </th>
                            <th class="text-right" colspan="2"> Total </th>
                             <th class="text-center" > {{ create_money_format($sales_total) }} </th>
                           
                        </tr>
                </tbody>
        </table>
    </div>
</div>
</body>
</html>

