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

    <div style=" overflow: hidden; clear: both; float: inherit; text-align: center!important;">
        <table cellspacing="0" style="width:100%;" class="table" >
            <tbody>
            <tr>
                <th>#SL</th>
                <th>{{__('common.product_code')}}</th>
                <th>{{__('common.product_name')}}</th>
                <th>{{__('common.opening_stock')}}</th>
                <th>{{__('common.purchase_qty')}}</th>
                <th>{{__('common.purchase_return_qty')}}</th>
                <th>{{__('common.sale_qty')}}</th>
                <th>{{__('common.sale_return_qty')}}</th>
                <th>{{__('common.damage_qty')}}</th>
                <th>{{__('common.overflow_qty')}}</th>
                <th>{{__('common.closing_stock')}}</th>
                <th>{{__('common.unit')}}</th>
                <th> {{__('common.date')}} </th>
            </tr>
            @foreach($stocks as $key=>$value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->product_code }}</td>
                    <td>{{ $value->product_name }}</td>
                    <td>{{ create_float_format($value->opening_stock)  }}</td>
                    <td>{{ create_float_format($value->purchase_qty)  }}</td>
                    <td>{{ create_float_format($value->purchase_return_qty)  }}</td>
                    <td>{{ create_float_format($value->sale_qty)  }}</td>
                    <td>{{ create_float_format($value->sale_return_qty)  }}</td>
                    <td>{{ create_float_format($value->loss_qty)  }}</td>
                    <td>{{ create_float_format($value->stock_overflow_qty)  }}</td>
                    <td>{{ create_float_format($value->opening_stock+$value->purchase_qty-$value->purchase_return_qty-$value->sale_qty+$value->sale_return_qty-$value->loss_qty+$value->stock_overflow_qty) }}</td>
                    <td>{{ $value->item->unit }}</td>
                    <td>{{ db_date_month_year_format($value->date) }}</td>
                </tr>
            @endforeach

            <?php
            if(count($stocks)==0){
            ?>

            <tr>
                <td class="text-left" colspan="8">{{__('common.no_report_available_for_stock')}}</td>
            </tr>

            <?php } ?>

            </tbody>
        </table>
    </div>
</div>
</body>
</html>

