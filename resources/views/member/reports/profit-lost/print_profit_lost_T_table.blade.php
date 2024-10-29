<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/11/2019
 * Time: 2:01 PM
 */
?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

    <title>{{ $report_title }}</title>
    <style>
        @page {
            /*size:8.5in 11in;*/
            /*margin-top: 2cm;*/
            /*margin-bottom: 2cm;*/
            /*margin-left: 2cm;*/
            /*margin-right: 2cm;*/

            margin: 40px 30px;
            size: letter;
        }

        @media print
        {
            .no-print, .no-print *
            {
                display: none !important;
            }
        }

        * { margin: 0; padding: 0; }
        html{margin:0px}
        body {
            font: 12px/1.4 Helvetica, Arial, sans-serif; margin: 0;
        }

        #page-wrap { width: 720px; margin: 0 auto; }

        table {
            display: table;
            border-collapse: collapse;
            border-spacing: 0;
            color: #0a0a0a !important;
            width: 100% !important;
        }

        table tbody tr td,  table tbody tr th, table thead tr th {
            border: 0.3px solid rgba(1, 1, 1, 0.74) !important; padding: 3px; !important;}

        .table tbody tr td,  .table tbody tr th, .table thead tr th {
            border: 0px solid #fff !important;
            padding: 6px;
            text-align: left;
        }
        .text-center{
            text-align: center !important;
        }
        .no-border{
            border: 0px;
        }
        .single-line{
            text-decoration-style: initial;
            text-decoration-line: underline;
            text-decoration-skip-ink: none;
        }
        .text-uppercase{
            text-transform: uppercase;
        }
        .padding-right-120{
            padding-right: 120px !important;
        }
        .padding-right-50{
            padding-right: 50px !important;
        }
        .padding-left-40{
            padding-left: 40px !important;
        }
        .padding-left-70 {
            padding-left: 70px !important;
        }
        .text-bold{
            font-weight: bold;
        }
        .report-head-tag{
            font-size: 17px;
            font-weight: bold;
            letter-spacing: 5px;
        }
        .width-100{
            width: 100px;
        }

        .balance_sheet_ul{
            list-style-type: none;
            width: 100%;
            margin: 0;
            padding: 0;
            clear: both;
        }

        .balance_sheet_ul li{
            float: left;
        }
        .balance_sheet_ul li:first-child{
            padding: 6px;
            font-weight: bold;
            width: 4% !important;
        }
        .balance_sheet_ul li:last-child{
            width: 90% !important;
        }

        .border-1 {
            border: 1px solid rgba(0, 0, 0, 0.94) !important;
        }
        .border-right-1 {
            border-right: 1px solid #ccc;
        }
        .border-top-1{
            border-top: 1px solid rgba(0, 0, 0, 0.94) !important;
        }

        .border-dual {
            border-bottom-color: #0a0a0a;
            border-bottom-style: double;
            width: 200px;
        }
        .text-right{
            text-align: right !important;
        }

        #logo { text-align: right; width: 70px; height: 50px; overflow: hidden; }
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }

        .col-lg-6:first-child{
            border-right: 1px solid #eee;
            margin-right: 13px;

        }
        .col-lg-6{
            float: left;
            width: 49%;
        }
        table.dataTable .border-1 {
            border: 1px solid #eee !important;
        }
        table.dataTable .border-top-1{
            border-top: 1px solid #eee !important;
        }

    </style>
</head>
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

                @php
                    $total_cost_of_sold = $openingStock+$total_purchases-$total_inventory+$cost_of_sold_balance;
                @endphp
                @foreach($cost_of_sold_items as $key => $value)
                    <tr>
                        <td class="padding-left-40" >{{ $value['account_type_name'] }}</td>
                        <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                    </tr>
                    @php
                        $total_cost_of_sold += $value['balance'];
                    @endphp
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


