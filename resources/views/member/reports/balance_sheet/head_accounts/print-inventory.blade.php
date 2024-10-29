<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/11/2019
 * Time: 2:01 PM
 */

?>

@include('member.reports.common.head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="width: 100%;">

        <div class="col-md-12">
            <table class="" style="width: 100%;">

                <tr>
                    <th> Product Name</th>
                    <th class="text-center single-line"> Quantity X Price </th>
                    <th class="text-center"> Total Price </th>
                </tr>

                @foreach($inventories as $key=>$value)
                    @if($value->pre_qty>0)
                        <tr>
                            <td>{{ $value->product_name }}</td>
                            <td class="text-center ">{{ $value->pre_qty }}{{ $value->unit }} X {{ create_money_format($value->pre_item_price) }}</td>
                            <td class="text-right">{{ create_money_format($value->pre_qty*$value->pre_item_price) }}</td>
                        </tr>
                    @endif
                @endforeach

                @if($pre_total_inventory>0)
                    <tr>
                        <th colspan="2">Total Opening Stock</th>
                        <th class="dual-line text-right"> {{ create_money_format($pre_total_inventory) }} </th>
                    </tr>
                @endif
                <tr>
                    <td colspan="3" class="border-top-1" height="30px"></td>
                </tr>

                @foreach($inventories as $key=>$value)

                    @if($value->qty>0)
                        <tr>
                            <td>{{ $value->product_name }}</td>
                            <td class="text-center ">{{ $value->qty }}{{ $value->unit }} X {{ create_money_format($value->item_price) }}</td>
                            <td class="text-right">{{ create_money_format($value->qty*$value->item_price) }}</td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <th colspan="2">Total Inventory</th>
                    <th class="dual-line text-right"> {{ create_money_format($total_inventory) }} </th>
                </tr>
            </table>

        </div>
    </div>
</div>
</body>
</html>



