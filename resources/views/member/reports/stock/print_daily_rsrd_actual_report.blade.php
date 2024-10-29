<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/29/2019
 * Time: 2:57 PM
 */

$total_damage = 0;
$total_damage_price = 0;
$total = 0;
$i = 1;
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
                <th width="250px;">Product Name</th>
                <th class="text-center">Unit</th>
                <th class="text-center">Requisition Qty</th>
                <th class="text-center">Sale</th>
                <th class="text-center">Req. Return </th>
                <th class="text-center">Return </th>
                <th class="text-center">Damage</th>
                {{--<th class="text-center">Closing Stock</th>--}}
                <th class="text-right">Qty/Price</th>
                <th class="text-right">Total Price</th>
                <th class="text-center">Date </th>
            </tr>
            @foreach($stocks as $key=>$value)
                @php

                    $req_return_qty = $value['sales_requisition_qty']-$value['sale_qty'];
                    $req_return_qty = $req_return_qty < 0 ? 0 : $req_return_qty;

                    $return_qty = $value['sale_return_qty'];
                    $close_qty = $return_qty>0? $return_qty-$value['damage_qty']: 0;

                    $total_damage += $value['damage_qty'];
                    $total_damage_price += $value['damage_qty']*$value['price'];
                    $sale_total_price = ($value['sale_qty']*$value['price']);
                    $total += ($value['sale_qty']*$value['price']);
                    $date = $value['date'];


                @endphp
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>{{ $value['item_name'] }}</td>
                    <td class="text-center">{{ $value['unit'] }}</td>
                    <td class="text-center">{{ create_float_format($value['sales_requisition_qty'])  }}</td>
                    <td class="text-center">{{ create_float_format($value['sale_qty'])  }}</td>
                    <td class="text-center">{{ create_float_format($req_return_qty)  }}</td>
                    <td class="text-center">{{ create_float_format($return_qty)  }}</td>
                    <td class="text-center">{{ create_float_format($value['damage_qty'])  }}</td>
                    {{--<td class="text-center">{{ create_float_format($close_qty) }}</td>--}}
                    <td class="text-right">{{ create_money_format($value['price']) }}</td>
                    <td class="text-right">{{ create_money_format($sale_total_price) }}</td>
                    <td class="text-center">{{ db_date_month_year_format($date) }}</td>
                </tr>
            @endforeach

            <?php
            if(count($stocks)==0){
            ?>

            <tr>
                <td class="text-left" colspan="11">No Report Data Available</td>
            </tr>

            <?php }else{
                ?>
            <tr>
                <th class="text-right padding-right-120" colspan="9"> Total</th>
                <th class="text-right">{{ create_money_format($total) }}</th>
                <td class="text-center"></td>
            </tr>
            <tr>
                <th class="text-right padding-right-120" colspan="9"> Commission</th>
                <td class="text-right">{{ create_money_format($sales_commission) }}</td>
                <td class="text-center"></td>
            </tr>
            <tr>
                <th class="text-right padding-right-120" colspan="9"> Damage</th>
                <th class="text-right">{{ create_money_format($total_damage_price) }}</th>
                <td class="text-center"></td>
            </tr>
            <tr>
                <th class="text-right padding-right-120" colspan="9"> Grand Total</th>
                <td class="text-right">{{ create_money_format($total-$sales_commission-$total_damage_price) }}</td>
                <td class="text-center"></td>
            </tr>
            <?php
            } ?>

            </tbody>
        </table>
    </div>
</div>
</body>
</html>

