<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 15-Dec-19
 * Time: 6:29 PM
 */
?>

@include('member.reports.print_head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="width: 100%; display: flex; flex-wrap: nowrap; overflow: hidden; clear: both;">
        <table class="report-table table table-striped" id="dataTable">

            <tbody>
            <tr>
                <th>#SL</th>
                <th>{{__('common.date')}}</th>
                <th>{{__('common.customer')}}</th>
                <th class="text-left">{{__("common.sale_code")}}</th>
                <th>{{__('common.sale_id')}}</th>
                <th class="text-left">{{__('common.product_name')}}</th>
                <th>{{__('common.unit')}}</th>
                <th class="text-center">{{__('common.qty')}}</th>
                {{-- <th class="text-center">Carton</th>
                <th class="text-center">Free</th> --}}
                <th class="text-right"> {{__('common.price')}}</th>
                {{-- <th class="text-right">Trade Price</th> --}}
                <th class="text-right">{{__('common.total_price')}}</th>
                <th class="text-right">{{__('common.profit_price')}}</th>
            </tr>
            @php
                $last_date = $profit =  0;
                $sale_total_price = $total = $total_qty = 0;
            @endphp
            @foreach($sales as $key => $value)

                @php

                    if(config('settings.sale_profit_by_quotation'))
                   {
                       $purchase_price = $value->quote_purchase_price;
                   }else{
                       $purchase_price = $value->purchase_price;
                   }

                                       $profit_per = ($value->qty*$value->price)-($value->qty*$purchase_price)-$value->discount;
                                       $total += $value->total_price;
                                       $profit += $profit_per;
                @endphp

                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ db_date_month_year_format($value->sale->date) }}</td>
                    <td class="text-left">{{ $value->sale->due > 0 ? $value->sale->customer ? $value->sale->customer->name : "" : "Cash" }}</td>
                    <td class="text-left">{{ $value->sale->sale_code }}</td>
                    <td>{{ $value->sale->id }}</td>
                    <td class="text-left">{{ $value->item->item_name }}</td>
                    <td>{{ $value->unit }}</td>
                    <td class="text-center">{{ $value->qty }}</td>
                    {{-- <td class="text-center">{{ $value->carton }}</td>
                    <td class="text-center">{{ $value->free }}</td> --}}
                    <td class="text-right">{{ create_money_format($value->price) }}</td>
                    {{-- <td class="text-right">{{ create_money_format($value->trade_price) }}</td> --}}
                    <td class="text-right">{{ create_money_format($value->total_price) }}</td>
                    <td class="text-right">{{ create_money_format($profit_per) }}</td>
                </tr>
                @php
                    $last_date = db_date_month_year_format($value->sale->date);
                    $sale_total_price += $value->total_price;
                    $total_qty += $value->qty;
                @endphp

                @if( $loop->last)
                    <tr class=" margin-bottom-20">
                        <th colspan="{{ $item ? 6 : 9 }}" class="text-right">{{__('common.total')}}</th>
                        @if($item)<th colspan="3" class="text-right">{{ $total_qty." ".$value->unit }}</th>@endif
                        <th class="text-right">{{ create_money_format($sale_total_price) }}</th>
                        <th  class="text-right">{{ create_money_format($profit) }}</th>
                    </tr>
                    @php
                        $sale_total_price = 0;
                    @endphp
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

