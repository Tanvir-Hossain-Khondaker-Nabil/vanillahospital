<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/11/2019
 * Time: 2:01 PM
 */

 $route = \Auth::user()->can(['member.report.balance_sheet']) ? route('member.report.balance_sheet') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'List',
        'href' => $route,
    ],
    [
        'name' => 'Balance Sheet Report',
    ],
];

$data['data'] = [
    'name' => "Balance Sheet",
    'title'=> 'Balance Sheet',
    'heading' => 'Balance Sheet ',
];

$balance_sheet_key = 1;
$total_fixed = $total_current = $total_cash_bank = $trade_debtor_no = $total_trade_debtor = 0;
$adv_deposit_no = $total_adv_deposits = $total_account_payables = $account_payable_no = 0;
$account_receivable_no = $total_account_receivables = $sundry_creditor_no = $total_sundry_creditors = 0;
$total_over_bank = $over_bank_no = $non_current_liability_no = $total_non_current_liability = 0;
$current_liability_no = $total_current_liability = $current_asset_no = $total_current_asset = 0;
$sale_no = $total_sales = $purchase_no = $total_purchases = 0;
?>
@extends('layouts.back-end.master', $data)

@push('styles')
<link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush

@section('contents')

<div class="row">
    <div class="col-xs-12">
        @include('common._alert')

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Search</h3>
            </div>

            {!! Form::open(['route' => ['member.report.balance_sheet'],'method' => 'GET', 'role'=>'form' ]) !!}

            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <label>  Fiscal Year </label>
                        {!! Form::select('fiscal_year', $fiscal_year, null ,['class'=>'form-control select2','placeholder'=>'Select All']); !!}
                    </div>
                    <div class="col-md-2">
                        <label> Year </label>
                        <input class="form-control year" name="year" value="" autocomplete="off"/>
                    </div>
                    <div class="col-md-2">
                        <label> From Date </label>
                        <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                    </div>
                    <div class="col-md-2">
                        <label> To Date</label>
                        <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                    </div>
                    <div class="col-md-2 margin-top-23">
                        <label></label>
                        <input class="btn btn-sm btn-info" value="Search" type="submit"/>
                        <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Reload</a>

                    </div>
                </div>
                <!-- /.row -->
            </div>

            {!! Form::close() !!}
        </div>

        <div class="box">

            @include('member.reports.print_title_btn')

            <div class="box-body">
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
                                        <td class="text-right">{{ create_money_format($qty*$value->item->price) }}</td>
                                    </tr>

                                    @php
                                        $last_unit = $value->item->unit;
                                        $category = $value->item->category->display_name;
                                        $total_inventory += $qty*$value->item->price;
                                        $total_per_category += $qty*$value->item->price;
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
                                    <th colspan="2">Total </th>
                                    <th class="dual-line text-right">{{ create_money_format( $total_sundry_creditors) }}</th>
                                </tr>
                            </table>
                        </div>
                    </li>
                </ul>
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

                <div class="col-lg-12">
                     <table class="table table-striped" id="dataTable">
                        <thead>

                        <tr>
                            <th colspan="3" style="border: none !important; padding-bottom: 20px;" class="text-center">
                                <h3>{{ $report_title }}</h3>
                            </th>
                        </tr>
                        </thead>

                        <tbody>

                            <tr>
                                <td class="text-uppercase report-head-tag border-1 "> particulars</td>
                                <td class="text-uppercase report-head-tag width-100 border-1 "> Notes</td>
                                <td class="text-uppercase report-head-tag text-right padding-right-50 border-1 "> Taka</td>
                            </tr>
                            <tr>
                                <th class="text-uppercase" >1. Sales(net)</th>
                                <th class="text-center width-100" >{{ $sale_no }}</th>
                                <th class="text-right">{{ create_money_format( $total_sales) }}</th>
                            </tr>
                            <tr>
                                <th class="text-uppercase" colspan="3">2. Less: Cost of Goods Sold</th>
                            </tr>
                            <tr>
                                <td class="padding-left-40" colspan="2">A. Opening Stock</td>
                                <td class="text-right">{{ create_money_format($openingStock) }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" >B. Purchase</td>
                                <td class="text-center width-100" >{{ $purchase_no }}</td>
                                <td class="text-right">{{ create_money_format($total_purchases) }}</td>
                            </tr>
                            <tr>
                                <th class="padding-left-40 text-uppercase " colspan="2">Total(A+B)</th>
                                <th class=" single-line text-right">{{ create_money_format($openingStock+$total_purchases) }}</th>
                            </tr>
                            <tr>
                                <td class="padding-left-40" colspan="2">C. Closing Stock</td>
                                <td class="text-right">({{ create_money_format($total_inventory) }})</td>
                            </tr>
                            <tr>
                                <th class="padding-left-40 text-uppercase " colspan="2">Total(A+B-C)</th>
                                <th class=" single-line text-right">{{ create_money_format($openingStock+$total_purchases-$total_inventory) }}</th>
                            </tr>

                            @php
                                $total_cost_of_sold = $openingStock+$total_purchases-$total_inventory+$cost_of_sold_balance;
                            @endphp
                            @foreach($cost_of_sold_items as $key => $value)
                                <tr>
                                    <td class="padding-left-40" colspan="2">{{ $value['account_type_name'] }}</td>
                                    <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                                </tr>
                                @php
                                    $total_cost_of_sold += $value['balance'];
                                @endphp
                            @endforeach
                            <tr>
                                <th class="padding-left-40 text-uppercase" colspan="2">Cost of Goods Sold</th>
                                <th class="single-line text-right">{{ create_money_format($total_cost_of_sold) }}</th>
                            </tr>
                            <tr>
                                <th class="text-uppercase  border-top-1" colspan="2">3. Gross Profit (1-2)</th>
                                <th class=" border-top-1 text-right">{{ create_money_format($total_sales-$total_cost_of_sold) }}</th>
                            </tr>
                            <tr>
                                <th class="text-uppercase" colspan="3">4. Less: Administrative and general Expenses</th>
                            </tr>
                            @php
                                $total_expense = 0;
                            @endphp
                            @foreach($expenses as $key => $value)
                                <tr>
                                    <td class="padding-left-40" colspan="2">{{ $value['account_type_name'] }}</td>
                                    <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                                </tr>
                                @php
                                    $total_expense += $value['balance'];
                                @endphp
                            @endforeach

                            <tr>
                                <th class="padding-left-40 text-uppercase " colspan="2">Total</th>
                                <th class=" single-line text-right">{{ create_money_format($total_expense) }}</th>
                            </tr>
                            <tr>
                                <th class="text-uppercase  border-top-1" colspan="2">5. Net Profit (3-4)</th>
                                <th class=" border-top-1 text-right">{{ create_money_format( $net_profit = $total_sales-$total_cost_of_sold-$total_expense) }}</th>
                            </tr>


                            <tr>
                                <td colspan="3">
                                    <br/>
                                    <br/>
                                    <br/>
                                    <br/>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-uppercase report-head-tag border-1 "> particulars</td>
                                <td class="text-uppercase report-head-tag width-100 border-1 "> Notes</td>
                                <td class="text-uppercase report-head-tag text-right padding-right-50 border-1 "> Taka</td>
                            </tr>
                            <tr>
                                <th class="text-uppercase text-underline" colspan="3"><u>Property AND Assets</u></th>
                            </tr>
                            <tr>
                                <th class="text-uppercase" colspan="3">1. Fixed Assets</th>
                            </tr>
                            <tr>
                                <td class="padding-left-40" >Total Fixed</td>
                                <td class="text-center width-100" >{{ $fixed_asset_no }}</td>
                                <td class="text-right">{{ ($total_fixed < 0 ? "(" : '').create_money_format( $total_fixed).($total_fixed < 0 ? ")" : '') }}</td>
                            </tr>
                            <tr>
                                <th class="text-uppercase" colspan="3">2. Current Assets</th>
                            </tr>
                            <tr>
                                <td class="padding-left-40" >Inventory</td>
                                <td class="text-center width-100" >{{ $inventory_no }}</td>
                                <td class="text-right">{{ create_money_format($total_inventory) }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" >Cash & Bank</td>
                                <td class="text-center width-100" >{{ $cash_bank_no }}</td>
                                <td class="text-right">{{ create_money_format($total_cash_bank) }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40"> Trade Debtors</td>
                                <td class="text-center width-100" >{{ $trade_debtor_no }}</td>
                                <td class="text-right">{{ create_money_format($total_trade_debtor) }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" >Account Receivable</td>
                                <td class="text-center width-100" >{{ $account_receivable_no }}</td>
                                <td class="text-right">{{ create_money_format($total_account_receivables) }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" >Others Current Assets</td>
                                <td class="text-center width-100" >{{ $current_asset_no }}</td>
                                <td class="text-right">{{ create_money_format($total_current_asset) }}</td>
                            </tr>
                            @php
                                $total_current_asset = $total_inventory+$total_trade_debtor+$total_cash_bank+$total_account_receivables+$total_current_asset;
                            @endphp
                            <tr>
                                <th class="padding-left-40 text-uppercase border-top-1" colspan="2">Total Current Assets</th>
                                <th class="border-top-1 text-right">{{ create_money_format($total_current_asset) }} </th>
                            </tr>
                            <tr>
                                <th class="text-uppercase  border-top-1" colspan="2">Total Assets (1+2)</th>
                                <th class=" border-top-1 text-right">{{ create_money_format($total_fixed+$total_current_asset) }}</th>
                            </tr>

                            <tr>
                                <th class="text-uppercase text-underline" colspan="3"><u>Equity and Liabilities</u></th>
                            </tr>
                            <tr>
                                <th class="text-uppercase" colspan="3">A. Proprietors' equity</th>
                            </tr>
                            @php
                                $e_sum = 1;
                            @endphp
                            <tr>
                                <th class="text-uppercase padding-left-40" colspan="2">1. equity</th>
                                <td class="text-right">{{ create_money_format($equity_balance) }}</td>
                            </tr>

                            @php
                                $total_equity = $equity_balance;
                            @endphp
                            @foreach($equities as $key => $value)
                                <tr>
                                    <th class="padding-left-40" colspan="2">{{ $key+2 }}. {{ $value['account_type_name'] }}</th>
                                    <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                                </tr>
                                @php
                                    $e_sum = $e_sum."+".($key+2);
                                    $total_equity += $value['balance'];
                                @endphp
                            @endforeach
                            @php
                                $total_equity += $net_profit;
                            @endphp
                            <tr>
                                <th class="padding-left-40" colspan="2"> Net Profit this year</th>
                                <td class="text-right">{{ create_money_format($net_profit) }}</td>
                            </tr>
                            <tr>
                                <th class="text-uppercase border-top-1" colspan="2">Total Equity ({{$e_sum}})</th>
                                <th class="border-top-1 text-right">{{ create_money_format($total_equity) }} </th>
                            </tr>
                            <tr>
                                <th class="text-uppercase" >B. Non-current Liabilities</th>
                                <td class="text-center width-100" >{{ $non_current_liability_no }}</td>
                                <th class="text-right">{{ create_money_format($total_non_current_liability) }} </th>
                            </tr>

                            <tr>
                                <th class="text-uppercase" colspan="3">C. current Liabilities</th>
                            </tr>
                            <tr>
                                <td class="padding-left-40" >Account Payable</td>
                                <td class="text-center width-100" >{{ $account_payable_no }}</td>
                                <td class="text-right">{{ create_money_format($total_account_payables) }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" >Sundry Creditors</td>
                                <td class="text-center width-100" >{{ $sundry_creditor_no }}</td>
                                <td class="text-right">{{ create_money_format($total_sundry_creditors) }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" >Bank Overdraft</td>
                                <td class="text-center width-100" >{{ $over_bank_no }}</td>
                                <td class="text-right">{{ create_money_format($total_over_bank) }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" >Other Liabilities</td>
                                <td class="text-center width-100" >{{ $current_liability_no }}</td>
                                <td class="text-right">{{ create_money_format($total_current_liability) }}</td>
                            </tr>
                            @php
                                $total_liabilities = $total_account_payables+$total_sundry_creditors+$total_over_bank+$total_current_liability;
                            @endphp
                            <tr>
                                <th class="text-uppercase border-top-1 padding-left-40" colspan="2">Total Current Liabilities</th>
                                <th class="border-top-1 text-right">{{ create_money_format($total_liabilities) }} </th>
                            </tr>
                            <tr>
                                <th class="text-uppercase border-top-1" colspan="2"> Total Equity & Liabilities (A+B+C)</th>
                                <th class="border-top-1 text-right">{{ create_money_format($total_equity+$total_non_current_liability+$total_liabilities) }}</th>
                            </tr>

                        </tbody>
                    </table>



                </div>
            </div>
        </div>
    </div>
</div>

@endsection



@push('scripts')

<script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

<script type="text/javascript">

    $(document).ready( function(){
        $('.select2').select2();
        $('.date').datepicker({
            "setDate": new Date(),
            "format": 'mm/dd/yyyy',
            "endDate": "+0d",
            "todayHighlight": true,
            "autoclose": true
        });
        var $setDate = new Date( '{{ str_replace("-", "/", $fiscal_years[1]->created_at) }}' );
        var today = new Date($setDate.getFullYear(), $setDate.getMonth(), $setDate.getDate(), 0, 0, 0, 0);
        // console.log(new Date());
        $('.year').datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            endDate: '+0d',
            setDate: today
        });

        $('.date').change( function (e) {
            $('.date').attr('required', true);
        })
    });
</script>
@endpush

