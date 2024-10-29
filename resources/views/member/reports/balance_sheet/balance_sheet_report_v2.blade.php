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
    'heading' =>trans('common.balance_sheet'),
];
$balance_sheet_key = 1;

$inventory_no = $balance_sheet_key++;
$fixed_asset_no = $balance_sheet_key++;
$current_asset_no = $balance_sheet_key++;
$cash_bank_no = $balance_sheet_key++;
$trade_debtors_no = $balance_sheet_key++;
$due_affiliated_company_no = $balance_sheet_key++;
$advance_prepayment_no = $balance_sheet_key++;
$fixed_deposits_receipt_no  = $balance_sheet_key++;
$account_payable_no = $balance_sheet_key++;
$account_receivable_no = $balance_sheet_key++;
$sundry_creditor_no = $balance_sheet_key++;
$over_bank_no = $balance_sheet_key++;
$non_current_liability_no = $balance_sheet_key++;
$current_liability_no = $balance_sheet_key++;
$liabilities_expenses_no = $balance_sheet_key++;
$income_tax_no = $balance_sheet_key++;
$due_affiliated_no = $balance_sheet_key++;
$sale_no  = $balance_sheet_key++;
$purchase_no  = $balance_sheet_key++;
$equity_no  = $balance_sheet_key++;


$parameter =  explode(Request::url(), Request::fullUrl());
$parameter = isset($parameter[1]) ? $parameter[1] : '';
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
                <h3 class="box-title">{{__('common.search')}}</h3>
            </div>

            {!! Form::open(['route' => ['member.report.balance_sheet'],'method' => 'GET', 'role'=>'form' ]) !!}

            <div class="box-body">
                <div class="row">
                    @if(Auth::user()->hasRole(['super-admin', 'developer']))
                        <div class="col-md-3">
                            <label>{{__('common.select_company')}}    </label>
                            {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=> trans('common.select_company')]); !!}
                        </div>
                    @endif
                    <div class="col-md-3">
                        <label> {{__('common.fiscal_year')}}   </label>
                        {!! Form::select('fiscal_year', $fiscal_year, null ,['class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                    </div>
                    <div class="col-md-2">
                        <label> {{__('common.year')}} </label>
                        <input class="form-control year" name="year" value="" autocomplete="off"/>
                    </div>
                    <div class="col-md-2">
                        <label>{{__('common.from_year')}} </label>
                        <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                    </div>
                    <div class="col-md-2">
                        <label> {{__('common.to_year')}} </label>
                        <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                    </div>
                        <div class="col-md-12">
                            <div class="col-md-3 margin-top-23">
                                <input type="checkbox" name="t_based_view" value="1" {{ $t_based_view ? "checked" : "" }}/>
                                <label> {{__('common.t_based_view')}} </label>
                            </div>

                        </div>
                    <div class="col-md-2 margin-top-23">
                        <label></label>
                        <input class="btn btn-sm btn-info" value="{{__('common.search')}}" type="submit"/>
                        <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i>{{__('common.reload')}} </a>

                    </div>
                </div>
                <!-- /.row -->
            </div>

            {!! Form::close() !!}
        </div>

        <div class="box">

            @include('member.reports.print_title_btn')

            <div class="box-header with-border">

                <a href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=full_balance_sheet" class="btn btn-info  btn-sm  pull-right" id="btn-print"> <i class="fa fa-print"></i> {{__('common.print_balance_sheet_details')}} </a>
                <a href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=download_full_balance_sheet" class="btn btn-default  btn-sm  pull-right  mr-3"> <i class="fa fa-download"></i> {{__('common.download_balance_sheet_details')}}</a>
            </div>

            <div class="box-body balance_sheet">

{{--                @include('member.reports.common._modal_balance_sheet_v2')--}}

                <div class="col-lg-12">
                     <table class="table table-striped" id="dataTable">
                        <thead>

                        <tr>
                            <th colspan="5" style="border: none !important; padding-bottom: 20px;" class="text-center">
                                <h3>{!!  $report_title !!} </h3>
                            </th>
                        </tr>
                        </thead>


                         <tbody>
                            <tr>
                                <td class="text-uppercase report-head-tag border-1 "> {{__('common.particulars')}}</td>
                                <td class="text-uppercase report-head-tag width-100 border-1 text-center">{{__('common.notes')}} </td>
                                <td class="text-uppercase report-head-tag text-right  border-1 "> {{__('common.previous_balance')}}<br/>
                                {{ formatted_date_string($pre_toDate) }}
                                </td>
                                <td class="text-uppercase report-head-tag text-right  border-1 ">{{__('common.taka')}}  <br/> {{ formatted_date_string($toDate) }} </td>
                                <td></td>
                            </tr>
                            <tr>
                                <th class="text-uppercase text-underline" colspan="5"><u> {{__('common.property_and_assets')}}</u></th>
                            </tr>
                            <tr>
                                <th class="text-uppercase" colspan="5">1. {{__('common.fixed_assets')}}</th>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#fixed_assets">  {{__('common.total_fixed')}}  </a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#fixed_assets"> {{ $fixed_asset_no }} </a> </td>
                                <td class="text-right">{{ ($pre_total_fixed < 0 ? "(" : '').create_money_format( $pre_total_fixed).($pre_total_fixed < 0 ? ")" : '') }}</td>
                                <td class="text-right">{{ ($total_fixed < 0 ? "(" : '').create_money_format( $total_fixed).($total_fixed < 0 ? ")" : '') }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.fixed_asset_report').$parameter}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-uppercase" colspan="5">2. {{__('common.current_assets')}}</th>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#inventories">{{__('common.inventory')}} </a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#inventory">{{ $inventory_no }}</a></td>
                                <td class="text-right">{{ create_money_format($pre_total_inventory) }}</td>
                                <td class="text-right">{{ create_money_format($total_inventory) }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.head_inventory').$parameter}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#cash_and_banks">{{__('common.cash_and_bank')}}</a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#cash_and_banks">{{ $cash_bank_no }}</a></td>
                                <td class="text-right">{{ create_money_format($pre_total_cash_bank) }}</td>
                                <td class="text-right">{{ create_money_format($total_cash_bank) }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.cash_bank_report').$parameter}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="padding-left-40"> <a href="javascript:void(0)" data-toggle="modal" data-target="#trade_debtors">{{__('common.trade_debtors')}}</a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#trade_debtors">{{ $trade_debtors_no }}</a></td>
                                <td class="text-right">{{ create_money_format($pre_total_trade_debtor) }}</td>
                                <td class="text-right">{{ create_money_format($total_trade_debtor) }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.trade_debtor_report').$parameter}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal"  data-target="#account_receivable">{{__('common.account_receivable')}}</a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#account_receivable">{{ $account_receivable_no }}</a></td>
                                <td class="text-right">{{ create_money_format($pre_total_account_receivables) }}</td>
                                <td class="text-right">{{ create_money_format($total_account_receivables) }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.account_receivable_report').$parameter}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#advance_prepayment">{{__('common.advance_deposits_and_prepayments')}} </a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#advance_prepayment">{{ $advance_prepayment_no }}</a></td>
                                <td class=" text-right">{{ ($pre_total_advance_prepayment < 0 ? "(" : '').create_money_format( $pre_total_advance_prepayment).($pre_total_advance_prepayment < 0 ? ")" : '') }}</td>
                                <td class=" text-right">{{ ($total_advance_prepayment < 0 ? "(" : '').create_money_format( $total_advance_prepayment).($total_advance_prepayment < 0 ? ")" : '') }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.advance_prepayment_report').$parameter}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#due_companies">{{__('common.due_from_affiliatted_company')}} </a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#due_companies">{{ $due_affiliated_company_no }}</a></td>
                                <td class=" text-right">{{ ($pre_total_due_affiliated_company < 0 ? "(" : '').create_money_format( $pre_total_due_affiliated_company).($pre_total_due_affiliated_company < 0 ? ")" : '') }}</td>
                                <td class=" text-right">{{ ($total_due_affiliated_company < 0 ? "(" : '').create_money_format( $total_due_affiliated_company).($total_due_affiliated_company < 0 ? ")" : '') }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.due_companies_report').$parameter}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#fixed_deposits_receipt">{{__('common.fixed_deposits_receipts')}}</a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#fixed_deposits_receipt">{{ $fixed_deposits_receipt_no }}</a></td>
                                <td class=" text-right">{{ ($pre_total_fixed_deposits_receipt < 0 ? "(" : '').create_money_format( $pre_total_fixed_deposits_receipt).($pre_total_fixed_deposits_receipt < 0 ? ")" : '') }}</td>
                                <td class=" text-right">{{ ($total_fixed_deposits_receipt < 0 ? "(" : '').create_money_format( $total_fixed_deposits_receipt).($total_fixed_deposits_receipt < 0 ? ")" : '') }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.fixed_deposits_receipts_report').$parameter}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#current_assets"> {{__('common.other_ucrrent_assets')}} </a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#current_assets">{{ $current_asset_no }}</a></td>
                                <td class=" text-right">{{ ($pre_total_current_asset < 0 ? "(" : '').create_money_format( $pre_total_current_asset).($pre_total_current_asset < 0 ? ")" : '') }}</td>
                                <td class=" text-right">{{ ($total_current_asset < 0 ? "(" : '').create_money_format( $total_current_asset).($total_current_asset < 0 ? ")" : '') }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.current_asset_report').$parameter}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
                            </tr>
                            @php
                                $total_current_asset = $total_inventory+$total_trade_debtor+$total_cash_bank+$total_account_receivables+$total_current_asset+$total_advance_prepayment+$total_due_affiliated_company+$total_fixed_deposits_receipt;

$pre_total_current_asset = $pre_total_inventory+$pre_total_trade_debtor+$pre_total_cash_bank+$pre_total_account_receivables+$pre_total_current_asset+$pre_total_advance_prepayment+$pre_total_due_affiliated_company+$pre_total_fixed_deposits_receipt;
                            @endphp
                            <tr>
                                <th class="padding-left-40 text-uppercase border-top-1" colspan="2">{{__('common.total_current_assets')}}</th>
                                <th class="border-top-1 text-right">{{ create_money_format($pre_total_current_asset) }} </th>
                                <th class="border-top-1 text-right">{{ create_money_format($total_current_asset) }} </th>

                            </tr>
                            <tr>
                                <th class="text-uppercase  border-top-1" colspan="2">{{__('common.total_assets')}}  (1+2)</th>
                                <th class=" border-top-1 text-right">{{ create_money_format($pre_total_fixed+$pre_total_current_asset) }}</th>
                                <th class=" border-top-1 text-right">{{ create_money_format($total_fixed+$total_current_asset) }}</th>
                            </tr>
                            @php
                                $total_assets = $total_fixed+$total_current_asset;
                                $pre_total_assets = $pre_total_fixed+$pre_total_current_asset;
                            @endphp

                            <tr>
                                <th class="text-uppercase text-underline" colspan="5"><u>{{__('common.equity_and_liabilities')}}</u></th>
                            </tr>
                            <tr>
                                <th class="text-uppercase" colspan="5">A. {{__('common.proprietors_equity')}}</th>
                            </tr>

                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#equity"> {{__('common.equity')}}</a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#equity">{{ $equity_no }}</a></td>
                                <th class="  text-right">{{ create_money_format($pre_total_equity) }}</th>
                                <th class=" text-right">{{ create_money_format($total_equity) }}</th>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.equity_report').$parameter}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
                            </tr>

                            @php
                                $total_equity += $net_profit;
                                $pre_total_equity += $pre_net_profit;
                            @endphp
                            <tr>
                                <th class="padding-left-40" colspan="2"><a href="javascript:void(0)" data-toggle="modal" data-target="#net_profit_loss">{{__('common.net_profit_this_year')}}</a></th>
                                <td class="text-right">{{ create_money_format($pre_net_profit) }}</td>
                                <td class="text-right">{{ create_money_format($net_profit) }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.lost_profit').(!empty($parameter) ? $parameter."&type=print" : $parameter."?type=print")}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-uppercase border-top-1" colspan="2">{{__('common.total_equity')}}</th>
                                <th class="border-top-1 text-right">{{ create_money_format($pre_total_equity) }} </th>
                                <th class="border-top-1 text-right">{{ create_money_format($total_equity) }} </th>
                            </tr>
                            <tr>
                                <th class="text-uppercase" >B. <a href="javascript:void(0)" data-toggle="modal" data-target="#non_current_liabilities"> {{__('common.non_current_liabilities')}}</a></th>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#non_current_liabilities">{{ $non_current_liability_no }}</a></td>
                                <th class="text-right">{{ create_money_format($pre_total_non_current_liability) }} </th>
                                <th class="text-right">{{ create_money_format($total_non_current_liability) }} </th>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.long_term_liability_report').$parameter}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
                            </tr>

                            <tr>
                                <th class="text-uppercase" colspan="5">C. {{__('common.current_liabilities')}}</th>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#account_payable">{{__('common.account_payable')}}</a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#account_payable">{{ $account_payable_no }}</a></td>
                                <td class="text-right">{{ create_money_format($pre_total_account_payables) }}</td>
                                <td class="text-right">{{ create_money_format($total_account_payables) }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.account_payable_report').$parameter}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#sundry_creditors">{{__('common.sundry_creditors')}}</a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#sundry_creditors">{{ $sundry_creditor_no }}</a></td>
                                <td class="text-right">{{ create_money_format($pre_total_sundry_creditors) }}</td>
                                <td class="text-right">{{ create_money_format($total_sundry_creditors) }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.sundry_creditor_report').$parameter}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#overdraft_banks">{{__('common.bank_overdraft')}}</a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#overdraft_banks">{{ $over_bank_no }}</a></td>
                                <td class="text-right">{{ create_money_format($pre_total_over_bank) }}</td>
                                <td class="text-right">{{ create_money_format($total_over_bank) }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.bank_overdraft_report').$parameter}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#current_liabilities"> {{__('common.other_current_liabilities')}}</a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#current_liabilities">{{ $current_liability_no }}</a></td>
                                <td class="text-right">{{ create_money_format($pre_total_current_liability) }}</td>
                                <td class="text-right">{{ create_money_format($total_current_liability) }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.current_liability_report').$parameter}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#liabilities_expense">{{__('common.liabilities_for_expenses')}}</a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#liabilities_expense">{{ $liabilities_expenses_no }}</a></td>
                                <td class=" text-right">{{ ($pre_total_liabilities_expenses < 0 ? "(" : '').create_money_format( $pre_total_liabilities_expenses).($pre_total_liabilities_expenses < 0 ? ")" : '') }}</td>
                                <td class=" text-right">{{ ($total_liabilities_expenses < 0 ? "(" : '').create_money_format( $total_liabilities_expenses).($total_liabilities_expenses < 0 ? ")" : '') }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.liability_for_expense_report').$parameter}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="padding-left-40 text-capitalize" ><a href="javascript:void(0)" data-toggle="modal" data-target="#income_tax">{{__('common.income_tax_payable')}}  </a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#income_tax">{{ $income_tax_no }}</a></td>
                                <td class=" text-right">{{ ($pre_total_income_tax_payable < 0 ? "(" : '').create_money_format( $pre_total_income_tax_payable).($pre_total_income_tax_payable < 0 ? ")" : '') }}</td>
                                <td class=" text-right">{{ ($total_income_tax_payable < 0 ? "(" : '').create_money_format( $total_income_tax_payable).($total_income_tax_payable < 0 ? ")" : '') }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.income_tax_payable_report').$parameter}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#due_affiliated_no">{{__('common.due_to_affiliated_company')}} </a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#due_affiliated_no">{{ $due_affiliated_no }}</a></td>
                                <td class=" text-right">{{ ($pre_total_due_affiliated < 0 ? "(" : '').create_money_format( $pre_total_due_affiliated).($pre_total_due_affiliated < 0 ? ")" : '') }}</td>
                                <td class=" text-right">{{ ($total_due_affiliated < 0 ? "(" : '').create_money_format( $total_due_affiliated).($total_due_affiliated < 0 ? ")" : '') }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('member.report.due_to_affiliated_company_report').$parameter}}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </td>
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
                                <th class="text-uppercase border-top-1 padding-left-40" colspan="2">{{__('common.total_current_liabilities')}} </th>
                                <th class="border-top-1 text-right">
                                    {{ create_money_format($pre_total_liabilities) }}
                                </th>
                                <th class="border-top-1 text-right">
                                    {{ create_money_format($total_liabilities) }}
                                </th>
                            </tr>
                            <tr>
                                <th class="text-uppercase border-top-1 padding-left-40" colspan="2">{{__('common.difference_in_opening_balances')}} </th>
                                <th class="border-top-1 text-right">{{ create_money_format($pre_opening_balance) }} </th>
                                <th class="border-top-1 text-right">{{ create_money_format($opening_balance) }} </th>
                            </tr>

                            <tr>
                                <th class="text-uppercase border-top-1" colspan="2"> {{__('common.total_equity_and_liabilities')}} (A+B+C)</th>
                                <th class="border-top-1 text-right">{{ create_money_format($pre_total_e_laibility+$pre_opening_balance) }}</th>
                                <th class="border-top-1 text-right">{{ create_money_format($total_e_laibility+$opening_balance) }}</th>
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
        @if($set_company_fiscal_year)
            var $setDate = new Date( '{{ str_replace("-", "/", $set_company_fiscal_year->start_date) }}' );
            var today = new Date($setDate.getFullYear(), $setDate.getMonth(), $setDate.getDate(), 0, 0, 0, 0);
        @endif
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
        });

        $(".account_type_view").click( function (e) {
           e.preventDefault();

           var $view = $(this).data('id');
            $view.show();
        });


    });
</script>
@endpush

