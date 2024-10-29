<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/11/2019
 * Time: 2:01 PM
 */
 $route = \Auth::user()->can(['member.report.trail_balance_v2']) ?route('member.report.trail_balance_v2'): '#';
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
        'name' => 'Trail Balance',
    ],
];

$data['data'] = [
    'name' => "Trail Balance",
    'title'=> 'Trail Balance',
    'heading' => 'Trail Balance ',
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
$expense_no  = $balance_sheet_key++;
$income_no  = $balance_sheet_key++;
$equity_no  = $balance_sheet_key++;
$total_debit  = 0;
$total_credit  = 0;
?>
@extends('layouts.back-end.master', $data)

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">

    <style>
        .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
            vertical-align: middle !important;
        }
    </style>
@endpush

@section('contents')

    <div class="row">
        <div class="col-xs-12">
            @include('common._alert')

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Search</h3>
                </div>

                {!! Form::open(['route' => ['member.report.trail_balance_v2'],'method' => 'GET', 'role'=>'form' ]) !!}

                <div class="box-body">
                    <div class="row">
                        @if(Auth::user()->hasRole(['super-admin', 'developer']))
                            <div class="col-md-3">
                                <label>  Select Company </label>
                                {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>'Select Company']); !!}
                            </div>
                        @endif
                        <div class="col-md-3">
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
                            <input type="hidden" name="search" value="1" />
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
                <div class="box-header with-border">
                    <a href="{{ route(Route::current()->getName()) == $full_url ? $full_url."?" : $full_url."&" }}type=print_details" class="btn btn-primary  btn-sm  pull-right" id="btn-print"> <i class="fa fa-print"></i> Print Details </a>
                </div>

                <div class="box-body balance_sheet">

                    @if($search)
                        @include('member.reports.common._modal_trail_balance')
                    @endif

                    <div class="col-lg-12">
                        <table class="table table-striped" id="dataTable">
                            <thead>

                            <tr>
                                <th colspan="6" style="border: none !important; padding-bottom: 20px;" class="text-center">
                                    <h3>{!!  $report_title !!} </h3>
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td rowspan="2" class="text-uppercase report-head-tag border-1 "> particulars</td>
                                <td colspan="2" class="text-uppercase report-head-tag text-center  border-1 pt-2"> Opening Balance  </td>
                                <td  colspan="2" class="text-uppercase report-head-tag text-center  border-1 "> Transaction </td>
                                <td  rowspan="2"class="text-uppercase report-head-tag text-center  border-1 "> Closing Balance </td>
                            </tr>
                            <tr>
                                <td class="text-uppercase report-head-tag text-center  border-1 "> Dr </td>
                                <td class="text-uppercase report-head-tag text-center  border-1 "> CR </td>
                                <td class="text-uppercase report-head-tag text-center  border-1 "> Dr </td>
                                <td class="text-uppercase report-head-tag text-center  border-1 "> CR </td>
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
                                <th class="text-uppercase" colspan="6"> Fixed Assets</th>
                            </tr>
                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#fixed_assets">  Total Fixed </a></td>

                                @if($pre_total_fixed>0)
                                <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_fixed) }}</td>
                                    <td></td>
                                @else
                                    <td></td>
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_fixed) }}</td>
                                @endif

                                <td class="text-right">{{ create_money_format( $fixed_total_dr) }}</td>
                                <td class="text-right">{{ create_money_format( $fixed_total_cr) }}</td>
                                <td class="text-right">{{ create_money_format_with_dr_cr( $total_fixed) }}</td>
                            </tr>
                            <tr>
                                <th class="text-uppercase" colspan="6"> Current Assets  </th>
                            </tr>
                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#cash_and_banks">Cash & Bank</a></td>

                                @if($pre_total_cash_bank>0)
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_cash_bank) }}</td>
                                    <td></td>
                                @else
                                    <td></td>
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_cash_bank) }}</td>
                                @endif
                                <td class="text-right">{{ create_money_format($cash_banks_total_dr) }}</td>
                                <td class="text-right">{{ create_money_format($cash_banks_total_cr) }}</td>
                                <td class="text-right">{{ create_money_format_with_dr_cr($total_cash_bank) }}</td>
                            </tr>

                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#trade_debtors">Trade Debtors</a></td>

                                @if($pre_total_trade_debtor>0)
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_trade_debtor) }}</td>
                                    <td></td>
                                @else
                                    <td></td>
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_trade_debtor) }}</td>
                                @endif
                                <td class=" text-right">{{ create_money_format( $trade_debtors_total_dr) }}</td>
                                <td class=" text-right">{{ create_money_format( $trade_debtors_total_cr) }}</td>
                                <td class="text-right">{{ create_money_format_with_dr_cr($total_trade_debtor) }}</td>
                            </tr>

                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal"  data-target="#account_receivable">Account Receivable</a></td>

                                @if($pre_total_account_receivables>0)
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_account_receivables) }}</td>
                                    <td></td>
                                @else
                                    <td></td>
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_account_receivables) }}</td>
                                @endif
                                <td class=" text-right">{{ create_money_format( $account_receivables_total_dr) }}</td>
                                <td class=" text-right">{{ create_money_format( $account_receivables_total_cr) }}</td>
                                <td class="text-right">{{ create_money_format_with_dr_cr($total_account_receivables) }}</td>
                            </tr>

                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#advance_prepayment">Advance Deposits & Prepayments</a></td>

                                @if($pre_total_advance_prepayment>0)
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_advance_prepayment) }}</td>
                                    <td></td>
                                @else
                                    <td></td>
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_advance_prepayment) }}</td>
                                @endif
                                <td class=" text-right">{{ create_money_format( $advance_prepayments_total_dr) }}</td>
                                <td class=" text-right">{{ create_money_format( $advance_prepayments_total_cr) }}</td>
                                <td class=" text-right">{{ create_money_format_with_dr_cr( $total_advance_prepayment) }}</td>
                            </tr>

                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#due_companies">Due from Affiliated Company</a></td>

                                @if($pre_total_due_affiliated_company>0)
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_due_affiliated_company) }}</td>
                                    <td></td>
                                @else
                                    <td></td>
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_due_affiliated_company) }}</td>
                                @endif
                                <td class=" text-right">{{ create_money_format( $due_companies_total_dr) }}</td>
                                <td class=" text-right">{{ create_money_format( $due_companies_total_cr) }}</td>
                                <td class=" text-right">{{ create_money_format_with_dr_cr( $total_due_affiliated_company) }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#fixed_deposits_receipt">Fixed Deposits Receipts</a></td>

                                @if($pre_total_fixed_deposits_receipt>0)
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_fixed_deposits_receipt) }}</td>
                                    <td></td>
                                @else
                                    <td></td>
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_fixed_deposits_receipt) }}</td>
                                @endif

                                <td class=" text-right">{{ create_money_format( $fixed_deposits_receipts_total_dr) }}</th>
                                <td class=" text-right">{{ create_money_format( $fixed_deposits_receipts_total_cr) }}</td>

                                <td class=" text-right">{{ create_money_format_with_dr_cr( $total_fixed_deposits_receipt) }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#current_assets">Other Current Assets </a></td>

                                @if($pre_total_current_asset>0)
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_current_asset) }}</td>
                                    <td></td>
                                @else
                                    <td></td>
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_current_asset) }}</td>
                                @endif
                                <td class=" text-right">{{ create_money_format( $current_assets_total_dr) }}</td>
                                <td class=" text-right">{{ create_money_format( $current_assets_total_cr) }}</td>

                                <td class=" text-right">{{ create_money_format_with_dr_cr( $total_current_asset) }}</td>
                            </tr>
                            <tr>
                                <th class="text-uppercase" colspan="6"> Equity and Liabilities  </th>
                            </tr>
                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#equity">Equity</a>
                                </td>

                                @if($pre_total_equity>0)
                                    <td></td>
                                    <td class="text-right">{{ create_money_format_with_dr_cr( $pre_total_equity) }}</td>
                                @else
                                    <td class="text-right">{{ create_money_format_with_dr_cr( $pre_total_equity) }}</td>
                                    <td></td>
                                @endif
                                <td class=" text-right">{{ create_money_format( $equities_total_dr) }}</td>
                                <td class=" text-right">{{ create_money_format( $equities_total_cr) }}</td>
                                <td class="text-right">{{ create_money_format_with_dr_cr( $total_equity) }}</td>
                            </tr>
                            <tr>
                                <th class="text-uppercase" colspan="6"> Liabilities  </th>
                            </tr>
                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#non_current_liabilities">Non-current Liabilities</a></td>


                                @if($pre_total_non_current_liability>0)
                                    <td></td>
                                    <td class="text-right">{{ create_money_format_with_dr_cr($pre_total_non_current_liability*(-1)) }} </td>
                                @else
                                    <td class="text-right">{{ create_money_format_with_dr_cr($pre_total_non_current_liability*(-1)) }} </td>
                                    <td></td>
                                @endif

                                <td class=" text-right">{{ create_money_format( $non_current_liabilities_total_dr) }}</td>
                                <td class=" text-right">{{ create_money_format( $non_current_liabilities_total_cr) }}</td>

                                <td class="text-right">{{ create_money_format_with_dr_cr($total_non_current_liability*(-1)) }} </td>
                            </tr>


                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#account_payable">Account Payable</a></td>


                                @if($pre_total_account_payables>0)
                                    <td class="text-right">{{ create_money_format_with_dr_cr($pre_total_account_payables*(-1)) }}</td>
                                    <td></td>
                                @else
                                    <td></td>
                                    <td class="text-right">{{ create_money_format_with_dr_cr($pre_total_account_payables*(-1)) }}</td>
                                @endif
                                <td class=" text-right">{{ create_money_format( $account_payables_total_dr) }}</td>
                                <td class=" text-right">{{ create_money_format( $account_payables_total_cr) }}</td>
                                <td class="text-right">{{ create_money_format_with_dr_cr($total_account_payables*(-1)) }}</td>
                            </tr>

                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#sundry_creditors">Sundry Creditors</a></td>


                                @if($pre_total_sundry_creditors>0)
                                    <td></td>
                                    <td class="text-right">{{ create_money_format_with_dr_cr($pre_total_sundry_creditors*(-1)) }}</td>
                                @else
                                    <td class="text-right">{{ create_money_format_with_dr_cr($pre_total_sundry_creditors*(-1)) }}</td>
                                    <td></td>
                                @endif
                                <td class=" text-right">{{ create_money_format( $sundry_creditors_total_dr) }}</td>
                                <td class=" text-right">{{ create_money_format( $sundry_creditors_total_cr) }}</td>
                                <td class="text-right">{{ create_money_format_with_dr_cr($total_sundry_creditors*(-1)) }}</td>
                            </tr>

                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#overdraft_banks">Bank Overdraft</a></td>


                                @if($pre_total_over_bank>0)
                                    <td></td>
                                    <td class="text-right">{{ create_money_format_with_dr_cr($pre_total_over_bank*(-1)) }}</td>
                                @else
                                    <td class="text-right">{{ create_money_format_with_dr_cr($pre_total_over_bank*(-1)) }}</td>
                                    <td></td>
                                @endif
                                <td class=" text-right">{{ create_money_format( $over_banks_total_dr) }}</td>
                                <td class=" text-right">{{ create_money_format( $over_banks_total_cr) }}</td>
                                <td class="text-right">{{ create_money_format_with_dr_cr($total_over_bank*(-1)) }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#current_liabilities">Other Current Liabilities</a></td>


                                @if($pre_total_current_liability>0)
                                    <td></td>
                                    <td class="text-right">{{ create_money_format_with_dr_cr($pre_total_current_liability*(-1)) }}</td>
                                @else
                                    <td class="text-right">{{ create_money_format_with_dr_cr($pre_total_current_liability*(-1)) }}</td>
                                    <td></td>
                                @endif
                                <td class=" text-right">{{ create_money_format( $current_liabilities_total_dr) }}</td>
                                <td class=" text-right">{{ create_money_format( $current_liabilities_total_cr) }}</td>
                                <td class="text-right">{{ create_money_format_with_dr_cr($total_current_liability*(-1)) }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#liabilities_expense"> Liabilities For Expenses</a></td>


                                @if($pre_total_liabilities_expenses>0)
                                    <td></td>
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_liabilities_expenses*(-1)) }}</td>
                                @else
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_liabilities_expenses*(-1)) }}</td>
                                    <td></td>
                                @endif
                                <td class=" text-right">{{ create_money_format( $liabilities_expenses_total_dr) }}</td>
                                <td class=" text-right">{{ create_money_format( $liabilities_expenses_total_cr) }}</td>
                                <td class=" text-right">{{ create_money_format_with_dr_cr( $total_liabilities_expenses*(-1)) }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#income_tax">  Income tax payable </a></td>


                                @if($pre_total_income_tax_payable>0)
                                    <td></td>
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_income_tax_payable*(-1)) }}</td>
                                @else
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_income_tax_payable*(-1)) }}</td>
                                    <td></td>
                                @endif

                                <td class=" text-right">{{ create_money_format( $income_tax_payables_total_dr) }}</td>
                                <td class=" text-right">{{ create_money_format( $income_tax_payables_total_cr) }}</td>

                                <td class=" text-right">{{ create_money_format_with_dr_cr( $total_income_tax_payable*(-1)) }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#due_affiliated_no"> Due to Affiliated Company </a></td>


                                @if($pre_total_due_affiliated>0)
                                    <td></td>
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_due_affiliated*(-1)) }}</td>
                                @else
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_due_affiliated*(-1)) }}</td>
                                    <td></td>
                                @endif
                                <td class=" text-right">{{ create_money_format( $due_to_affiliated_companies_total_dr) }}</td>
                                <td class=" text-right">{{ create_money_format( $due_to_affiliated_companies_total_cr) }}</td>
                                <td class=" text-right">{{ create_money_format_with_dr_cr( $total_due_affiliated*(-1)) }}</td>
                            </tr>
                            <tr>
                                <th class="text-uppercase" colspan="6"> Income and Expense  </th>
                            </tr>
                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#incomes"> Incomes </a></td>
{{--                                <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_incomes) }}</td> --}}
                                <td class=" text-right" colspan="2"></td>
                                <td class=" text-right">{{ create_money_format( $income_total_dr) }}</td>
                                <td class=" text-right">{{ create_money_format( $income_total_cr) }}</td>
                                <td class=" text-right">{{ create_money_format_with_dr_cr( $total_incomes*(-1)) }}</td>
                            </tr>

                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#expenses"> Expenses </a></td>
                                <td class=" text-right" colspan="2"></td>
{{--                                <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_expenses) }}</td>--}}
                                <td class=" text-right">{{ create_money_format( $expense_total_dr) }}</td>
                                <td class=" text-right">{{ create_money_format( $expense_total_cr) }}</td>
                                <td class=" text-right">{{ create_money_format_with_dr_cr( $total_expenses) }}</td>
                            </tr>

                            <tr>
                                <th class="text-uppercase" colspan="6"> Sale and Purchase  </th>
                            </tr>
                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)"> Sales </a></td>

                                @if($pre_total_sales>0)
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_sales) }}</td>
                                    <td></td>
                                @else
                                    <td></td>
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_sales) }}</td>
                                @endif

                                <td class=" text-right">{{ create_money_format( $sales_total_dr) }}</td>
                                <td class=" text-right">{{ create_money_format( $sales_total_cr) }}</td>
                                <td class=" text-right">{{ create_money_format_with_dr_cr($total_sales) }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40"><a href="javascript:void(0)" > Purchases </a></td>


                                @if($pre_total_purchases>0)
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_purchases) }}</td>
                                    <td></td>
                                @else
                                    <td></td>
                                    <td class=" text-right">{{ create_money_format_with_dr_cr( $pre_total_purchases) }}</td>
                                @endif

                                <td class=" text-right">{{ create_money_format( $purchases_total_dr) }}</td>
                                <td class=" text-right">{{ create_money_format( $purchases_total_cr) }}</td>
                                <td class=" text-right">{{ create_money_format_with_dr_cr($total_purchases) }}</td>
                            </tr>
                            @if(count($no_parents)>0)

                            <tr>
                                <th class="text-uppercase" colspan="6"> Undefined parent Ledger  </th>
                            </tr>
                            @endif

                            @foreach($no_parents as $value)

                                <tr>
                                    <td class="padding-left-40">{{ $value['account_type_name'] }}</td>

                                    @if($value['pre_balance']>0)
                                        <td class=" text-right">{{ create_money_format_with_dr_cr( $value['pre_balance']) }}</td>
                                        <td></td>
                                    @else
                                        <td></td>
                                        <td class=" text-right">{{ create_money_format_with_dr_cr( $value['pre_balance']) }}</td>
                                    @endif
                                    <td class="text-right ">{{ $value['total_dr']>0 ? create_money_format( $value['total_dr'] ) : "" }}</td>
                                    <td class="text-right">{{ $value['total_cr']>0 ? create_money_format( $value['total_cr'] ) : "" }}</td>
                                    <td class="text-right">{{ create_money_format_with_dr_cr( $value['balance'] ) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th > Grand  Total</th>
                                <th class=" text-right" colspan="2">{{ create_money_format_with_dr_cr($total_opening) }}</th>
                                <th class="dual-line  text-right">{{ create_money_format($total_dr) }}</th>
                                <th class="dual-line  text-right">{{ create_money_format($total_cr) }}</th>
                                <th class=" text-right">{{ create_money_format_with_dr_cr($total_closing) }}</th>
                            </tr>
@endif

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

