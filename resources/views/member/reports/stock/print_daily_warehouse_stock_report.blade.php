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
                <th class="text-left"> Warehouse </th>
                {{--<th class="text-left">Product Code</th>--}}
                <th class="text-left">Product Name</th>
                <th>Opening Stock</th>
                <th>Load Qty</th>
                <th>Unload Qty</th>
                <th>Transfer Qty</th>
                <th>Damage Qty</th>
                <th>Gain Qty</th>
                <th>Closing Stock</th>
                <th>Unit</th>
                <th> Date </th>
            </tr>
            @foreach($stocks as $key=>$value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td class="text-left">{{ $value->warehouse->title }}</td>
                    {{--<td class="text-left">{{ $value->item->productCode }}</td>--}}
                    <td class="text-left">{{ $value->item->item_name }}</td>
                    <td>{{ create_float_format($value->opening_stock) }}</td>
                    <td>{{ create_float_format($value->load_qty)  }}</td>
                    <td>{{ create_float_format($value->unload_qty)  }}</td>
                    <td>{{ create_float_format($value->transfer_qty)  }}</td>
                    <td>{{ create_float_format($value->damage_qty)  }}</td>
                    <td>{{ create_float_format($value->overflow_qty)  }}</td>
                    <td>{{ create_float_format($value->closing_stock)  }}</td>
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

