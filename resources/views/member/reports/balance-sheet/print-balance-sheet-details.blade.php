<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/11/2019
 * Time: 2:01 PM
 */


$balance_sheet_key = 1;
$total_fixed = $total_current = $total_cash_bank = $trade_debtor_no = $total_trade_debtor = 0;
$adv_deposit_no = $total_adv_deposits = $total_account_payables = $account_payable_no = 0;
$account_receivable_no = $total_account_receivables = $sundry_creditor_no = $total_sundry_creditors = 0;
$total_over_bank = $over_bank_no = $non_current_liability_no = $total_non_current_liability = 0;
$current_liability_no = $total_current_liability = $current_asset_no = $total_current_asset = 0;
$sale_no = $total_sales = $purchase_no = $total_purchases = 0;
?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>

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

        @media print {
            .no-print, .no-print * {
                display: none !important;
            }
        }

        * {
            margin: 0;
            padding: 0;
        }

        html {
            margin: 0px
        }

        body {
            font: 12px/1.4 Helvetica, Arial, sans-serif;
            margin: 0;
        }

        #page-wrap {
            width: 720px;
            margin: 0 auto;
        }

        table {
            display: table;
            border-collapse: collapse;
            border-spacing: 0;
            color: #0a0a0a !important;
            width: 100% !important;
        }

        a {
            color: #1d1d1d;
            text-decoration: none;
        }

        table tbody tr td, table tbody tr th, table thead tr th {
            border: 0.3px solid rgba(1, 1, 1, 0.74) !important;
            padding: 3px;
        !important;
        }

        .table tbody tr td, .table tbody tr th, .table thead tr th {
            border: 0px solid #fff !important;
            padding: 6px;
            text-align: left;
        }

        .text-center {
            text-align: center !important;
        }

        .no-border {
            border: 0px;
        }

        .single-line {
            text-decoration-style: initial;
            text-decoration-line: underline;
            text-decoration-skip-ink: none;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .padding-right-120 {
            padding-right: 120px !important;
        }

        .padding-right-50 {
            padding-right: 50px !important;
        }

        .padding-left-40 {
            padding-left: 40px !important;
        }
        .padding-left-70 {
            padding-left: 70px !important;
        }
        .text-bold{
            font-weight: bold;
        }

        .report-head-tag {
            font-size: 17px;
            font-weight: bold;
            letter-spacing: 5px;
        }

        .width-100 {
            width: 100px;
        }

        .balance_sheet_ul {
            list-style-type: none;
            width: 100%;
            margin: 0;
            padding: 0;
            clear: both;
        }

        .balance_sheet_ul li {
            float: left;
        }

        .balance_sheet_ul li:first-child {
            padding: 6px;
            font-weight: bold;
            width: 4% !important;
        }

        .balance_sheet_ul li:last-child {
            width: 90% !important;
        }

        .border-1 {
            border: 1px solid rgba(0, 0, 0, 0.94) !important;
        }

        .border-right-1 {
            border-right: 1px solid #ccc;
        }

        .border-top-1 {
            border-top: 1px solid rgba(0, 0, 0, 0.94) !important;
        }

        .border-dual {
            border-bottom-color: #0a0a0a;
            border-bottom-style: double;
            width: 200px;
        }

        .text-right {
            text-align: right !important;
        }

        #logo {
            text-align: right;
            width: 70px;
            height: 50px;
            overflow: hidden;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }


    </style>
</head>
<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="width: 100%;">
        <ul class="balance_sheet_ul">
            <li> {{ create_money_format( $inventory_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Inventories</th>
                        </tr>
                        @php
                            $last_unit = '';
                            $category = '';
                            $total_inventory = $total_per_category = 0;
                        @endphp
                        @foreach($inventories as $key=>$value)
                            @php
                                $qty = $value->opening_stock+$value->purchase_qty-$value->purchase_return_qty-$value->sale_qty+$value->sale_return_qty;
                            @endphp

                            @if( !empty($category) && $category != $value->item->category->display_name)
                                <tr>
                                    <td colspan="2">Total {{ $category }}</td>
                                    <td class="single-line text-right"> {{ create_money_format($total_per_category) }} </td>
                                </tr>
                            @endif
                            @if( $key == 0 || $category != $value->item->category->display_name)
                                @php
                                    $total_per_category = 0;
                                @endphp
                                <tr>
                                    <th>{{ $value->item->category->display_name }}</th>
                                    <td class="text-right single-line"> Quantity (in {{ $value->item->unit }})</td>
                                </tr>
                            @endif

                            <tr>
                                <td>{{ $value->product_name }}</td>
                                <td class="text-right padding-right-50">{{ $qty }}</td>
                                <td class="text-right">{{ create_money_format($qty*$value->item_price) }}</td>
                            </tr>

                            @php
                                $last_unit = $value->item->unit;
                                $category = $value->item->category->display_name;
                                $total_inventory += $qty*$value->item_price;
                                $total_per_category += $qty*$value->item_price;
                            @endphp

                            @if(count($inventories) == $key+1)
                                <tr>
                                    <td colspan="2">Total {{ $value->item->category->display_name }}</td>
                                    <td class="single-line text-right"> {{ create_money_format($total_per_category) }} </td>
                                </tr>
                                <tr>
                                    <th colspan="2">Total Inventory</th>
                                    <th class="dual-line text-right"> {{ create_money_format($total_inventory) }} </th>
                                </tr>
                            @endif
                        @endforeach
                    </table>

                </div>
            </li>
        </ul>
        <br/>
        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($fixed_asset_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Fixed Assets</th>
                        </tr>
                        <tr>
                            <td colspan="2">Fixed Assets</td>
                            <td class="text-right">{{ create_money_format( $fixed_asset ? $fixed_asset->balance : 0) }}</td>
                        </tr>

                        @php
                            $total_fixed += $fixed_asset ? $fixed_asset->balance : 0;
                        @endphp
                        @foreach($fixed_assets as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value['account_type_name'] }}</td>
                                <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                            </tr>
                            @php
                                $total_fixed += $value['balance'];
                            @endphp
                        @endforeach
                        <tr>
                            <th colspan="2">Total Fixed Assets</th>
                            <th class="dual-line text-right">{{ ($total_fixed < 0 ? "(" : '').create_money_format( $total_fixed).($total_fixed < 0 ? ")" : '') }}</th>
                        </tr>
                    </table>

                </div>
            </li>
        </ul>
        <br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($current_asset_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Current Assets</th>
                        </tr>
                        <tr>
                            <td colspan="2">Current Assets</td>
                            <td class="text-right">{{ create_money_format( $current_asset_balance) }}</td>
                        </tr>

                        @php
                            $total_current_asset += $current_liability_balance;
                        @endphp
                        @foreach($current_assets as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value['account_type_name'] }}</td>
                                <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                            </tr>
                            @php
                                $total_current_asset += $value['balance'];
                            @endphp
                        @endforeach
                        <tr>
                            <th colspan="2">Total Current Assets</th>
                            <th class="dual-line text-right">{{ ($total_current_asset < 0 ? "(" : '').create_money_format( $total_current_asset).($total_current_asset < 0 ? ")" : '') }}</th>
                        </tr>
                    </table>

                </div>
            </li>
        </ul>
        <br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($cash_bank_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Cash & Bank</th>
                        </tr>
                        @foreach($current_assets as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value['account_type_name'] }}</td>
                                <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                            </tr>
                            @php
                                $total_cash_bank += $value['balance'];
                            @endphp
                        @endforeach
                        @foreach($cash_banks as $key=>$value)
                            @if($value['balance'] > 0)
                                <tr>
                                    <td colspan="2">{{ $value['account_type_name'] }}</td>
                                    <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                                </tr>
                                @php
                                    $total_cash_bank += $value['balance'];
                                @endphp
                            @endif
                        @endforeach
                        <tr>
                            <th colspan="2">Total Cash & Bank</th>
                            <th class="dual-line text-right">{{ ($total_cash_bank < 0 ? "(" : '').create_money_format( $total_cash_bank).($total_cash_bank < 0 ? ")" : '') }}</th>
                        </tr>
                    </table>
                </div>
            </li>
        </ul>
        <br/>


        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($trade_debtors_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Trade Debtors</th>
                        </tr>
                        @foreach($trade_debtors as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value['account_type_name'] }}</td>
                                <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                            </tr>
                            @php
                                $total_trade_debtor += $value['balance'];
                            @endphp
                        @endforeach
                        <tr>
                            <th colspan="2">Total Trade Debtor</th>
                            <th class="dual-line text-right">{{ ($total_trade_debtor < 0 ? "(" : '').create_money_format( $total_trade_debtor).($total_cash_bank < 0 ? ")" : '') }}</th>
                        </tr>
                    </table>
                </div>
            </li>
        </ul>
        <br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($account_receivable_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Account Receivable</th>
                        </tr>
                        @foreach($account_receivables as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value['account_type_name'] }}</td>
                                <td class="text-right">{{ create_money_format( $value['balance'] ) }}</td>
                            </tr>
                            @php
                                $total_account_receivables += $value['balance'];
                            @endphp
                        @endforeach
                        <tr>
                            <th colspan="2">Total Account Receivable</th>
                            <th class="dual-line text-right">{{ create_money_format( $total_account_receivables) }}</th>
                        </tr>
                    </table>
                </div>
            </li>
        </ul>
        <br/>
        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($account_payable_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Account Payable</th>
                        </tr>
                        @foreach($account_payables as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value['account_type_name'] }}</td>
                                <td class="text-right">{{ create_money_format( $value['balance']*(-1) ) }}</td>
                            </tr>
                            @php
                                $total_account_payables += $value['balance']*(-1);
                            @endphp
                        @endforeach
                        <tr>
                            <th colspan="2">Total Account Payable</th>
                            <th class="dual-line text-right">{{ create_money_format( $total_account_payables) }}</th>
                        </tr>
                    </table>
                </div>
            </li>
        </ul>
        <br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($sundry_creditor_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Sundry Creditors</th>
                        </tr>
                        @foreach($sundry_creditors as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value['account_type_name'] }}</td>
                                <td class="text-right">{{ create_money_format( $value['balance']*(-1) ) }}</td>
                            </tr>
                            @php
                                $total_sundry_creditors += $value['balance']*(-1);
                            @endphp
                        @endforeach
                        <tr>
                            <th colspan="2">Total</th>
                            <th class="dual-line text-right">{{ create_money_format( $total_sundry_creditors) }}</th>
                        </tr>
                    </table>
                </div>
            </li>
        </ul>
        <br/>
        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($over_bank_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Bank Overdraft</th>
                        </tr>
                        @foreach($cash_banks as $key=>$value)
                            @if($value['balance'] < 0)
                                <tr>
                                    <td colspan="2">{{ $value['account_type_name'] }}</td>
                                    <td class="text-right">{{ create_money_format( $value['balance']*(-1) ) }}</td>
                                </tr>
                                @php
                                    $total_over_bank += (-1)*$value['balance'];
                                @endphp
                            @endif
                        @endforeach
                        <tr>
                            <th colspan="2">Total Bank Overdraft</th>
                            <th class="dual-line text-right">{{ create_money_format( $total_over_bank) }}</th>
                        </tr>
                    </table>
                </div>
            </li>
        </ul>
        <br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($non_current_liability_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Non-Current Liabilities</th>
                        </tr>
                        <tr>
                            <td colspan="2">Long Term Liabilities</td>
                            <td class="text-right">{{ create_money_format( $non_current_liability_balance) }}</td>
                        </tr>

                        @php
                            $total_non_current_liability += $non_current_liability_balance;
                        @endphp
                        @foreach($non_current_liabilities as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value['account_type_name'] }}</td>
                                <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                            </tr>
                            @php
                                $total_non_current_liability += $value['balance'];
                            @endphp
                        @endforeach
                        <tr>
                            <th colspan="2">Total Non-Current Liabilities</th>
                            <th class="dual-line text-right">{{ ($total_non_current_liability < 0 ? "(" : '').create_money_format( $total_non_current_liability).($total_non_current_liability < 0 ? ")" : '') }}</th>
                        </tr>
                    </table>

                </div>
            </li>
        </ul>
        <br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($current_liability_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Current Liabilities</th>
                        </tr>
                        <tr>
                            <td colspan="2">Current Liabilities</td>
                            <td class="text-right">{{ create_money_format( $current_liability_balance) }}</td>
                        </tr>

                        @php
                            $total_current_liability += $current_liability_balance;
                        @endphp
                        @foreach($current_liabilities as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value['account_type_name'] }}</td>
                                <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                            </tr>
                            @php
                                $total_current_liability += $value['balance'];
                            @endphp
                        @endforeach
                        <tr>
                            <th colspan="2">Total Current Liabilities</th>
                            <th class="dual-line text-right">{{ ($total_current_liability < 0 ? "(" : '').create_money_format( $total_current_liability).($total_current_liability < 0 ? ")" : '') }}</th>
                        </tr>
                    </table>

                </div>
            </li>
        </ul>
        <br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($sale_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Sales</th>
                        </tr>

                        @foreach($sale_details as $key=>$value)
                            <tr>
                                <td colspan="1">{{ $value->item->item_name }}</td>
                                <td colspan="1" class="text-right">{{ $value->total_qty." ".$value->unit }}</td>
                                <td class="text-right">{{ create_money_format($value->sum_total_price) }}</td>
                            </tr>
                            @php
                                $total_sales += $value->sum_total_price;
                            @endphp
                        @endforeach
                        <tr>
                            <th colspan="2">Total Sales</th>
                            <th class="dual-line text-right">{{ create_money_format( $total_sales) }}</th>
                        </tr>
                    </table>

                </div>
            </li>
        </ul>
        <br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($purchase_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Purchases</th>
                        </tr>

                        @foreach($purchase_details as $key=>$value)
                            <tr>
                                <td colspan="1">{{ $value->item->item_name }}</td>
                                <td colspan="1" class="text-right">{{ $value->total_qty." ".$value->unit }}</td>
                                <td class="text-right">{{ create_money_format($value->sum_total_price) }}</td>
                            </tr>
                            @php
                                $total_purchases += $value->sum_total_price;
                            @endphp
                        @endforeach
                        <tr>
                            <th colspan="2">Total Purchases</th>
                            <th class="dual-line text-right">{{ create_money_format( $total_purchases) }}</th>
                        </tr>
                    </table>

                </div>
            </li>
        </ul>
        <br/>
        <P style="page-break-before: always"></P>


        <div class="col-lg-12">
            <table class="table table-striped" id="dataTable">

                <thead>

                <tr>
                    <th colspan="2" style="border: none !important; padding-bottom: 20px;" class="text-center">
                        <h3 style="font-size: 25px;"> Profit And Loss A/C</h3>
                    </th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td class="text-uppercase report-head-tag border-1 "> particulars</td>
                    <td class="text-uppercase report-head-tag text-right border-1 "> Taka</td>
                </tr>
                <tr>
                    <th class="text-uppercase" >1. Sales(net)</th>
                    <th class="text-right">{{ create_money_format( $total_sales) }}</th>
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
                    <th class="text-uppercase  border-top-1" >3. Gross Profit (1-2)</th>
                    <th class=" border-top-1 text-right">{{ create_money_format($total_sales-$total_cost_of_sold) }}</th>
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
                    <th class="padding-left-40 text-uppercase " >Total</th>
                    <th class=" single-line text-right">{{ create_money_format($total_expenses) }}</th>
                </tr>
                <tr>
                    <th class="text-uppercase" colspan="3">5. Income</th>
                </tr>
                @foreach($incomes as $key => $value)
                    <tr>
                        <td  class=" {{ isset($value['child']) && $value['child'] == 'yes' ? 'padding-left-70 ' : 'padding-left-40'  }} {{ isset($value['parent']) && $value['parent'] == 'yes' ? ' text-bold ' : '' }}" >{{ $value['account_type_name'] }}</td>
                        <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                    </tr>
                @endforeach

                <tr>
                    <th class="padding-left-40 text-uppercase " >Total</th>
                    <th class=" single-line text-right">{{ create_money_format($total_incomes) }}</th>
                </tr>
                <tr>
                    <th class="text-uppercase  border-top-1"> Net {{ $net_profit<0? "Loss":"Profit" }} (3-4+5)</th>
                    <th class=" border-top-1 text-right">{{ create_money_format( $net_profit<0 ? $net_profit*(-1) :$net_profit ) }}</th>
                </tr>


                </tbody>
            </table>



        </div>
    </div>
</div>
</body>
</html>


