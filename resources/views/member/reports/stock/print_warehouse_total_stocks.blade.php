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
            @if($group_by == "no")
                <tr>
                    <th>#SL</th>
                    <th class="text-left">Warehouse </th>
                    <th class="text-left">Product Code</th>
                    <th class="text-left">Product Name</th>
                    <th width="60px">Quantity</th>
                    <th class="text-center" width="60px"> Unit Price</th>
                    <th class="text-center" width="80px"> Total Price</th>
                </tr>
            @endif
            </thead>
            <tbody>
            @php
                $warehouse = "";
                $qty = $ware_total_price = 0;
                $sl = $warehouse_id = 1;
            @endphp
            @forelse($stocks as $key=>$value)

                @if(!empty($warehouse) && $value->warehouse_id != $warehouse && $group_by == "yes")
                    <tr>
                        <th colspan="3" class="text-center" style="padding-right: 50px;"> Sub Total</th>
                        <th>{{ create_money_format(create_float_format($qty)) }}</th>
                        <th></th>
                        <th class="text-right"> {{ create_money_format(create_float_format($ware_total_price)) }}</th>

                    </tr>
                    @php
                        $qty = 0;
                        $ware_total_price = 0;
                        $sl = 1;
                    @endphp
                @endif
                @if($value->warehouse_id != $warehouse)
                    @if($group_by == "yes")
                        <tr style="border:1px solid #fff !important;">
                            <th colspan="6" class="text-left" style="border:0 !important; font-size:15px; padding: 10px 0 !important;">{{ sprintf("%02d", $warehouse_id++) }}. {{ $value->title }} </th>

                        </tr>
                        <tr>
                            <th>#SL</th>
                            <th class="text-left" >Product Code</th>
                            <th class="text-left">Product Name</th>
                            <th width="60px">Quantity</th>
                            <th class="text-center" width="60px"> Unit Price</th>
                            <th class="text-center" width="80px"> Total Price</th>
                        </tr>
                    @endif
                @endif


                @php
                    $purchase_price = $value->purchase_qty_price ?? $value->purchase_price;
                    $total_price = $value->stock*$purchase_price;
                    $total += $total_price;
                    $ware_total_price += $total_price;
                @endphp
                <tr>
                    <td>{{ $sl }}</td>
                    @if($group_by == "no")
                        <td class="text-left">{{ $value->title }}</td>
                    @endif
                    <td class="text-left">{{ $value->productCode }}</td>
                    <td class="text-left">{{ $value->item_name }}</td>
                    <td class="text-center">{{ create_float_format($value->stock, 2) }}</td>
                    <td class="text-center">{{ create_money_format(create_float_format($purchase_price)) }}</td>
                    <td class="text-right">{{ create_money_format(create_float_format($value->stock*$purchase_price)) }}</td>
                </tr>

                @php
                    $sl++;
                    $warehouse = $value->warehouse_id;
                    $qty += $value->stock;
                @endphp
            @empty
                <tr>
                    <td class="text-left" colspan="5">No Report available for Stock</td>
                </tr>
            @endforelse


            @if( ($qty>0 || $ware_total_price>0) && $group_by == "yes")

                <tr>
                    <th colspan="3" class="text-center" style="padding-right: 50px;"> Sub Total</th>
                    <th>{{ create_money_format(create_float_format($qty)) }}</th>
                    <th></th>
                    <th class="text-right">{{ create_money_format(create_float_format($ware_total_price)) }}</th>

                </tr>

            @endif
            @if( $stocks->sum('stock')>0 || $total>0)
                <tr>
                    <th colspan="{{ $group_by == "yes" ? 3 : 4 }}" class="text-center" style="font-size: 12px; padding-right: 50px;"> Total</th>
                    <th style="font-size: 12px; ">{{ create_money_format(create_float_format($stocks->sum('stock'))) }}</th>
                    <th></th>
                    <th class="text-right" style="font-size: 12px; ">{{ create_money_format(create_float_format($total)) }}</th>

                </tr>

            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>




