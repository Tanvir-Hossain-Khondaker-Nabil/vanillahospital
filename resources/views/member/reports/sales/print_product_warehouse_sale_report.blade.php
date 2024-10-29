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
            <tbody>
            <tr>
                <th>#SL</th>
                <th>Date</th>
                <th>Customer</th>
                <th class="text-left">Sale Code</th>
                <th>Warehouse</th>
                <th class="text-left">Product Name</th>
                <th>Unit</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Total Price</th>
            </tr>
            @php
                $last_date = $i =0;
                $sale_total_price = $total_qty = 0;
            @endphp
            @foreach($sales as $key => $value)
                @php
                    $warehouse = $value->warehouse($value->id, $value->item_id);
                @endphp

                @foreach($warehouse as $warehouseValue)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ db_date_month_year_format($value->sale->date) }}</td>
                        <td class="text-left">{{ $value->sale->due > 0 ? $value->sale->customer ? $value->sale->customer->name : "" : "Cash" }}</td>
                        <td class="text-left">{{ $value->sale->sale_code }}</td>
                        <td class="text-left">{{ $value->item->item_name }}</td>
                        <td>{{ $warehouseValue->warehouse->title }}</td>
                        <td class="text-center">{{ $warehouseValue->qty }}</td>
                        <td>{{ $value->unit }}</td>
                        <td class="text-right">{{ create_money_format($value->price) }}</td>
                        <td class="text-right">{{ create_money_format($warehouseValue->qty*$value->price) }}</td>
                    </tr>
                @endforeach

                @php
                    $last_date = db_date_month_year_format($value->sale->date);
                    $sale_total_price += $value->total_price;
                    $total_qty += $value->qty;
                @endphp

                @if( $key+1 == count($sales))
                    <tr class=" margin-bottom-20">
                        <th colspan="{{ $item ? 6 : 8 }}" class="text-right">Total</th>
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

