<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/30/2019
 * Time: 3:03 PM
 */
 
 $total = 0;
?>

@include('member.reports.print_head')
<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="overflow: hidden; clear: both;">
        <table cellspacing="0" width="100%" class="table">
            <thead>
            <tr>
                <th>#SL</th>
                <th>{{__('common.product_code')}}</th>
                <th>{{__(('common.product_name'))}}</th>
                <th> {{__('common.stock')}}</th>
                <th class="text-right">  {{__('common.price')}}</th>
                <th class="text-right"> {{__("common.total_price")}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($stocks as $key=>$value)
            
            @php
            $total_price = $value->stock*$value->purchase_qty_price;
            $total += $total_price;
            @endphp
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->productCode }}</td>
                    <td>{{ $value->item_name }}</td>
                    <td>{{ $value->stock." ".$value->unit }}</td>
                    <td class="text-right">{{ create_money_format(create_float_format($value->purchase_qty_price)) }}</td>
                    <td class="text-right">{{ create_money_format(create_float_format($total_price)) }}</td>
                </tr>
            @empty
                <tr>
                    <td class="text-left" colspan="4">{{__('common.no_report_available_for_stock')}}</td>
                </tr>
            @endforelse
            
            @if($total>0)
            
                <tr>
                    <th class="text-right" colspan="5">{{__('common.total_price')}}</th>
                    <th class="text-right">{{ create_money_format(create_float_format($total)) }}</th>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>




