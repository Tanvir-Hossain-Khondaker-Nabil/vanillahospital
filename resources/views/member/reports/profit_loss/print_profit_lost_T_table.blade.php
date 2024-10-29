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

        <div class="col-lg-6">
            <table class="table table-striped dataTable">
                <tbody>
                <tr>
                    <td class="text-uppercase report-head-tag border-1 "> particulars</td>
                    <td class="text-uppercase report-head-tag text-right border-1 "> Taka</td>
                </tr>

                <tr>
                    <th class="text-uppercase" colspan="3">2. Less: Cost of Goods Sold</th>
                </tr>
                <tr>
                    <td class="padding-left-40" >A. Opening Stock</td>
                    <td class="text-right">{{ create_money_format($openingStock) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" >B. Purchase</td>
                    <td class="text-right">{{ create_money_format($total_purchases) }}</td>
                </tr>
                <tr>
                    <th class="padding-left-40 text-uppercase " >Total(A+B)</th>
                    <th class=" single-line text-right">{{ create_money_format($openingStock+$total_purchases) }}</th>
                </tr>
                <tr>
                    <td class="padding-left-40" >C. Closing Stock</td>
                    <td class="text-right">({{ create_money_format($total_inventory) }})</td>
                </tr>
                <tr>
                    <th class="padding-left-40 text-uppercase " >Total(A+B-C)</th>
                    <th class=" single-line text-right">{{ create_money_format($openingStock+$total_purchases-$total_inventory) }}</th>
                </tr>
                @foreach($cost_of_sold_items as $key => $value)
                    <tr>
                        <td class="padding-left-40" >{{ $value['account_type_name'] }}</td>
                        <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th class="padding-left-40 text-uppercase" >Cost of Goods Sold</th>
                    <th class="single-line text-right">{{ create_money_format($total_cost_of_sold) }}</th>
                </tr>

                <tr>
                    <th class="text-uppercase" colspan="3">4. Less: Administrative and general Expenses</th>
                </tr>
                @foreach($expenses as $key => $value)
                    <tr>
                        <td class=" {{ isset($value['child']) && $value['child'] == 'yes' ? 'padding-left-70 ' : 'padding-left-40'  }} {{ isset($value['parent']) && $value['parent'] == 'yes' ? ' text-bold ' : '' }}" >{{ $value['account_type_name'] }}</td>
                        <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                    </tr>
                @endforeach

                <tr>
                    <th class=" padding-left-40 text-uppercase " >Total</th>
                    <th class=" single-line text-right">{{ create_money_format($total_expenses) }}</th>
                </tr>
                <tr>
                    <th class="border-top-1 text-uppercase ">Total</th>
                    <th class="border-top-1 text-right">{{ create_money_format($total_cost_of_sold+$total_expenses) }}</th>
                </tr>
                </tbody>
            </table>



        </div>

        <div class="col-lg-6">
            <table class="table table-striped dataTable">
                <tbody>
                <tr>
                    <td class="text-uppercase report-head-tag border-1 "> particulars</td>
                    <td class="text-uppercase report-head-tag text-right border-1 "> Taka</td>
                </tr>
                <tr>
                    <th class="text-uppercase" >1. Sales</th>
                    <th class="text-right">{{ create_money_format( $total_sales) }}</th>
                </tr>

                <tr>
                    <th class="text-uppercase" colspan="3">5. Income</th>
                </tr>
                @foreach($incomes as $key => $value)
                    <tr>
                        <td class=" {{ isset($value['child']) && $value['child'] == 'yes' ? 'padding-left-70 ' : 'padding-left-40'  }} {{ isset($value['parent']) && $value['parent'] == 'yes' ? ' text-bold ' : '' }}" >{{ $value['account_type_name'] }}</td>
                        <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                    </tr>
                @endforeach

                <tr>
                    <th class="padding-left-40 text-uppercase " >Total</th>
                    <th class=" single-line text-right">{{ create_money_format($total_incomes) }}</th>
                </tr>
                <tr>
                    <th class="text-uppercase  border-top-1"> Net {{ $net_profit<0? "Loss":"Profit" }} </th>
                    <th class=" border-top-1 text-right">{{ create_money_format( $profit = $net_profit<0 ? $net_profit*(-1) :$net_profit ) }}</th>
                </tr>

                <tr>
                    <th class="border-top-1 text-uppercase ">Total</th>
                    <th class="border-top-1 text-right">{{ create_money_format($total_sales+$profit+$total_incomes) }}</th>
                </tr>
                <tr>
                    <th class="text-uppercase  border-top-1"> Gross Profit (1-2)</th>
                    <th class=" border-top-1 text-right">{{ create_money_format($total_sales-$total_cost_of_sold) }}</th>
                </tr>
                </tbody>
            </table>



        </div>
    </div>
</div>
</body>
</html>


