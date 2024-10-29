<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/17/2019
 * Time: 1:03 PM
 */

 $route = \Auth::user()->can(['member.report.cash_book']) ? route('member.report.cash_book'): '#';
 $home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Sheets',
        'href' => $route,
    ],
    [
        'name' => 'Cash Book Report',
    ],
];

$data['data'] = [
    'name' => 'Cash Book Report',
    'title'=> 'Cash Book Report',
    'heading' => 'Cash Book Report',
];

?>
@extends('layouts.back-end.master', $data)

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush

@section('contents')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Search</h3>
                </div>

            {!! Form::open(['route' => 'member.report.daily_sheet','method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        @if(Auth::user()->hasRole(['super-admin', 'developer']))
                            <div class="col-md-3">
                                <label>  Select Company </label>
                                {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>'Select Company']); !!}
                            </div>
                        @endif
                        <div class="col-md-3">
                            <label> From Date </label>
                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-3">
                            <label> To Date</label>
                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-3 margin-top-23">
                            <label></label>
                            <input class="btn btn-info" value="Search" type="submit"/>
                            <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Reload</a>

                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                {{--<div class="box-body">--}}

                {{----}}
                {{--</div>--}}

                {!! Form::close() !!}
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Cash Book Report</h3>
                    <a href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=print" class="btn btn-sm btn-primary pull-right"  id="btn-print"> <i class="fa fa-print"></i> Print </a>
                    <a href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=download" class="btn btn-sm btn-success pull-right"> <i class="fa fa-download"></i> Download </a>
                </div>

                <div class="box-body">

                    <div class="col-lg-6">

                        <table class="table table-striped" id="dataTable">
                            <tbody>
                            <tr>
                                <th colspan="7" class="text-center border-right-1"> -------- Received --------</th>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <th>Vr. No</th>
                                <th>Received</th>
                                <th>Folio</th>
                                <th class="border-right-1 text-right">Cash </th>
                                <th class="border-right-1 text-right">Bank</th>
                                <th class="border-right-1 text-right">Total</th>
                            </tr>
                            @php
                                $cash_amount = $bank_amount = $transaction_amount = 0;
                            @endphp
                            @foreach($incomes as $key=>$value)
                                @php
                                    $cash_amount += $value->cash_amount;
                                    $bank_amount += $value->bank_amount;
                                    $transaction_amount +=$value->transaction_amount;
                                @endphp
                                <tr>
                                    <td>{{ db_date_month_year_format($value->date) }}</td>
                                    <td>{{ $value->transaction_id }}</td>
                                    <td>{{ $value->account_name }}</td>
                                    <td>{{ $value->full_name }}</td>
                                    <td class="border-right-1 text-right">
                                        {{ create_money_format($value->cash_amount) }}
                                    </td>
                                    <td class="border-right-1 text-right">
                                        {{ create_money_format($value->bank_amount) }}
                                    </td>
                                    <td class="border-right-1 text-right">
                                        {{ create_money_format($value->transaction_amount) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="">
                                <th colspan="4">Total</th>
                                <th class="dual-line text-right">{{ create_money_format($cash_amount) }}</th>
                                <th class="dual-line text-right">{{ create_money_format($bank_amount) }}</th>
                                <th class="dual-line text-right">{{ create_money_format($transaction_amount) }}</th>
                            </tr>
                            </tbody>
                        </table>

                    </div>

                    <div class="col-lg-6">
                        <table class="table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th colspan="7" class="text-center border-right-1"> ------- Payment -------</th>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <th>Vr. No</th>
                                <th>Payment</th>
                                <th>Folio</th>
                                <th class="border-right-1 text-right">Cash </th>
                                <th class="border-right-1 text-right">Bank</th>
                                <th class="border-right-1 text-right">Total</th>
                            </tr>
                            @php
                                $cash_amount = $bank_amount = $transaction_amount = 0;
                            @endphp
                            @foreach($expenses as $key=>$value)
                                @php
                                    $cash_amount += $value->cash_amount;
                                    $bank_amount += $value->bank_amount;
                                    $transaction_amount +=$value->transaction_amount;
                                @endphp
                                <tr>
                                    <td>{{ db_date_month_year_format($value->date) }}</td>
                                    <td>{{ $value->transaction_id }}</td>
                                    <td>{{ $value->account_name }}</td>
                                    <td>{{ $value->full_name }}</td>
                                    <td class="border-right-1 text-right">
                                        {{ create_money_format($value->cash_amount) }}
                                    </td>
                                    <td class="border-right-1 text-right">
                                        {{ create_money_format($value->bank_amount) }}
                                    </td>
                                    <td class="border-right-1 text-right">
                                        {{ create_money_format($value->transaction_amount) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="4">Total</th>
                                <th class="dual-line text-right">{{ create_money_format($cash_amount) }}</th>
                                <th class="dual-line text-right">{{ create_money_format($bank_amount) }}</th>
                                <th class="dual-line text-right">{{ create_money_format($transaction_amount) }}</th>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-12 text-right">
                        {{--{{ $reports->links() }}--}}
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
        });
    </script>
@endpush

