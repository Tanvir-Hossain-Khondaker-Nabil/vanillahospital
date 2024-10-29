<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 10/2/2019
 * Time: 3:34 PM
 */
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>

    <title>{!! $report_title !!} </title>
    <style type="text/css">
        * {
            margin: 0;
        }

        @page {
            /*size:8.5in 11in;*/
            /*margin-top: 2cm;*/
            /*margin-bottom: 2cm;*/
            /*margin-left: 2cm;*/
            /*margin-right: 2cm;*/
            margin: 20px 30px;
            size: letter;
        }

        html {
            margin: 0px;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 12px;
        }

        #page-wrap {
            width: 720px;
            margin: 0 auto;
        }

        .table {
            border: 0.3px solid rgba(1, 1, 1, 0.6) !important;
        }

        .table tbody tr th {
            padding: 2px;
            border: 0.3px solid rgba(1, 1, 1, 0.6) !important;
            text-align: center;
        }

        .pr-3 {
            padding-right: 15px;
        }

        .table tbody tr td {
            padding: 2px;
        }

        .table-border-padding {
            border: 0.3px solid rgba(1, 1, 1, 0.6) !important;
            padding: 3px !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-bold {
            font-weight: bold;
        }

        .report-head-tag {
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 5px;
            border: 1px solid rgba(1, 1, 1, 0.6);
        }

        .text-left {
            text-align: left !important;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .border-dual {
            border-bottom-color: #0a0a0a;
            border-bottom-style: double;
            width: 200px;
        }

        table#dataTable .border-1 {
            border: 1px solid rgba(1, 1, 1, 0.6) !important;
        }

        .border-1 {
            border: 1px solid rgba(1, 1, 1, 0.6) !important;
        }

        .dual-line {
            text-decoration-style: double;
            text-decoration-line: underline;
            text-decoration-skip-ink: none;
        }

        .pl-15 {
            padding-left: 15px !important;
        }

        .pl-30 {
            padding-left: 30px !important;
        }

        #logo {
            text-align: right;
            width: 70px;
            height: 50px;
            overflow: hidden;
        }

        @media print {
            .no-print, .no-print * {
                display: none !important;
            }
        }
    </style>
</head>

<body>

<div id="page-wrap">

    @include('member.reports.company')

    <div>
        <table class="table table-striped" id="dataTable">

            <tbody>
            <tr>
                <td rowspan="2" class="text-left text-uppercase report-head-tag border-1 "> particulars</td>
                <td colspan="2" class="text-uppercase report-head-tag text-right  border-1 pt-2"> Opening Balance</td>
                <td colspan="2" class="text-uppercase report-head-tag text-right  border-1 "> Transaction</td>
                <td rowspan="2" class="text-uppercase report-head-tag text-right  border-1 "> Closing Balance</td>
            </tr>
            <tr>
                <td class="text-uppercase report-head-tag text-right pr-3 border-1 "> Dr</td>
                <td class="text-uppercase report-head-tag text-right pr-3  border-1 "> CR</td>
                <td class="text-uppercase report-head-tag text-right pr-3 border-1 "> Dr</td>
                <td class="text-uppercase report-head-tag text-right pr-3  border-1 "> CR</td>
            </tr>
            @if($search)

                @php
                    $total_opening = $pre_total_fixed;
                    $total_closing = $total_fixed;
                    $total_opening += $pre_total_cash_bank;
                    $total_closing += $total_cash_bank;
                    $total_opening += $pre_total_trade_debtor;
                    $total_closing += $total_trade_debtor;
                    $total_opening += $pre_total_account_receivables;
                    $total_closing += $total_account_receivables;
                    $total_opening += $pre_total_advance_prepayment;
                    $total_closing += $total_advance_prepayment;
                    $total_opening += $pre_total_due_affiliated_company;
                    $total_closing += $total_due_affiliated_company;
                    $total_opening += $pre_total_fixed_deposits_receipt;
                    $total_closing += $total_fixed_deposits_receipt;
                    $total_opening += $pre_total_equity;
                    $total_closing += $total_equity;
                    $total_opening += $pre_total_current_asset;
                    $total_closing += $total_current_asset;
                    $total_closing += $total_expenses;
                    $total_opening += $pre_total_purchases;
                    $total_closing += $total_purchases;
                    $total_opening += $pre_total_no_parent;
                    $total_closing += $total_no_parent;

                    $total_opening += $pre_total_sales;
                    $total_closing += $total_sales;
                    $total_closing -= $total_incomes;
                    $total_opening -= $pre_total_due_affiliated;
                    $total_closing -= $total_due_affiliated;
                    $total_opening -= $pre_total_income_tax_payable;
                    $total_closing -= $total_income_tax_payable;
                    $total_opening -= $pre_total_liabilities_expenses;
                    $total_closing -= $total_liabilities_expenses;
                    $total_opening -= $pre_total_current_liability;
                    $total_closing -= $total_current_liability;
                    $total_opening -= $pre_total_non_current_liability;
                    $total_closing -= $total_non_current_liability;
                    $total_opening -= $pre_total_account_payables;
                    $total_closing -= $total_account_payables;
                    $total_opening -= $pre_total_sundry_creditors;
                    $total_closing -= $total_sundry_creditors;
                    $total_opening -= $pre_total_over_bank;
                    $total_closing -= $total_over_bank;
                @endphp
                <tr>
                    <th class="text-left text-uppercase" colspan="6"> Fixed Assets</th>
                </tr>

                @php
                    $data['accounts'] = $fixed_assets;
                    $data['head_name'] = 'fixed assets';
                @endphp
                @include('member.reports.common._table_row', $data)

                <tr>
                    <td class="text-left   pl-15"> Total Fixed</td>

                    <td class="text-right text-bold"
                        colspan="2">{{ ($pre_total_fixed < 0 ? "(" : '').create_money_format_with_dr_cr( $pre_total_fixed).($pre_total_fixed < 0 ? ")" : '') }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $fixed_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $fixed_total_cr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr( $total_fixed) }}</td>
                </tr>

                <tr>
                    <th class="text-left text-uppercase" colspan="6"> Current Asstes</th>
                </tr>
                @php
                    $data['accounts'] = $cash_banks;
                    $data['head_name'] = 'cash and bank';
                @endphp
                @include('member.reports.common._table_row', $data)
                <tr>
                    <td class="text-left  pl-15"> Total Cash & Bank</td>

                    <td class="text-right text-bold"
                        colspan="2">{{ create_money_format_with_dr_cr($pre_total_cash_bank) }}</td>
                    <td class="text-right text-bold">{{ create_money_format($cash_banks_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format($cash_banks_total_cr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr($total_cash_bank) }}</td>
                </tr>

                @php
                    $data['accounts'] = $trade_debtors;
                    $data['head_name'] = 'Trade Debtors';
                @endphp
                @include('member.reports.common._table_row', $data)
                <tr>
                    <td class="text-left   pl-15">Total Trade Debtors</td>

                    <td class="text-right text-bold"
                        colspan="2">{{ create_money_format_with_dr_cr($pre_total_trade_debtor) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $trade_debtors_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $trade_debtors_total_cr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr($total_trade_debtor) }}</td>
                </tr>

                @php
                    $data['accounts'] = $account_receivables;
                    $data['head_name'] = 'Account Receivable';
                @endphp
                @include('member.reports.common._table_row', $data)
                <tr>
                    <td class="text-left">Total Account Receivable</td>

                    <td class="text-right text-bold"
                        colspan="2">{{ create_money_format_with_dr_cr($pre_total_account_receivables) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $account_receivables_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $account_receivables_total_cr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr($total_account_receivables) }}</td>
                </tr>


                @php
                    $data['accounts'] = $advance_prepayments;
                    $data['head_name'] = 'Advance Deposits & Prepayments';
                @endphp
                @include('member.reports.common._table_row', $data)
                <tr>
                    <td class="text-left">Total Advance Deposits & Prepayments</td>

                    <td class="text-right text-bold"
                        colspan="2">{{ create_money_format_with_dr_cr( $pre_total_advance_prepayment) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $advance_prepayments_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $advance_prepayments_total_cr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr( $total_advance_prepayment) }}</td>
                </tr>


                @php
                    $data['accounts'] = $due_companies;
                    $data['head_name'] = 'Due from Affiliated Company';
                @endphp
                @include('member.reports.common._table_row', $data)
                <tr>
                    <td class="text-left">Total Due from Affiliated Company</td>

                    <td class="text-right text-bold"
                        colspan="2">{{ create_money_format_with_dr_cr( $pre_total_due_affiliated_company) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $due_companies_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $due_companies_total_cr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr( $total_due_affiliated_company) }}</td>
                </tr>

                @php
                    $data['accounts'] = $fixed_deposits_receipts;
                    $data['head_name'] = 'Fixed Deposits Receipts';
                @endphp
                @include('member.reports.common._table_row', $data)
                <tr>
                    <td class="text-left">Total Fixed Deposits Receipts</td>

                    <td class="text-right text-bold"
                        colspan="2">{{ create_money_format_with_dr_cr( $pre_total_fixed_deposits_receipt) }}</td>

                    <td class="text-right text-bold">
                    {{ create_money_format( $fixed_deposits_receipts_total_dr) }}</th>
                    <td class="text-right text-bold">{{ create_money_format( $fixed_deposits_receipts_total_cr) }}</td>

                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr( $total_fixed_deposits_receipt) }}</td>
                </tr>

                @php
                    $data['accounts'] = $current_assets;
                    $data['head_name'] = 'Other Current Assets';
                @endphp
                @include('member.reports.common._table_row', $data)
                <tr>
                    <td class="text-left">Total Other Current Assets</td>

                    <td class="text-right text-bold"
                        colspan="2">{{ create_money_format_with_dr_cr( $pre_total_current_asset) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $current_assets_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $current_assets_total_cr) }}</td>

                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr( $total_current_asset) }}</td>
                </tr>

                <tr>
                    <th class="text-left text-uppercase" colspan="6"> Equity and Liabilities</th>
                </tr>


                @php
                    $data['accounts'] = $equities;
                    $data['head_name'] = 'Equity';
                @endphp
                @include('member.reports.common._table_row', $data)
                <tr>
                    <td class="text-left">Total Equity</td>
                    <td class="text-right text-bold"
                        colspan="2">{{ create_money_format_with_dr_cr( $pre_total_equity) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $equities_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $equities_total_cr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr( $total_equity) }}</td>
                </tr>

                <tr>
                    <th class="text-left text-uppercase" colspan="6"> Liabilities</th>
                </tr>


                @php
                    $data['accounts'] = $non_current_liabilities;
                    $data['head_name'] = 'Non-current Liabilities';
                @endphp
                @include('member.reports.common._table_row', $data)
                <tr>
                    <td class="text-left">Total Non-current Liabilities</td>

                    <td class="text-right text-bold"
                        colspan="2">{{ create_money_format_with_dr_cr($pre_total_non_current_liability*(-1)) }} </td>

                    <td class="text-right text-bold">{{ create_money_format( $non_current_liabilities_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $non_current_liabilities_total_cr) }}</td>

                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr($total_non_current_liability*(-1)) }} </td>
                </tr>


                @php
                    $data['accounts'] = $account_payables;
                    $data['head_name'] = 'Account Payable';
                @endphp
                @include('member.reports.common._table_row', $data)

                <tr>
                    <td class="text-left">Total Account Payable</td>

                    <td class="text-right text-bold"
                        colspan="2">{{ create_money_format_with_dr_cr($pre_total_account_payables*(-1)) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $account_payables_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $account_payables_total_cr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr($total_account_payables*(-1)) }}</td>
                </tr>


                @php
                    $data['accounts'] = $sundry_creditors;
                    $data['head_name'] = 'Sundry Creditors';
                @endphp
                @include('member.reports.common._table_row', $data)
                <tr>
                    <td class="text-left">Total Sundry Creditors</td>

                    <td class="text-right text-bold"
                        colspan="2">{{ create_money_format_with_dr_cr($pre_total_sundry_creditors*(-1)) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $sundry_creditors_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $sundry_creditors_total_cr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr($total_sundry_creditors*(-1)) }}</td>
                </tr>


                @php
                    $data['accounts'] = $over_banks;
                    $data['head_name'] = 'Bank Overdraft';
                @endphp
                @include('member.reports.common._table_row', $data)
                <tr>
                    <td class="text-left">Total Bank Overdraft</td>

                    <td class="text-right text-bold"
                        colspan="2">{{ create_money_format_with_dr_cr($pre_total_over_bank*(-1)) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $over_banks_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $over_banks_total_cr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr($total_over_bank*(-1)) }}</td>
                </tr>

                @php
                    $data['accounts'] = $current_liabilities;
                    $data['head_name'] = 'Other Current Liabilities';
                @endphp
                @include('member.reports.common._table_row', $data)
                <tr>
                    <td class="text-left">Total Other Current Liabilities</td>

                    <td class="text-right text-bold"
                        colspan="2">{{ create_money_format_with_dr_cr($pre_total_current_liability*(-1)) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $current_liabilities_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $current_liabilities_total_cr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr($total_current_liability*(-1)) }}</td>
                </tr>

                @php
                    $data['accounts'] = $liabilities_expenses;
                    $data['head_name'] = 'Liabilities For Expenses';
                @endphp
                @include('member.reports.common._table_row', $data)
                <tr>
                    <td class="text-left">Total Liabilities For Expenses</td>

                    <td class="text-right text-bold"
                        colspan="2">{{ create_money_format_with_dr_cr( $pre_total_liabilities_expenses*(-1)) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $liabilities_expenses_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $liabilities_expenses_total_cr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr( $total_liabilities_expenses*(-1)) }}</td>
                </tr>

                @php
                    $data['accounts'] = $income_tax_payables;
                    $data['head_name'] = 'Income tax payable';
                @endphp
                @include('member.reports.common._table_row', $data)
                <tr>
                    <td class="text-left"> Total Income tax payable</td>

                    <td class="text-right text-bold"
                        colspan="2">{{ create_money_format_with_dr_cr( $pre_total_income_tax_payable*(-1)) }}</td>

                    <td class="text-right text-bold">{{ create_money_format( $income_tax_payables_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $income_tax_payables_total_cr) }}</td>

                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr( $total_income_tax_payable*(-1)) }}</td>
                </tr>

                @php
                    $data['accounts'] = $due_to_affiliated_companies;
                    $data['head_name'] = 'Due to Affiliated Company';
                @endphp
                @include('member.reports.common._table_row', $data)
                <tr>
                    <td class="text-left">Total Due to Affiliated Company</td>

                    <td class="text-right text-bold"
                        colspan="2">{{ create_money_format_with_dr_cr( $pre_total_due_affiliated*(-1)) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $due_to_affiliated_companies_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $due_to_affiliated_companies_total_cr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr( $total_due_affiliated*(-1)) }}</td>
                </tr>

                <tr>
                    <th class="text-left text-uppercase" colspan="6"> Income and Expense</th>
                </tr>


                @php
                    $data['accounts'] = $incomes;
                    $data['head_name'] = 'Incomes';
                    $data['income_expense'] = true;
                @endphp
                @include('member.reports.common._table_row', $data)
                <tr>
                    <td class="text-left">Total Incomes</td>
                    <td class="text-right text-bold" colspan="2"></td>
                    <td class="text-right text-bold">{{ create_money_format( $income_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $income_total_cr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr( $total_incomes*(-1)) }}</td>
                </tr>


                @php
                    $data['accounts'] = $expenses;
                    $data['head_name'] = 'Expenses';
                    $data['income_expense'] = true;
                @endphp
                @include('member.reports.common._table_row', $data)
                <tr>
                    <td class="text-left">Total Expenses</td>
                    <td class="text-right text-bold" colspan="2"></td>
                    <td class="text-right text-bold">{{ create_money_format( $expense_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $expense_total_cr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr( $total_expenses) }}</td>
                </tr>

                <tr>
                    <th class="text-left text-uppercase" colspan="6"> Sale and Purchase</th>
                </tr>
                <tr>
                    <td class="text-left pl-15">Sales</td>
                    <td class="text-right text-bold"
                        colspan="2">{{ create_money_format_with_dr_cr( $pre_total_sales*(-1)) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $sales_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $sales_total_cr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr($total_sales*(-1)) }}</td>
                </tr>

                <tr>
                    <td class="text-left pl-15"> Purchases</td>
                    <td class="text-right text-bold"
                        colspan="2">{{ create_money_format_with_dr_cr( $pre_total_purchases) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $purchases_total_dr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format( $purchases_total_cr) }}</td>
                    <td class="text-right text-bold">{{ create_money_format_with_dr_cr( $total_purchases) }}</td>
                </tr>
                @if(count($no_parents)>0)

                    <tr>
                        <th class="text-left text-uppercase" colspan="6"> Undefined parent Ledger</th>
                    </tr>
                @endif

                @foreach($no_parents as $value)
                    <tr>
                        <td class="text-left pl-15">{{ $value['account_type_name'] }}</td>
                        @if($value['pre_balance']>0)
                            <td class="text-right {{ $value['pre_balance'] < 0 ? " font-italic" :'' }}">{{  create_money_format_with_dr_cr( $value['pre_balance'] ) }}</td>
                            <td></td>
                        @else
                            <td></td>
                            <td class="text-right {{ $value['pre_balance'] < 0 ? " font-italic" :'' }}">{{ create_money_format_with_dr_cr( $value['pre_balance'] ) }}</td>
                        @endif
                        <td class="text-right">{{ $value['total_dr']>0 ? create_money_format( $value['total_dr'] ) : "" }}</td>
                        <td class="text-right">{{ $value['total_cr']>0 ? create_money_format( $value['total_cr'] ) : "" }}</td>
                        <td class="text-right">{{ create_money_format_with_dr_cr( $value['balance'] ) }}</td>
                    </tr>
                @endforeach

                @if(count($no_parents)>0)
                    <tr>
                        <td class="text-left pl-15"> Total Undefined</td>
                        <td class="text-right text-bold"
                            colspan="2">{{ create_money_format_with_dr_cr( $pre_total_no_parent) }}</td>
                        <td class="text-right text-bold">{{ create_money_format( $no_parent_total_dr) }}</td>
                        <td class="text-right text-bold">{{ create_money_format( $no_parent_total_cr) }}</td>
                        <td class="text-right text-bold">{{ create_money_format_with_dr_cr( $total_no_parent) }}</td>
                    </tr>
                @endif
                <tr>
                    <th> Grand Total</th>
                    <th class="text-right" colspan="2">{{ create_money_format_with_dr_cr($total_opening) }}</th>
                    <th class="dual-line  text-right">{{ create_money_format($total_dr) }}</th>
                    <th class="dual-line  text-right">{{ create_money_format($total_cr) }}</th>
                    <th class=" text-right">{{ create_money_format_with_dr_cr($total_closing) }}</th>
                </tr>
            @endif

            </tbody>
        </table>


    </div>
</div>
</body>
</html>

