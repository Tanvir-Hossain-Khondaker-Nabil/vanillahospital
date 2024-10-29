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
                    <td class="text-uppercase report-head-tag text-right  border-1 "> Previous Taka <br/>
                        {{ formatted_date_string($pre_toDate) }}
                    </td>
                    <td class="text-uppercase report-head-tag text-right  border-1 "> Taka <br/> {{ formatted_date_string($toDate) }} </td>
                </tr>
                <tr>
                    <th class="text-uppercase text-underline" colspan="4"><u>Property AND Assets</u></th>
                </tr>
                <tr>
                    <th class="text-uppercase" colspan="4">1. Fixed Assets</th>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#fixed_assets">  Total Fixed </a></td>

                    <td class="text-right">{{ ($pre_total_fixed < 0 ? "(" : '').create_money_format( $pre_total_fixed).($pre_total_fixed < 0 ? ")" : '') }}</td>
                    <td class="text-right">{{ ($total_fixed < 0 ? "(" : '').create_money_format( $total_fixed).($total_fixed < 0 ? ")" : '') }}</td>
                </tr>
                <tr>
                    <th class="text-uppercase" colspan="4">2. Current Assets</th>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#inventories">Inventory</a></td>
                    <td class="text-right">{{ create_money_format($pre_total_inventory) }}</td>
                    <td class="text-right">{{ create_money_format($total_inventory) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#cash_and_banks">Cash & Bank</a></td>
                    <td class="text-right">{{ create_money_format($pre_total_cash_bank) }}</td>
                    <td class="text-right">{{ create_money_format($total_cash_bank) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40"> <a href="javascript:void(0)" data-toggle="modal" data-target="#trade_debtors">Trade Debtors</a></td>
                    <td class="text-right">{{ create_money_format($pre_total_trade_debtor) }}</td>
                    <td class="text-right">{{ create_money_format($total_trade_debtor) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal"  data-target="#account_receivable">Account Receivable</a></td>
                    <td class="text-right">{{ create_money_format($pre_total_account_receivables) }}</td>
                    <td class="text-right">{{ create_money_format($total_account_receivables) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#advance_prepayment">Advance Deposits & Prepayments</a></td>
                    <td class=" text-right">{{ ($pre_total_advance_prepayment < 0 ? "(" : '').create_money_format( $pre_total_advance_prepayment).($pre_total_advance_prepayment < 0 ? ")" : '') }}</td>
                    <td class=" text-right">{{ ($total_advance_prepayment < 0 ? "(" : '').create_money_format( $total_advance_prepayment).($total_advance_prepayment < 0 ? ")" : '') }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#due_companies">Due from Affiliatted Company</a></td>
                    <td class=" text-right">{{ ($pre_total_due_affiliated_company < 0 ? "(" : '').create_money_format( $pre_total_due_affiliated_company).($pre_total_due_affiliated_company < 0 ? ")" : '') }}</td>
                    <td class=" text-right">{{ ($total_due_affiliated_company < 0 ? "(" : '').create_money_format( $total_due_affiliated_company).($total_due_affiliated_company < 0 ? ")" : '') }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#fixed_deposits_receipt">Fixed Deposits Receipts</a></td>
                    <td class=" text-right">{{ ($pre_total_fixed_deposits_receipt < 0 ? "(" : '').create_money_format( $pre_total_fixed_deposits_receipt).($pre_total_fixed_deposits_receipt < 0 ? ")" : '') }}</td>
                    <td class=" text-right">{{ ($total_fixed_deposits_receipt < 0 ? "(" : '').create_money_format( $total_fixed_deposits_receipt).($total_fixed_deposits_receipt < 0 ? ")" : '') }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#current_assets">Other Current Assets </a></td>
                    <td class=" text-right">{{ ($pre_total_current_asset < 0 ? "(" : '').create_money_format( $pre_total_current_asset).($pre_total_current_asset < 0 ? ")" : '') }}</td>
                    <td class=" text-right">{{ ($total_current_asset < 0 ? "(" : '').create_money_format( $total_current_asset).($total_current_asset < 0 ? ")" : '') }}</td>
                </tr>

                @php
                    $total_current_asset = $total_inventory+$total_trade_debtor+$total_cash_bank+$total_account_receivables+$total_current_asset+$total_advance_prepayment+$total_due_affiliated_company+$total_fixed_deposits_receipt;

$pre_total_current_asset = $pre_total_inventory+$pre_total_trade_debtor+$pre_total_cash_bank+$pre_total_account_receivables+$pre_total_current_asset+$pre_total_advance_prepayment+$pre_total_due_affiliated_company+$pre_total_fixed_deposits_receipt;
                @endphp
                <tr>
                    <th class="padding-left-40 text-uppercase border-top-1" colspan="1">Total Current Assets</th>
                    <th class="border-top-1 text-right">{{ create_money_format($pre_total_current_asset) }} </th>
                    <th class="border-top-1 text-right">{{ create_money_format($total_current_asset) }} </th>
                </tr>
                <tr>
                    <th class="text-uppercase  border-top-1" colspan="1">Total Assets (1+2)</th>
                    <th class=" border-top-1 text-right">{{ create_money_format($pre_total_fixed+$pre_total_current_asset) }}</th>
                    <th class=" border-top-1 text-right">{{ create_money_format($total_fixed+$total_current_asset) }}</th>
                </tr>
                @php
                    $total_assets = $total_fixed+$total_current_asset;
                    $pre_total_assets = $pre_total_fixed+$pre_total_current_asset;
                @endphp

                <tr>
                    <th class="text-uppercase text-underline" colspan="4"><u>Equity and Liabilities</u></th>
                </tr>
                <tr>
                    <th class="text-uppercase" colspan="4">A. Proprietors' equity</th>
                </tr>
                @php
                    $e_sum = 1;
                @endphp
                <tr>
                    <th class="text-uppercase padding-left-40" colspan="1">1. equity</th>
                    <td class="text-right">{{ create_money_format($pre_equity_balance) }}</td>
                    <td class="text-right">{{ create_money_format($equity_balance) }}</td>
                </tr>

                @php
                    $total_equity = $equity_balance;
                    $pre_total_equity = $pre_equity_balance;
                @endphp
                @foreach($equities as $key => $value)
                    <tr>
                        <th class="padding-left-40" colspan="1">{{ $key+2 }}. {{ $value['account_type_name'] }}</th>
                        <td class="text-right">{{ create_money_format($value['pre_balance']) }}</td>
                        <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                    </tr>
                    @php
                        $e_sum = $e_sum."+".($key+2);
                        $total_equity += $value['balance'];
                        $pre_total_equity += $value['pre_balance'];
                    @endphp
                @endforeach
                @php
                    $total_equity += $net_profit;
                    $pre_total_equity += $pre_net_profit;
                @endphp
                <tr>
                    <th class="padding-left-40" colspan="1"><a href="javascript:void(0)" data-toggle="modal" data-target="#net_profit_loss"> Net Profit this year</a></th>
                    <td class="text-right">{{ create_money_format($pre_net_profit) }}</td>
                    <td class="text-right">{{ create_money_format($net_profit) }}</td>
                </tr>
                <tr>
                    <th class="text-uppercase border-top-1" colspan="1">Total Equity ({{$e_sum}})</th>
                    <th class="border-top-1 text-right">{{ create_money_format($pre_total_equity) }} </th>
                    <th class="border-top-1 text-right">{{ create_money_format($total_equity) }} </th>
                </tr>
                <tr>
                    <th class="text-uppercase" >B. <a href="javascript:void(0)" data-toggle="modal" data-target="#non_current_liabilities">Non-current Liabilities</a></th>

                    <th class="text-right">{{ create_money_format($pre_total_non_current_liability) }} </th>
                    <th class="text-right">{{ create_money_format($total_non_current_liability) }} </th>
                </tr>

                <tr>
                    <th class="text-uppercase" colspan="4">C. current Liabilities</th>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#account_payable">Account Payable</a></td>
                    <td class="text-right">{{ create_money_format($pre_total_account_payables) }}</td>
                    <td class="text-right">{{ create_money_format($total_account_payables) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#sundry_creditors">Sundry Creditors</a></td>
                    <td class="text-right">{{ create_money_format($pre_total_sundry_creditors) }}</td>
                    <td class="text-right">{{ create_money_format($total_sundry_creditors) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#overdraft_banks">Bank Overdraft</a></td>
                    <td class="text-right">{{ create_money_format($pre_total_over_bank) }}</td>
                    <td class="text-right">{{ create_money_format($total_over_bank) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#current_liabilities">Other Current Liabilities</a></td>
                    <td class="text-right">{{ create_money_format($pre_total_current_liability) }}</td>
                    <td class="text-right">{{ create_money_format($total_current_liability) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#liabilities_expense"> Liabilities For Expenses</a></td>
                    <td class=" text-right">{{ ($pre_total_liabilities_expenses < 0 ? "(" : '').create_money_format( $pre_total_liabilities_expenses).($pre_total_liabilities_expenses < 0 ? ")" : '') }}</td>
                    <td class=" text-right">{{ ($total_liabilities_expenses < 0 ? "(" : '').create_money_format( $total_liabilities_expenses).($total_liabilities_expenses < 0 ? ")" : '') }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40 text-capitalize" ><a href="javascript:void(0)" data-toggle="modal" data-target="#income_tax"> Total income tax payable </a></td>
                    <td class=" text-right">{{ ($pre_total_income_tax_payable < 0 ? "(" : '').create_money_format( $pre_total_income_tax_payable).($pre_total_income_tax_payable < 0 ? ")" : '') }}</td>
                    <td class=" text-right">{{ ($total_income_tax_payable < 0 ? "(" : '').create_money_format( $total_income_tax_payable).($total_income_tax_payable < 0 ? ")" : '') }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#due_affiliated_no"> Due to Affiliated Company </a></td>
                    <td class=" text-right">{{ ($pre_total_due_affiliated < 0 ? "(" : '').create_money_format( $pre_total_due_affiliated).($pre_total_due_affiliated < 0 ? ")" : '') }}</td>
                    <td class=" text-right">{{ ($total_due_affiliated < 0 ? "(" : '').create_money_format( $total_due_affiliated).($total_due_affiliated < 0 ? ")" : '') }}</td>
                </tr>
                @php
                    $pre_total_liabilities = $pre_total_account_payables+$pre_total_sundry_creditors+$pre_total_over_bank+$pre_total_current_liability+$pre_total_due_affiliated+$pre_total_income_tax_payable+$pre_total_liabilities_expenses;
                    $pre_total_e_laibility = $pre_total_equity+$pre_total_non_current_liability+$pre_total_liabilities;

                    $total_liabilities = $total_account_payables+$total_sundry_creditors+$total_over_bank+$total_current_liability+$total_due_affiliated+$total_income_tax_payable+$total_liabilities_expenses;
                    $total_e_laibility = $total_equity + $total_non_current_liability + $total_liabilities;
                    $pre_opening_balance = $pre_total_assets-$pre_total_e_laibility;
                    $opening_balance = $total_assets-$total_e_laibility;
                @endphp
                <tr>
                    <th class="text-uppercase border-top-1 padding-left-40" colspan="1">Total Current Liabilities</th>
                    <th class="border-top-1 text-right">{{ create_money_format($pre_total_liabilities) }} </th>
                    <th class="border-top-1 text-right">{{ create_money_format($total_liabilities) }} </th>
                </tr>
                <tr>
                    <th class="text-uppercase border-top-1 padding-left-40" colspan="1">Diff. in Opening Balances </th>
                    <th class="border-top-1 text-right">{{ create_money_format($pre_opening_balance) }} </th>
                    <th class="border-top-1 text-right">{{ create_money_format($opening_balance) }} </th>
                </tr>

                <tr>
                    <th class="text-uppercase border-top-1" colspan="1"> Total Equity & Liabilities (A+B+C)</th>
                    <th class="border-top-1 text-right">{{ create_money_format($pre_total_e_laibility+$pre_opening_balance) }}</th>
                    <th class="border-top-1 text-right">{{ create_money_format($total_e_laibility+$opening_balance) }}</th>
                </tr>

                </tbody>
            </table>


        </div>
    </div>
</div>
</body>
</html>


