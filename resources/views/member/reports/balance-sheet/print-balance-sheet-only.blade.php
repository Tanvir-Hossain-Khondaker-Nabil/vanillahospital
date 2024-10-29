<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/11/2019
 * Time: 2:01 PM
 */
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

        table#dataTable .border-1 {
            border: 1px solid #eee !important;
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
    @php
        $total_account_payables = $total_account_payables*(-1);
        $total_over_bank = $total_over_bank*(-1);
        $total_sundry_creditors = $total_sundry_creditors*(-1);

        $total_current_asset = $total_inventory+$total_trade_debtor+$total_cash_bank+$total_account_receivables+$total_current_asset;
        $total_assets = $total_fixed+$total_current_asset;
        $total_liabilities = $total_account_payables+$total_sundry_creditors+$total_over_bank+$total_current_liability;

    @endphp

    <div style="width: 100%;">

        <div class="col-lg-12">

            <table class="table table-striped" id="dataTable">
                <tbody>
                <tr>
                    <td class="text-uppercase report-head-tag border-1 "> particulars</td>
                    <td class="text-uppercase report-head-tag text-right border-1 "> Taka</td>
                </tr>
                <tr>
                    <th class="text-uppercase text-underline border-1" colspan="2"><u>Property AND Assets</u></th>
                </tr>
                <tr>
                    <th class="text-uppercase" colspan="2">1. Fixed Assets</th>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#fixed_assets">  Total Fixed </a></td>
                    <td class="text-right">{{ ($total_fixed < 0 ? "(" : '').create_money_format( $total_fixed).($total_fixed < 0 ? ")" : '') }}</td>
                </tr>
                <tr>
                    <th class="text-uppercase" colspan="2">2. Current Assets</th>
                </tr>
                <tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#inventory">Inventory</a></td>
                    <td class="text-right">{{ create_money_format($total_inventory) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#cash_and_banks">Cash & Bank</a></td>
                    <td class="text-right">{{ create_money_format($total_cash_bank) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40"> <a href="javascript:void(0)" data-toggle="modal" data-target="#trade_debtors">Trade Debtors</a></td>
                    <td class="text-right">{{ create_money_format($total_trade_debtor) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#account_receivable">Account Receivable</a></td>
                    <td class="text-right">{{ create_money_format($total_account_receivables) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#current_assets">Others Current Assets</a></td>
                    <td class="text-right">{{ create_money_format($total_current_asset) }}</td>
                </tr>
                <tr>
                    <th class="padding-left-40 text-uppercase border-top-1" colspan="1">Total Current Assets</th>
                    <th class="border-top-1 text-right">{{ create_money_format($total_current_asset) }} </th>
                </tr>
                <tr>
                    <th class="text-uppercase  border-top-1" colspan="1">Total Assets (1+2)</th>
                    <th class=" border-top-1 text-right">{{ create_money_format($total_assets) }}</th>
                </tr>

                <tr>
                    <th class="text-uppercase text-underline border-1" colspan="2" ><u>Equity and Liabilities</u></th>
                </tr>
                <tr>
                    <th class="text-uppercase" colspan="2">A. Proprietors' equity</th>
                </tr>
                <tr>
                    <th class="text-uppercase padding-left-40" colspan="1">1. equity</th>
                    <td class="text-right">{{ create_money_format($equity_balance) }}</td>
                </tr>

                @php
                    $e_sum = 1;
                    $total_equity = $equity_balance;
                @endphp
                @foreach($equities as $key => $value)
                    <tr>
                        <th class="padding-left-40" colspan="1">{{ $key+2 }}. {{ $value['account_type_name'] }}</th>
                        <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                    </tr>
                    @php
                        $e_sum = $e_sum."+".($key+2);
                        $total_equity += $value['balance'];
                    @endphp
                @endforeach
                @php
                    $total_equity += $net_profit;

                    $total_e_laibility = $total_equity+$total_non_current_liability+$total_liabilities;
                    $opening_balance = $total_assets-$total_e_laibility;
                @endphp
                <tr>
                    <th class="padding-left-40" colspan="1">Net Profit this year</th>
                    <td class="text-right">{{ create_money_format($net_profit) }}</td>
                </tr>
                <tr>
                    <th class="text-uppercase border-top-1" colspan="1">Total Equity ({{$e_sum}})</th>
                    <th class="border-top-1 text-right">{{ create_money_format($total_equity) }} </th>
                </tr>
                <tr>
                    <th class="text-uppercase" >B. <a href="javascript:void(0)" data-toggle="modal" data-target="#non_current_liabilities">Non-current Liabilities</a></th>
                    <th class="text-right">{{ create_money_format($total_non_current_liability) }} </th>
                </tr>

                <tr>
                    <th class="text-uppercase" colspan="1">C. current Liabilities</th>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#account_payable">Account Payable</a></td>
                    <td class="text-right">{{ create_money_format($total_account_payables) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#sundry_creditors">Sundry Creditors</a></td>
                    <td class="text-right">{{ create_money_format($total_sundry_creditors) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#overdraft_banks">Bank Overdraft</a></td>
                    <td class="text-right">{{ create_money_format($total_over_bank) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#current_liabilities">Other Liabilities</a></td>
                    <td class="text-right">{{ create_money_format($total_current_liability) }}</td>
                </tr>
                <tr>
                    <th class="text-uppercase border-top-1 padding-left-40" colspan="1">Total Current Liabilities</th>
                    <th class="border-top-1 text-right">{{ create_money_format($total_liabilities) }} </th>
                </tr>
                <tr>
                    <th class="text-uppercase border-top-1 padding-left-40" colspan="1">Diff. in Opening Balances</th>
                    <th class="border-top-1 text-right">{{ create_money_format($opening_balance) }} </th>
                </tr>
                <tr>
                    <th class="text-uppercase border-top-1" colspan="1"> Total Equity & Liabilities (A+B+C)</th>
                    <th class="border-top-1 text-right">{{ create_money_format($total_e_laibility+$opening_balance) }}</th>
                </tr>

                </tbody>
            </table>


        </div>
    </div>
</div>
</body>
</html>


