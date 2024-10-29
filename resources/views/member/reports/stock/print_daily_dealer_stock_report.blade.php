<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/29/2019
 * Time: 2:57 PM
 */
?>

@include('member.reports.print_head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style=" text-align: center!important;">
        <table cellspacing="0" style="width:100%;" class="table" >
            <tbody>
            <tr>
                <th>#SL</th>
                @if(Auth::user()->can(['super-admin','admin']) || Auth::user()->hasRole(['sales_man']))
                <th>Dealer Name</th>

                @endif
                <th width="250px" class="text-left">Product Name</th>
                <th>Opening Stock</th>
                <th>Purchase Qty</th>
                <th>Purchase Return Qty</th>
                <th>Sale Qty</th>
                <th>Sale Return Qty</th>
                <th>Closing Stock</th>
                <th>Unit</th>
                <th width="100px">Date</th>
            </tr>
            @foreach($stocks as $key=>$value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    @if(Auth::user()->can(['super-admin','admin']) || Auth::user()->hasRole(['sales_man']))
                    <td>{{ $value->dealer->user_phone }}</td>
                    @endif
                    <td class="text-left">{{ $value->product_name }}</td>
                    <td>{{ create_float_format($value->opening_stock)  }}</td>
                    <td>{{ create_float_format($value->purchase_qty)  }}</td>
                    <td>{{ create_float_format($value->purchase_return_qty)  }}</td>
                    <td>{{ create_float_format($value->sale_qty)  }}</td>
                    <td>{{ create_float_format($value->sale_return_qty)  }}</td>
                    <td>{{ create_float_format($value->opening_stock+$value->purchase_qty-$value->purchase_return_qty-$value->sale_qty+$value->sale_return_qty) }}</td>
                    <td>{{ $value->item->unit }}</td>
                    <td>{{ db_date_month_year_format($value->date) }}</td>
                </tr>
            @endforeach

            <?php
            if(count($stocks)==0){
            ?>

            <tr>
                <td class="text-left" colspan="8">No Report available for Stock</td>
            </tr>

            <?php } ?>

            </tbody>
        </table>
    </div>
</div>
</body>
</html>

