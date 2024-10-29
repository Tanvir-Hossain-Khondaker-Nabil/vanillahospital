<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/11/2019
 * Time: 2:01 PM
 */


$balance_sheet_key = 1;
?>

  @include('member.reports.common.head')

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
                            <th colspan="3">Inventory</th>
                        </tr>

                        @php
                            $category = '';
                        @endphp
                        @foreach($inventories as $key=>$value)

                            @if( !empty($category) && $category != $value->category && $value->pre_total_per_category>0)
                                <tr>
                                    <td colspan="2">Total {{ $category }}</td>
                                    <td class="single-line text-right"> {{ create_money_format($value->pre_total_per_category) }} </td>
                                </tr>
                            @endif
                            @if(($loop->first || $category != $value->category ) && $value->pre_qty>0 )
                                <tr>
                                    <th>{{ $value->category }}</th>
                                    <td class="text-right single-line"> Quantity (in {{ $value->unit }})</td>
                                </tr>
                            @endif

                            @if($value->pre_qty>0)
                                <tr>
                                    <td>{{ $value->product_name }}</td>
                                    <td class="text-right padding-right-50">{{ $value->pre_qty }}</td>
                                    <td class="text-right">{{ create_money_format($value->pre_qty*$value->pre_item_price) }}</td>
                                </tr>
                            @endif

                            @if($loop->last && $value->pre_total_per_category>0)
                                <tr>
                                    <td colspan="2">Total {{ $value->category }}</td>
                                    <td class="single-line text-right"> {{ create_money_format($value->pre_total_per_category) }} </td>
                                </tr>

                            @endif

                            @php
                                $category = $value->category;
                            @endphp
                        @endforeach

                        @if($pre_total_inventory>0)
                        <tr>
                            <th colspan="2">Total Pre Inventory</th>
                            <th class="dual-line text-right"> {{ create_money_format($pre_total_inventory) }} </th>
                        </tr>
@endif
                        <tr>
                            <td colspan="3" class="border-top-1" height="20px"></td>
                        </tr>

                        @php
                            $category = '';
                        @endphp
                        @foreach($inventories as $key=>$value)

                            @if( !empty($category) && $category != $value->category && $value->total_per_category>0)
                                <tr>
                                    <td colspan="2">Total {{ $category }}</td>
                                    <td class="single-line text-right"> {{ create_money_format($value->total_per_category) }} </td>
                                </tr>
                            @endif
                            @if(($loop->first || $category != $value->category ) && $value->qty>0)
                                <tr>
                                    <th>{{ $value->category }}</th>
                                    <td class="text-right single-line"> Quantity (in {{ $value->unit }})</td>
                                </tr>
                            @endif

                            @if($value->qty>0)
                                <tr>
                                    <td>{{ $value->product_name }}</td>
                                    <td class="text-right padding-right-50">{{ $value->qty }}</td>
                                    <td class="text-right">{{ create_money_format($value->qty*$value->item_price) }}</td>
                                </tr>
                            @endif

                            @if($loop->last && $value->total_per_category>0)
                                <tr>
                                    <td colspan="2">Total {{ $value->category }}</td>
                                    <td class="single-line text-right"> {{ create_money_format($value->total_per_category) }} </td>
                                </tr>

                            @endif

                            @php
                                $category = $value->category;
                            @endphp
                        @endforeach
                        <tr>
                            <th colspan="2">Total Inventory</th>
                            <th class="dual-line text-right"> {{ create_money_format($total_inventory) }} </th>
                        </tr>
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
                        @foreach($current_assets as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value['account_type_name'] }}</td>
                                <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                            </tr>
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
                        @foreach($cash_banks as $key=>$value)
                            @if($value['balance'] > 0)
                                <tr>
                                    <td colspan="2">{{ $value['account_type_name'] }}</td>
                                    <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                                </tr>
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
                        @endforeach
                        <tr>
                            <th colspan="2">Total Trade Debtor</th>
                            <th class="dual-line text-right">{{ ($total_trade_debtor < 0 ? "(" : '').create_money_format( $total_trade_debtor).($total_trade_debtor < 0 ? ")" : '') }}</th>
                        </tr>
                    </table>
                </div>
            </li>
        </ul>
        <br/>


        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($due_affiliated_company_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Due from Affiliated Company </th>
                        </tr>
                        @foreach($due_companies as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value['account_type_name'] }}</td>
                                <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="2">Total</th>
                            <th class="dual-line text-right">{{ ($total_due_affiliated_company < 0 ? "(" : '').create_money_format( $total_due_affiliated_company).($total_due_affiliated_company < 0 ? ")" : '') }}</th>
                        </tr>
                    </table>
                </div>
            </li>
        </ul>
        <br/>


        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($advance_prepayment_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Advance Deposits & Prepayments</th>
                        </tr>
                        @foreach($advance_prepayments as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value['account_type_name'] }}</td>
                                <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="2">Total</th>
                            <th class="dual-line text-right">{{ ($total_advance_prepayment < 0 ? "(" : '').create_money_format( $total_advance_prepayment).($total_advance_prepayment < 0 ? ")" : '') }}</th>
                        </tr>
                    </table>
                </div>
            </li>
        </ul>
        <br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($fixed_deposits_receipt_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Fixed Deposits Receipts</th>
                        </tr>
                        @foreach($fixed_deposits_receipts as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value['account_type_name'] }}</td>
                                <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="2">Total</th>
                            <th class="dual-line text-right">{{ ($total_fixed_deposits_receipt < 0 ? "(" : '').create_money_format( $total_fixed_deposits_receipt).($total_fixed_deposits_receipt < 0 ? ")" : '') }}</th>
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
                                <td class="text-right">{{ create_money_format( $value['balance'] ) }}</td>
                            </tr>
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
                                <td class="text-right">{{ create_money_format( $value['balance'] ) }}</td>
                            </tr>
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
                        @foreach($over_banks as $key=>$value)

                                <tr>
                                    <td colspan="2">{{ $value['account_type_name'] }}</td>
                                    <td class="text-right">{{ create_money_format( $value['balance'] ) }}</td>
                                </tr>
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

                        @foreach($non_current_liabilities as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value['account_type_name'] }}</td>
                                <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                            </tr>
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
                        @foreach($current_liabilities as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value['account_type_name'] }}</td>
                                <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                            </tr>
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
            <li> {{ create_money_format($liabilities_expenses_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Liabilities For Expenses</th>
                        </tr>

                        @foreach($liabilities_expenses as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value['account_type_name'] }}</td>
                                <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="2">Total </th>
                            <th class="dual-line text-right">{{ ($total_liabilities_expenses < 0 ? "(" : '').create_money_format( $total_liabilities_expenses).($total_liabilities_expenses < 0 ? ")" : '') }}</th>
                        </tr>
                    </table>

                </div>
            </li>
        </ul>
        <br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($income_tax_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Total income tax payable</th>
                        </tr>

                        @foreach($income_tax_payables as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value['account_type_name'] }}</td>
                                <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="2">Total </th>
                            <th class="dual-line text-right">{{ ($total_income_tax_payable < 0 ? "(" : '').create_money_format( $total_income_tax_payable).($total_income_tax_payable < 0 ? ")" : '') }}</th>
                        </tr>
                    </table>

                </div>
            </li>
        </ul>
        <br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($due_affiliated_no = $balance_sheet_key++) }}</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th colspan="3">Due to Affiliated Company</th>
                        </tr>

                        @foreach($due_to_affiliated_companies as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value['account_type_name'] }}</td>
                                <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="2">Total </th>
                            <th class="dual-line text-right">{{ ($total_due_affiliated < 0 ? "(" : '').create_money_format( $total_due_affiliated).($total_due_affiliated < 0 ? ")" : '') }}</th>
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
                                <td colspan="2">{{ $value->item->item_name }}</td>
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
                                <td colspan="2">{{ $value->item->item_name }}</td>
                                <td class="text-right">{{ create_money_format($value->sum_total_price) }}</td>
                            </tr>
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
                    <th class="text-uppercase  border-top-1" >3. Gross Profit (1-2)</th>
                    <th class=" border-top-1 text-right">{{ create_money_format($total_sales-$total_cost_of_sold) }}</th>
                </tr>
                <tr>
                    <th class="text-uppercase" colspan="3">4. Less: Administrative and general Expenses</th>
                </tr>
                @foreach($expenses as $key => $value)
                    <tr>
                        <td  class=" {{ isset($value['child']) && $value['child'] == 'yes' ? 'padding-left-70 ' : 'padding-left-40'  }} {{ isset($value['parent']) && $value['parent'] == 'yes' ? ' text-bold ' : '' }}" >{{ $value['account_type_name'] }}</td>
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
                        <td class=" {{ isset($value['child']) && $value['child'] == 'yes' ? 'padding-left-70 ' : 'padding-left-40'  }} {{ isset($value['parent']) && $value['parent'] == 'yes' ? ' text-bold ' : '' }}" >{{ $value['account_type_name'] }}</td>
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


