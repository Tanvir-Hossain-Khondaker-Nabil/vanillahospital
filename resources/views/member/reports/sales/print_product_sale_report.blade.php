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
        <table class="table table-striped" id="dataTable">
            <thead>
            <tr>
                <th>#SL</th>
                <th width="100px">{{__('common.date')}}</th>
                <th>{{__('common.customer')}}</th>
                <th class="text-left">{{__('common.product_name')}}</th>
                <th>{{__('common.unit')}}</th>
                <th class="text-center">{{__("common.quantity")}}</th>
                <th class="text-right">{{__('common.price')}}</th>
                <th class="text-right">{{__('common.total_price')}}</th>
            </tr>
            </thead>
            <tbody>
            @php
                $last_date = $total_qty = 0;
                $sale_total_price = 0;
            @endphp
            @foreach($sales as $key => $value)
                {{--                @if($last_date!=0 && $last_date!=db_date_month_year_format($value->sale->date))--}}
                {{--                    <tr class=" margin-bottom-20">--}}
                {{--                        <th colspan="6" class="text-right">Total</th>--}}
                {{--                        <th colspan="2" class="text-right">{{ create_money_format($sale_total_price) }}</th>--}}
                {{--                    </tr>--}}
                {{--                    @php--}}
                {{--                        $sale_total_price = 0;--}}
                {{--                    @endphp--}}
                {{--                @endif--}}
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td width="100px">{{ db_date_month_year_format($value->sale->date) }}</td>
                    <td class="text-left">{{ $value->sale->due > 0 ? $value->sale->customer ? $value->sale->customer->name : "" : "Cash" }}</td>
                    <td class="text-left">{{ $value->item->item_name }}</td>
                    <td>{{ $value->unit }}</td>
                    <td class="text-center">{{ $value->qty }}</td>
                    <td class="text-right">{{ create_money_format($value->price) }}</td>
                    <td class="text-right">{{ create_money_format($value->total_price) }}</td>
                </tr>
                @php
                    $last_date = db_date_month_year_format($value->sale->date);
                    $sale_total_price += $value->total_price;
                     $total_qty += $value->qty;
                @endphp

                @if( $loop->last)
                    <tr class=" margin-bottom-20">
                        <th colspan="{{ $item ? '4' : '6' }}" class="text-right">{{__('common.total')}}</th>
                        @if($item)<th colspan="2" class="text-right">{{ $total_qty." ".$value->unit }}</th>@endif
                        <th colspan="2" class="text-right">{{ create_money_format($sale_total_price) }}</th>
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

