<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/11/2019
 * Time: 2:01 PM
 */


$balance_sheet_key = 1;
$sale_no = $purchase_no = $inventory_no = 0;
?>

@include('member.reports.common.head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="width: 100%;">


        <ul class="balance_sheet_ul">
            <li> {{ create_money_format( $inventory_no = $balance_sheet_key++) }} Inventory</li>
            <li>
                <div class="col-md-12">

                    <table class="table">

                        <tr>
                            <th>Product name </th>
                            <th class="text-center "> Quantity X Price</th>
                            <th class="text-right"> Total Price</th>
                        </tr>
                        @php
                            $category = '';
                        @endphp
                        @foreach($inventories as $key=>$value)

                            @if($value->pre_qty != 0)
                                <tr>
                                    <td>{{ $value->product_name }}</td>
                                    <td class="text-center ">{{ $value->pre_qty}}{{ $value->unit }} X {{$value->pre_item_price }}</td>
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

                            @if($value->qty != 0)
                                <tr>
                                    <td>{{ $value->product_name }}</td>
                                    <td class="text-center ">{{ $value->qty}}{{ $value->unit }} X {{$value->item_price }}</td>
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
            </li>
        </ul>

        <br/><br/>
        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($sale_no = $balance_sheet_key++) }} Sales</li>
            <li>
                <div class="col-md-12">
                    <table class="table">


                        @foreach($sale_details as $key=>$value)
                            <tr>
                                <td colspan="1">{{ $value->item->item_name }}</td>
                                <td colspan="1" class="text-right">{{ $value->total_qty." ".$value->unit }}</td>
                                <td class="text-right">{{ create_money_format($value->sum_total_price) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="2">Total Sales</th>
                            <th class="dual-line text-right">{{ create_money_format( $total_sales) }}</th>
                        </tr>
                    </table>

                </div>
            </li>
        </ul>

        <br/><br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($purchase_no = $balance_sheet_key++) }} Purchases</li>
            <li>
                <div class="col-md-12">
                    <table class="table">

                        @foreach($purchase_details as $key=>$value)
                            <tr>
                                <td colspan="1">{{ $value->item->item_name }}</td>
                                <td colspan="1" class="text-right">{{ $value->total_qty." ".$value->unit }}</td>
                                <td class="text-right">{{ create_money_format($value->sum_total_price) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="2">Total Purchases</th>
                            <th class="dual-line text-right">{{ create_money_format( $total_purchases_details) }}</th>
                        </tr>
                        <tr>
                            <th colspan="2">Total Purchases Cost(Bank Charge+unload+Transport)</th>
                            <th class="dual-line text-right">{{ create_money_format( $total_purchases_bank_charge) }}</th>
                        </tr>
                        <tr>
                            <th colspan="2">Total</th>
                            <th class="dual-line text-right">{{ create_money_format( $total_purchases) }}</th>
                        </tr>
                    </table>

                </div>
            </li>
        </ul><br/><br/>


        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($sale_return_no = $balance_sheet_key++) }} Sales Return</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        @php
                            $return = 0;
                        @endphp
                        @foreach($sale_return_details as $key=>$value)
                            @php
                                $returnTotal =$value->total_qty*$value->sum_total_price;
                                $return +=$returnTotal;
                            @endphp
                            <tr>
                                <td colspan="1">{{ $value->item->item_name }}</td>
                                <td colspan="1" class="text-right">{{ $value->total_qty." ".$value->unit." X ".$value->sum_total_price }}</td>
                                <td class="text-right">{{ create_money_format($returnTotal) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="2">Total Sales Return</th>
                            <th class="dual-line text-right">{{ create_money_format( $total_sales_return) }}</th>
                        </tr>

                    </table>

                </div>
            </li>
        </ul>

        <br/><br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($purchase_return_no = $balance_sheet_key++) }} Purchases Return</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        @php
                            $return = 0;
                        @endphp
                        @foreach($purchase_return_details as $key=>$value)
                            @php
                                $returnTotal =$value->total_qty*$value->sum_total_price;
                                $return +=$returnTotal;
                            @endphp
                            <tr>
                                <td colspan="1">{{ $value->item->item_name }}</td>
                                <td colspan="1" class="text-right">{{ $value->total_qty." ".$value->unit." X ".$value->sum_total_price }}</td>
                                <td class="text-right">{{ create_money_format($returnTotal) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="2">Total Purchases Return</th>
                            <th class="dual-line text-right">{{ create_money_format( $total_purchases_return) }}</th>
                        </tr>
                    </table>

                </div>
            </li>
        </ul><br/><br/>
        <P style="page-break-before: always"></P>

    </div>
</div>
</body>
</html>


