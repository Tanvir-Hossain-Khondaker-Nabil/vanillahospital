<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/4/2021
 * Time: 4:44 PM
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
                <th colspan="4">{{__("common.product_sales_report")}}</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="text-left">#SL</th>
                <th class="text-left">{{__('common.product_name')}}</th>
                <th class="text-right" style="padding-right: 30px;">{{__('common.qty')}}</th>
                <th class="text-right">{{__('common.total_price')}}</th>
            </tr>
            @php
                $total_price = 0;
            @endphp
            @foreach($sale_details as $key => $value)
                <tr>
                    <td class="text-left"> {{ $key+1 }}</td>
                    <td class="text-left">{{ $value->item->item_name }}</td>
                    <td class="text-right">{{ $value->total_qty." ".$value->unit }}</td>
                    <td class="text-right">{{ create_money_format($value->sum_total_price) }}</td>
                </tr>
                @php
                    $total_price += $value->sum_total_price
                @endphp

                @if( $loop->last)
                    <tr>
                        <th colspan="2" class="text-right"> {{__('common.total')}}</th>
                        <th class="text-right"> {{ ($sale_details->sum('total_qty')) }}</th>
                        <th class="text-right"> {{ create_money_format($total_price) }}</th>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>

    <hr/>
    <div style="width: 100%; display: flex; flex-wrap: nowrap; overflow: hidden; clear: both; margin-top: 50px;">

        <table class="table table-striped" id="dataTable">
            <thead>
            <tr>
                <th colspan="4">{{__('common.product_purchases_report')}}</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th class="text-left">#SL</th>
                <th class="text-left">{{__('common.product_name')}}</th>
                <th class="text-right" style="padding-right: 30px;">{{__('common.qty')}}</th>
                <th class="text-right">{{__('common.total_price')}}</th>
            </tr>
            @php
                $total_price = 0;
            @endphp
            @foreach($purchase_details as $key => $value)
                <tr>
                    <td class="text-left"> {{ $key+1 }}</td>
                    <td class="text-left">{{ $value->item->item_name }}</td>
                    <td class="text-right">{{ $value->total_qty." ".$value->unit }} </td>
                    <td class="text-right">{{ create_money_format($value->sum_total_price) }}</td>
                </tr>
                @php
                    $total_price += $value->sum_total_price
                @endphp

                @if( $loop->last)
                    <tr>
                        <th colspan="2" class="text-right"> {{__('common.total')}}</th>
                        <th class="text-right"> {{ ($purchase_details->sum('total_qty')) }}</th>
                        <th class="text-right"> {{ create_money_format($total_price) }}</th>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>

</div>
</div>
</body>
</html>

