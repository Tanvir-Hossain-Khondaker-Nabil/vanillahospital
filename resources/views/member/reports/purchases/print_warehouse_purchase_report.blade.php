<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/22/2019
 * Time: 4:58 PM
 */
?>


@include('member.reports.print_head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="width: 100%; display: flex; flex-wrap: nowrap; overflow: hidden; clear: both;">
        <table class="table table-striped" id="dataTable">
            <tbody>
            <tr>
                <th>#SL</th>
                <th width="100px">Date</th>
                <th>Transaction Code</th>
                <th class="text-left">Product Name</th>
                <th width="70px">Warehouse</th>
                <th class="text-center">Qty</th>
                <th>Unit</th>
                <th class="text-right">Price</th>
                <th class="text-right">Total Price</th>
            </tr>
            @php
                $i=1;
                $last_date = 0;
                $purchase_total_price = $total_qty = 0;
            @endphp
            @foreach($purchases as $key => $value)
                @php
                    $warehouse = $value->warehouse($value->id, $value->item_id);
                @endphp

                @foreach($warehouse as $warehouseValue)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td width="100px">{{ db_date_month_year_format($value->purchases->date) }}</td>
                        <td class="text-left">{{ $value->purchases->transaction->transaction_code }}</td>
                        <td class="text-left">{{ $value->item->item_name }}</td>
                        <td width="70px">{{ $warehouseValue->warehouse->title }}</td>
                        <td>{{ $value->unit }}</td>
                        <td class="text-center">{{ $warehouseValue->qty }}</td>
                        <td class="text-right">{{ create_money_format($value->price) }}</td>
                        <td class="text-right">{{ create_money_format($warehouseValue->qty*$value->price) }}</td>
                    </tr>
                @endforeach

                @php
                    $purchase_total_price += $value->total_price;
                    $total_qty += $value->qty;
                @endphp
            @endforeach

            @if($purchase_total_price>0)
                <tr class=" margin-bottom-20">
                    <th colspan="{{ $item ? 5 : 7 }}" class="text-right">Total</th>
                    @if($item)<th colspan="2" class="text-right">{{ $total_qty." ".$value->unit }}</th>@endif
                    <th colspan="2" class="text-right">{{ create_money_format($purchase_total_price) }}</th>
                </tr>
                @php
                    $purchase_total_price = 0;
                @endphp
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

