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
    'name' => 'Balance Sheet',
    'title' => 'Balance Sheet',
    'heading' => 'Balance Sheet ',
];

?>

@extends('layouts.back-end.master', $data)

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush

@section('contents')
    <div class="row">
        <div class="col-xs-12">
            @include('common._alert')

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Search</h3>
                </div>

                {!! Form::open(['route' => ['member.report.balance_sheet'], 'method' => 'GET', 'role' => 'form']) !!}

                <div class="box-body">
                    <div class="row">
                        @if (Auth::user()->hasRole(['super-admin', 'developer']))
                            <div class="col-md-3">
                                <label> Select Company </label>
                                {!! Form::select('company_id', $companies, null, [
                                    'id' => 'company_id',
                                    'class' => 'form-control select2',
                                    'placeholder' => 'Select Company',
                                ]) !!}
                            </div>
                        @endif
                        <div class="col-md-3">
                            <label> Fiscal Year </label>
                            {!! Form::select('fiscal_year', $fiscal_year, null, [
                                'class' => 'form-control select2',
                                'placeholder' => 'Select All',
                            ]) !!}
                        </div>
                        <div class="col-md-2">
                            <label> Year </label>
                            <input class="form-control year" name="year" value="" autocomplete="off" />
                        </div>
                        <div class="col-md-2">
                            <label> From Date </label>
                            <input class="form-control date" name="from_date" value="" autocomplete="off" />
                        </div>
                        <div class="col-md-2">
                            <label> To Date</label>
                            <input class="form-control date" name="to_date" value="" autocomplete="off" />
                        </div>
                        <div class="col-md-2 margin-top-23">
                            <label></label>
                            <input class="btn btn-sm btn-info" value="Search" type="submit" />
                            <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i
                                    class="fa fa-refresh"></i> Reload</a>

                        </div>
                    </div>
                    <!-- /.row -->
                </div>

                {!! Form::close() !!}
            </div>

            <div class="box">

                @include('member.reports.print_title_btn')

                <div class="box-body balance_sheet">

                    <div class="col-lg-12">
                        <table class="table table-striped" id="dataTable">
                            <thead>

                                <tr>
                                    <th colspan="1" style="border: none !important; padding-bottom: 20px;"
                                        class="text-center">
                                        <h3>{{ $report_title }}</h3>
                                    </th>
                                </tr>
                            </thead>


                            <tbody>
                                <tr>
                                    <td class="text-uppercase report-head-tag border-1 "> particulars</td>
                                    {{--                                <td class="text-uppercase report-head-tag width-100 border-1 "> Notes</td> --}}
                                    <td class="text-uppercase report-head-tag text-right padding-right-50 border-1 "> Taka
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-uppercase text-underline" colspan="2"><u>Property AND Assets</u></th>
                                </tr>
                                <tr>
                                    <th class="text-uppercase" colspan="2">1. Fixed Assets</th>
                                </tr>
                                <tr>
                                    <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal"
                                            data-target="#fixed_assets"> Total Fixed </a></td>
                                    {{--                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#fixed_assets"> {{ $fixed_asset_no }} </a> </td> --}}
                                    <td class="text-right">
                                        {{ ($total_fixed < 0 ? '(' : '') . create_money_format($total_fixed) . ($total_fixed < 0 ? ')' : '') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-uppercase" colspan="2">2. Current Assets</th>
                                </tr>
                                <tr>
                                <tr>
                                    <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal"
                                            data-target="#inventory">Inventory</a></td>
                                    {{--                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#inventory">{{ $inventory_no }}</a></td> --}}
                                    <td class="text-right">{{ create_money_format($total_inventory) }}</td>
                                </tr>
                                <tr>
                                    <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal"
                                            data-target="#cash_and_banks">Cash & Bank</a></td>
                                    {{--                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#cash_and_banks">{{ $cash_bank_no }}</a></td> --}}
                                    <td class="text-right">{{ create_money_format($total_cash_bank) }}</td>
                                </tr>
                                <tr>
                                    <td class="padding-left-40"> <a href="javascript:void(0)" data-toggle="modal"
                                            data-target="#trade_debtors">Trade Debtors</a></td>
                                    {{--                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#trade_debtors">{{ $trade_debtors_no }}</a></td> --}}
                                    <td class="text-right">{{ create_money_format($total_trade_debtor) }}</td>
                                </tr>
                                <tr>
                                    <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal"
                                            data-target="#account_receivable">Account Receivable</a></td>
                                    {{--                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#account_receivable">{{ $account_receivable_no }}</a></td> --}}
                                    <td class="text-right">{{ create_money_format($total_account_receivables) }}</td>
                                </tr>
                                <tr>
                                    <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal"
                                            data-target="#current_assets">Others Current Assets</a></td>
                                    {{--                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#current_assets">{{ $current_asset_no }}</a></td> --}}
                                    <td class="text-right">{{ create_money_format($total_current_asset) }}</td>
                                </tr>
                                @php
                                    $total_current_asset = $total_inventory + $total_trade_debtor + $total_cash_bank + $total_account_receivables + $total_current_asset;
                                @endphp
                                <tr>
                                    <th class="padding-left-40 text-uppercase border-top-1" colspan="1">Total Current
                                        Assets</th>
                                    <th class="border-top-1 text-right">{{ create_money_format($total_current_asset) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-uppercase  border-top-1" colspan="1">Total Assets (1+2)</th>
                                    <th class=" border-top-1 text-right">
                                        {{ create_money_format($total_fixed + $total_current_asset) }}</th>
                                </tr>

                                <tr>
                                    <th class="text-uppercase text-underline" colspan="2"><u>Equity and Liabilities</u>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-uppercase" colspan="2">A. Proprietors' equity</th>
                                </tr>
                                @php
                                    $e_sum = 1;
                                @endphp
                                <tr>
                                    <th class="text-uppercase padding-left-40" colspan="1">1. equity</th>
                                    <td class="text-right">{{ create_money_format($equity_balance) }}</td>
                                </tr>

                                @php
                                    $total_equity = $equity_balance;
                                @endphp
                                @foreach ($equities as $key => $value)
                                    <tr>
                                        <th class="padding-left-40" colspan="1">{{ $key + 2 }}.
                                            {{ $value['account_type_name'] }}</th>
                                        <td class="text-right">{{ create_money_format($value['balance']) }}</td>
                                    </tr>
                                    @php
                                        $e_sum = $e_sum . '+' . ($key + 2);
                                        $total_equity += $value['balance'];
                                    @endphp
                                @endforeach
                                @php
                                    $total_equity += $net_profit;
                                @endphp
                                <tr>
                                    <th class="padding-left-40" colspan="1">Net Profit this year</th>
                                    <td class="text-right">{{ create_money_format($net_profit) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-uppercase border-top-1" colspan="1">Total Equity
                                        ({{ $e_sum }})</th>
                                    <th class="border-top-1 text-right">{{ create_money_format($total_equity) }} </th>
                                </tr>
                                <tr>
                                    <th class="text-uppercase">B. <a href="javascript:void(0)" data-toggle="modal"
                                            data-target="#non_current_liabilities">Non-current Liabilities</a></th>
                                    {{--                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#non_current_liabilities">{{ $non_current_liability_no }}</a></td> --}}
                                    <th class="text-right">{{ create_money_format($total_non_current_liability) }} </th>
                                </tr>

                                <tr>
                                    <th class="text-uppercase" colspan="1">C. current Liabilities</th>
                                </tr>
                                <tr>
                                    <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal"
                                            data-target="#account_payable">Account Payable</a></td>
                                    {{--                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#account_payable">{{ $account_payable_no }}</a></td> --}}
                                    <td class="text-right">
                                        {{ create_money_format($total_account_payables = $total_account_payables * -1) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal"
                                            data-target="#sundry_creditors">Sundry Creditors</a></td>
                                    {{--                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#sundry_creditors">{{ $sundry_creditor_no }}</a></td> --}}
                                    <td class="text-right">
                                        {{ create_money_format($total_sundry_creditors = $total_sundry_creditors * -1) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal"
                                            data-target="#overdraft_banks">Bank Overdraft</a></td>
                                    {{--                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#overdraft_banks">{{ $over_bank_no }}</a></td> --}}
                                    <td class="text-right">
                                        {{ create_money_format($total_over_bank = $total_over_bank * -1) }}</td>
                                </tr>
                                <tr>
                                    <td class="padding-left-40"><a href="javascript:void(0)" data-toggle="modal"
                                            data-target="#current_liabilities">Other Liabilities</a></td>
                                    {{--                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#current_liabilities">{{ $current_liability_no }}</a></td> --}}
                                    <td class="text-right">{{ create_money_format($total_current_liability) }}</td>
                                </tr>
                                @php
                                    $total_liabilities = $total_account_payables + $total_sundry_creditors + $total_over_bank + $total_current_liability;
                                @endphp
                                <tr>
                                    <th class="text-uppercase border-top-1 padding-left-40" colspan="1">Total Current
                                        Liabilities</th>
                                    <th class="border-top-1 text-right">{{ create_money_format($total_liabilities) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-uppercase border-top-1" colspan="1"> Total Equity & Liabilities
                                        (A+B+C)</th>
                                    <th class="border-top-1 text-right">
                                        {{ create_money_format($total_equity + $total_non_current_liability + $total_liabilities) }}
                                    </th>
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
        $(document).ready(function() {
            $('.select2').select2();
            $('.date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });
            @if ($set_company_fiscal_year)
                var $setDate = new Date('{{ str_replace('-', '/', $set_company_fiscal_year->start_date) }}');
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

            $('.date').change(function(e) {
                $('.date').attr('required', true);
            });

            $(".account_type_view").click(function(e) {
                e.preventDefault();

                var $view = $(this).data('id');
                $view.show();
            });


        });
    </script>
@endpush
