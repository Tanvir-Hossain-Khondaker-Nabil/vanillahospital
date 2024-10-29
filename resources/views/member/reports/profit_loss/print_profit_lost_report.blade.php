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

        <div class="col-lg-12">
            <table class="table table-striped" id="dataTable">
                <tbody>
                    <tr>
                        <td class="text-uppercase report-head-tag border-1 "> particulars</td>
                        <td class="text-uppercase report-head-tag text-right  border-1 "> Previous Taka<br/>
                            {{ formatted_date_string($pre_toDate) }}</td>
                        <td class="text-uppercase report-head-tag text-right border-1 "> Taka<br/> {{ formatted_date_string($toDate) }} </td>
                    </tr>
                    <tr>
                        <th class="text-uppercase" >1. Sales(net)</th>
                        <th class="text-right">{{ create_money_format($pre_total_sales) }}</th>
                        <th class="text-right">{{ create_money_format($total_sales) }}</th>
                    </tr>
                    <tr>
                        <th class="text-uppercase" colspan="3">2. Less: Cost of Goods Sold</th>
                    </tr>
                    <tr>
                        <td class="padding-left-40" >A. Opening Stock</td>
                        <td class="text-right">{{ create_money_format($pre_openingStock) }}</td>
                        <td class="text-right">{{ create_money_format($openingStock) }}</td>
                    </tr>
                    <tr>
                        <td class="padding-left-40" >B. Purchase</td>
                        <td class="text-right">{{ create_money_format($pre_total_purchases) }}</td>
                        <td class="text-right">{{ create_money_format($total_purchases) }}</td>
                    </tr>
                    <tr>
                        <th class="padding-left-40 text-uppercase " >Total(A+B)</th>
                        <th class=" single-line text-right">{{ create_money_format($pre_openingStock+$pre_total_purchases) }}</th>
                        <th class=" single-line text-right">{{ create_money_format($openingStock+$total_purchases) }}</th>
                    </tr>
                    <tr>
                        <td class="padding-left-40" >C. Closing Stock</td>
                        <td class="text-right">({{ create_money_format($pre_total_inventory) }})</td>
                        <td class="text-right">({{ create_money_format($total_inventory) }})</td>
                    </tr>
                    <tr>
                        <th class="padding-left-40 text-uppercase " >Total(A+B-C)</th>
                        <th class=" single-line text-right">{{ create_money_format($pre_openingStock+$pre_total_purchases-$pre_total_inventory) }}</th>
                        <th class=" single-line text-right">{{ create_money_format($openingStock+$total_purchases-$total_inventory) }}</th>
                    </tr>
                    <tr>
                        <td class="padding-left-40" > Cost of Good Sold </td>
                        <td class="text-right">{{ create_money_format($pre_cost_of_sold_balance) }}</td>
                        <td class="text-right">{{ create_money_format($cost_of_sold_balance) }}</td>
                    </tr>

                    @foreach($cost_of_sold_items as $key => $value)
                        <tr>
                            <td class="padding-left-40" >{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ $value['pre_balance']<0 ? "(".create_money_format((-1)*$value['pre_balance']).")" : create_money_format($value['pre_balance'])   }}</td>
                            @php
                                $balance = $value['balance']-$value['pre_balance'];
                            @endphp

                            <td class="text-right">{{  $balance<0 ? "(".create_money_format((-1)*$balance).")" : create_money_format($balance) }}</td>
                        </tr>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="padding-left-40 text-uppercase" >Cost of Goods Sold</th>
                        <th class="single-line text-right">{{ create_money_format($pre_total_cost_of_sold) }}</th>
                        <th class="single-line text-right">{{ create_money_format($total_cost_of_sold) }}</th>
                    </tr>
                    <tr>
                        <th class="text-uppercase  border-top-1" >3. Gross Profit (1-2)</th>
                        <th class=" border-top-1 text-right">{{ create_money_format($pre_total_sales-$pre_total_cost_of_sold) }}</th>
                        <th class=" border-top-1 text-right">{{ create_money_format($total_sales-$total_cost_of_sold) }}</th>
                    </tr>
                    <tr>
                        <th class="text-uppercase" colspan="3">4. Less: Administrative and general Expenses</th>
                    </tr>
                    @foreach($expenses as $key => $value)
                        <tr>
                            <td class=" {{ isset($value['child']) && $value['child'] == 'yes' ? 'padding-left-70 ' : 'padding-left-40'  }} {{ isset($value['parent']) && $value['parent'] == 'yes' ? ' text-bold ' : '' }}" >{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ $value['pre_balance']<0 ? "(".create_money_format((-1)*$value['pre_balance']).")" : create_money_format($value['pre_balance'])   }}</td>
                            <td class="text-right">{{  $value['balance']<0 ? "(".create_money_format((-1)*$value['balance']).")" : create_money_format($value['balance']) }}</td>
                        </tr>
                        </tr>
                    @endforeach

                    <tr>
                        <th class="padding-left-40 text-uppercase " >Total</th>
                        <th class=" single-line text-right">{{ create_money_format($pre_total_expenses) }}</th>
                        <th class=" single-line text-right">{{ create_money_format($total_expenses) }}</th>
                    </tr>
                    <tr>
                        <th class="text-uppercase" colspan="3">5. Income</th>
                    </tr>
                    @foreach($incomes as $key => $value)
                        <tr>
                            <td class=" {{ isset($value['child']) && $value['child'] == 'yes' ? 'padding-left-70 ' : 'padding-left-40'  }} {{ isset($value['parent']) && $value['parent'] == 'yes' ? ' text-bold ' : '' }}" >{{ $value['account_type_name'] }}</td>
                            <td class="text-right">{{ $value['pre_balance']<0 ? "(".create_money_format((-1)*$value['pre_balance']).")" : create_money_format($value['pre_balance'])   }}</td>
                            <td class="text-right">{{  $value['balance']<0 ? "(".create_money_format((-1)*$value['balance']).")" : create_money_format($value['balance']) }}</td>
                        </tr>
                        </tr>
                    @endforeach

                    <tr>
                        <th class="padding-left-40 text-uppercase " >Total</th>
                        <th class=" single-line text-right">{{ create_money_format($pre_total_incomes) }}</th>
                        <th class=" single-line text-right">{{ create_money_format($total_incomes) }}</th>
                    </tr>

                    <tr>
                        <th class="text-uppercase  border-top-1"> Net {{ $net_profit<0? "Loss":"Profit" }} (3-4+5)</th>
                        <th class=" border-top-1 text-right">{{ $pre_net_profit<0 ? "(".create_money_format((-1)*$pre_net_profit).")" : create_money_format($pre_net_profit ) }}</th>
                        <th class=" border-top-1 text-right">{{  $net_profit<0 ? "(".create_money_format((-1)*$net_profit).")" : create_money_format($net_profit ) }}</th>
                    </tr>


                </tbody>
            </table>



        </div>
    </div>
</div>
</body>
</html>


