<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 25-Mar-20
 * Time: 5:11 PM
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
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-3 margin-top-23">
                                <input type="checkbox" name="t_based_view" value="1" {{ $t_based_view ? "checked" : "" }}/>
                                <label> T Based View </label>
                            </div>

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

                    <a href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=full_balance_sheet" class="btn btn-info  btn-sm  pull-right" id="btn-print"> <i class="fa fa-print"></i> Print Balance Sheet Details </a>
                    <a href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=download_full_balance_sheet" class="btn btn-default  btn-sm  pull-right  mr-3"> <i class="fa fa-download"></i> Download Balance Sheet Details</a>
                </div>


                <div class="box-body balance_sheet">

                   @include('member.reports.common._modal_balance_sheet')

                    <div class="col-lg-12 text-center">
                        <h3>{!!  $report_title !!} </h3>
                    </div>

                    <div class="col-lg-6">
                        <table class="table table-striped" id="dataTable">
                            <tbody>
                            <tr>
                                <td class="text-uppercase report-head-tag border-1 "> particulars</td>
                                <td class="text-uppercase report-head-tag text-right padding-right-50 border-1 "> Taka</td>
                            </tr>

                            <tr>
                                <th class="text-uppercase text-underline" colspan="2"><u>Equity and Liabilities</u></th>
                            </tr>
                            <tr>
                                <th class="text-uppercase" colspan="2">A. Proprietors' equity</th>
                            </tr>
                            @php
                                $e_sum = 1;
                            @endphp
                            <tr>
                                <th class="text-uppercase padding-left-40">1. equity</th>
                                <td class="text-right">{{ create_money_format($equity_balance) }}</td>
                            </tr>

                            @php
                                $total_equity = $equity_balance;
                            @endphp
                            @foreach($equities as $key => $value)
                                <tr>
                                    <th class="padding-left-40">{{ $key+2 }}. {{ $value['account_type_name'] }}</th>
                                    <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                                </tr>
                                @php
                                    $e_sum = $e_sum."+".($key+2);
                                    $total_equity += $value['balance'];
                                @endphp
                            @endforeach
                            @php
                                $total_equity += $net_profit;

                                $total_liabilities = $total_account_payables+$total_sundry_creditors+$total_over_bank+$total_current_liability;
                                $total_current_asset = $total_inventory+$total_trade_debtor+$total_cash_bank+$total_account_receivables+$total_current_asset+$total_advance_prepayment+$total_due_affiliated_company+$total_fixed_deposits_receipt;
                                 $total_e_laibility = $total_equity+$total_non_current_liability+$total_liabilities;
                                $total_assets = $total_fixed+$total_current_asset;
                                $opening_balance = $total_assets-$total_e_laibility;
                            @endphp
                            <tr>
                                <th class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal" data-target="#net_profit_loss"> Net Profit this year</a></th>
                                <td class="text-right">{{ create_money_format($net_profit) }}</td>
                            </tr>
                            <tr>
                                <th class="text-uppercase border-top-1" >Total Equity ({{$e_sum}})</th>
                                <th class="border-top-1 text-right">{{ create_money_format($total_equity) }} </th>
                            </tr>
                            <tr>
                                <th class="text-uppercase" >B. <a href="javascript:void(0)" data-toggle="modal" data-target="#non_current_liabilities">Non-current Liabilities</a></th>
                                <th class="text-right">{{ create_money_format($total_non_current_liability) }} </th>
                            </tr>

                            <tr>
                                <th class="text-uppercase" colspan="3">C. current Liabilities</th>
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
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#current_liabilities">Other Current Liabilities</a></td>
                                <td class="text-right">{{ create_money_format($total_current_liability) }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#liabilities_expense"> Liabilities For Expenses</a></td>
                                <td class=" text-right">{{ ($total_liabilities_expenses < 0 ? "(" : '').create_money_format( $total_liabilities_expenses).($total_liabilities_expenses < 0 ? ")" : '') }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40 text-capitalize" ><a href="javascript:void(0)" data-toggle="modal" data-target="#income_tax"> Total income tax payable </a></td>
                                <td class=" text-right">{{ ($total_income_tax_payable < 0 ? "(" : '').create_money_format( $total_income_tax_payable).($total_income_tax_payable < 0 ? ")" : '') }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#due_affiliated_no"> Due to Affiliated Company </a></td>
                                <td class=" text-right">{{ ($total_due_affiliated < 0 ? "(" : '').create_money_format( $total_due_affiliated).($total_due_affiliated < 0 ? ")" : '') }}</td>
                            </tr>
                            <tr>
                                <th class="text-uppercase border-top-1 padding-left-40">Total Current Liabilities</th>
                                <th class="border-top-1 text-right">{{ create_money_format($total_liabilities) }} </th>
                            </tr>
                            <tr>
                                <th class="text-uppercase border-top-1 padding-left-40">Diff. in Opening Balances</th>
                                <th class="border-top-1 text-right">{{ create_money_format($opening_balance) }} </th>
                            </tr>
                            <tr>
                                <th class="text-uppercase border-top-1" > Total Equity & Liabilities (A+B+C)</th>
                                <th class="border-top-1 text-right">{{ create_money_format($total_e_laibility+$opening_balance) }}</th>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                    <div class="col-lg-6">
                        <table class="table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <td class="text-uppercase report-head-tag border-1 "> particulars</td>
                                <td class="text-uppercase report-head-tag text-right padding-right-50 border-1 "> Taka</td>
                            </tr>
                            <tr>
                                <th class="text-uppercase text-underline" colspan="2"><u>Property AND Assets</u></th>
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
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#advance_prepayment">Advance Deposits & Prepayments</a></td>
                                <td class=" text-right">{{ ($total_advance_prepayment < 0 ? "(" : '').create_money_format( $total_advance_prepayment).($total_advance_prepayment < 0 ? ")" : '') }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#due_companies">Due from Affiliatted Company</a></td>
                                <td class=" text-right">{{ ($total_due_affiliated_company < 0 ? "(" : '').create_money_format( $total_due_affiliated_company).($total_due_affiliated_company < 0 ? ")" : '') }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#fixed_deposits_receipt">Fixed Deposits Receipts</a></td>
                                <td class=" text-right">{{ ($total_fixed_deposits_receipt < 0 ? "(" : '').create_money_format( $total_fixed_deposits_receipt).($total_fixed_deposits_receipt < 0 ? ")" : '') }}</td>
                            </tr>
                            @foreach($current_assets as $key=>$value)
                                <tr>
                                    <td class=" {{ isset($value['child']) && $value['child'] == 'yes' ? 'padding-left-70 ' : 'padding-left-40'  }} {{ isset($value['parent']) && $value['parent'] == 'yes' ? ' text-bold ' : '' }}" >{{ $value['account_type_name'] }}</td>
                                    <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                                </tr>
                            @endforeach
{{--                            <tr>--}}
{{--                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#current_assets">Others Current Assets</a></td>--}}
{{--                                <td class="text-right">{{ create_money_format($total_current_asset) }}</td>--}}
{{--                            </tr>--}}
                            <tr>
                                <th class="padding-left-40 text-uppercase border-top-1" >Total Current Assets</th>
                                <th class="border-top-1 text-right">{{ create_money_format($total_current_asset) }} </th>
                            </tr>
                            <tr>
                                <th class="text-uppercase  border-top-1">Total Assets (1+2)</th>
                                <th class=" border-top-1 text-right">{{ create_money_format($total_assets) }}</th>
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

