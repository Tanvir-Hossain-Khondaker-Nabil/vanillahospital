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
            <li> {{ create_money_format( $inventory_no = $balance_sheet_key++) }} Inventory</li>
            <li>
                <div class="col-md-12">


                    <table class="table">

                        <tr>
                            <th>Product name </th>
                            <th class="text-center "> Quantity X Price</th>
                            <th class="text-right"> Total Price</th>
                        </tr>

                        @foreach($inventories as $key=>$value)

                            @if($value->pre_qty != 0)
                                <tr>
                                    <td>{{ $value->product_name }}</td>
                                    <td class="text-center ">{{ $value->pre_qty}}{{ $value->unit }} X {{$value->pre_item_price }}</td>
                                    <td class="text-right">{{ create_money_format($value->pre_qty*$value->pre_item_price) }}</td>
                                </tr>
                            @endif

                        @endforeach

                        <tr>
                            <th colspan="2">Total Opening Stock</th>
                            <th class="dual-line text-right"> {{ create_money_format($pre_total_inventory) }} </th>
                        </tr>

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
            <li> {{ create_money_format($fixed_asset_no = $balance_sheet_key++) }} Fixed Assets</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
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
        <br/><br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($current_asset_no = $balance_sheet_key++) }} Current Assets</li>
            <li>
                <div class="col-md-12">
                    <table class="table">

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
        <br/><br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($cash_bank_no = $balance_sheet_key++) }} Cash & Bank</li>
            <li>
                <div class="col-md-12">
                    <table class="table">

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
        <br/><br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($trade_debtors_no = $balance_sheet_key++) }} Trade Debtors</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
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
        <br/><br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($due_affiliated_company_no = $balance_sheet_key++) }} Due from Affiliated Company</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
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
        <br/><br/>


        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($advance_prepayment_no = $balance_sheet_key++) }} Advance Deposits & Prepayments</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
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
        <br/><br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($fixed_deposits_receipt_no = $balance_sheet_key++) }} Fixed Deposits Receipts</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
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
        <br/><br/>
        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($account_payable_no = $balance_sheet_key++) }} Account Payable</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
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


        <br/><br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($account_receivable_no = $balance_sheet_key++) }} Account Receivable</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
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

        <br/><br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($sundry_creditor_no = $balance_sheet_key++) }} Sundry Creditors</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
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
        <br/><br/>
        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($over_bank_no = $balance_sheet_key++) }} Bank Overdraft</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
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
                    </table>
                </div>
            </li>
        </ul>
        <br/><br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($non_current_liability_no = $balance_sheet_key++) }} Non-Current Liabilities</li>
            <li>
                <div class="col-md-12">
                    <table class="table">

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
        <br/><br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($current_liability_no = $balance_sheet_key++) }} Current Liabilities</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
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
        <br/><br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($liabilities_expenses_no = $balance_sheet_key++) }} Liabilities For Expenses</li>
            <li>
                <div class="col-md-12">
                    <table class="table">

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
        <br/><br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($income_tax_no = $balance_sheet_key++) }} Income tax payable</li>
            <li>
                <div class="col-md-12">
                    <table class="table">

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
        <br/><br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($due_affiliated_no = $balance_sheet_key++) }} Due to Affiliated Company</li>
            <li>
                <div class="col-md-12">
                    <table class="table">

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
        <br/><br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($sale_no = $balance_sheet_key++) }} Sales</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
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
        <br/><br/>

        <ul class="balance_sheet_ul">
            <li> {{ create_money_format($purchase_no = $balance_sheet_key++) }} Purchases</li>
            <li>
                <div class="col-md-12">
                    <table class="table">
                        @foreach($purchase_details as $key=>$value)
                            <tr>
                                <td colspan="2">{{ $value->item->item_name }}</td>
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
        </ul>
        <br/><br/>

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
                    <th class="text-uppercase" colspan="3">1. Sales(net)</th>
                </tr>
                <tr>
                    <td class="text-uppercase" >A1. Sales</td>
                    <td class="text-right">{{ create_money_format( $total_sales) }}</td>
                </tr>
                <tr>
                    <td class="text-uppercase" >B1. Sales Return</td>
                    <td class="text-right">{{ create_money_format( $total_sales_return) }}</td>
                </tr>
                <tr>
                    <th class="padding-left-40 text-uppercase ">Total(A1-B1)</th>
                    <th class=" single-line text-right">{{ create_money_format($net_sale) }}</th>
                </tr>
                <tr>
                    <th class="text-uppercase" colspan="3">2. Less: Cost of Goods Sold</th>
                </tr>
                <tr>
                    <td class="padding-left-40" >A. Opening Stock</td>
                    <td class="text-right">{{ create_money_format($openingStock) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" >B2. Purchase</td>
                    <td class="text-right">{{ create_money_format($total_purchases) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" >B3. Purchase Return</td>
                    <td class="text-right">{{ create_money_format($total_purchases_return) }}</td>
                </tr>
                <tr>
                    <td class="padding-left-40" >B. Total (B2-B3) </td>
                    <td class="text-right">{{ create_money_format($net_purchase) }}</td>
                </tr>
                <tr>
                    <th class="padding-left-40 text-uppercase " >Total(A+B)</th>
                    <th class=" single-line text-right">{{ create_money_format($total_AB) }}</th>
                </tr>
                <tr>
                    <td class="padding-left-40" >C. Closing Stock</td>
                    <td class="text-right">({{ create_money_format($total_inventory) }})</td>
                </tr>
                <tr>
                    <th class="padding-left-40 text-uppercase " >Total(A+B-C)</th>
                    <th class=" single-line text-right">{{ create_money_format($total_ABC) }}</th>
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
                    <th class=" border-top-1 text-right">{{ $gross_profit >= 0 ? create_money_format($gross_profit) :  "(".create_money_format((-1)*$gross_profit).")" }}</th>
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


