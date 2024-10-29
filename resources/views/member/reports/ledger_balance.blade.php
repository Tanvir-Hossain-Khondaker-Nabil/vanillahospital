<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 16-May-20
 * Time: 6:21 PM
 */
 $route = \Auth::user()->can(['member.report.list']) ? route('member.report.list'): '#';
 $home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Reports',
        'href' => $route,
    ],
    [
        'name' => $report_title,
    ],
];

$data['data'] = [
    'name' => $report_title,
    'title'=> $report_title,
    'heading' => $report_title,
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

            {!! Form::open(['route' => 'member.report.ledger_balance_report','method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
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
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-3 margin-top-23">
                                <input type="checkbox" name="opening_balance" value="1" {{ $opening_balance ? "checked" : "" }}/>
                                <label> With Opening Balance </label>
                            </div>
                            <div class="col-md-4 margin-top-23">
                                <input type="checkbox" name="total_dr_Cr" value="1" {{ $total_dr_Cr ? "checked" : "" }} />
                                <label> Transaction Total Debit/Credit Balance </label>
                            </div>
                        </div>

                        <div class="col-md-12 margin-top-23">
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
                    <h3 class="box-title">{{ $report_title }}</h3>
                    <a href="{{  $full_url }}type=print" class="btn btn-sm btn-primary pull-right" id="btn-print"> <i class="fa fa-print"></i> Print </a>
                    <a href="{{  $full_url }}type=download" class="btn btn-sm btn-success pull-right"> <i class="fa fa-download"></i> Download </a>
                </div>

                <div class="box-body">

                    <div class="col-lg-12">
                        <table class="table table-striped table-bordered" id="dataTable">

                            <thead>
                            <tr>
                                <th rowspan="2" width="20px"  >ID</th>
                                <th rowspan="2" width="20px"  >GL Code</th>
                                <th rowspan="2" width="300px"  >Particulars</th>
                                @if($opening_balance)<th class="text-right"  rowspan="2">Opening Balance</th>@endif
                                @if($total_dr_Cr)
                                    <th colspan="2" class="text-center"> Transactions</th>
                                @endif
                                <th class="text-right" rowspan="2">Closing Balance</th>
                                <th class="text-center"  rowspan="2" width="120px">Last Payment Date</th>
                            </tr>
                            <tr>
                                @if($total_dr_Cr)
                                    <th class="text-center">Debit</th>
                                    <th class="text-center">Credit</th>
                                @endif
                            </tr>

                            </thead>
                            <tbody>
                            @foreach($modal as $key => $value)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value->id }}</td>
                                    <td>{{ $value->display_name }}</td>
                                    {{--                                    <td>{{ $value->address }}</td>--}}
                                    @if($opening_balance)<td class="text-right">{{ $value->opening_balance != 0 ? create_money_format($value->opening_balance) : "" }}</td>@endif
                                    @if($total_dr_Cr)
                                        <td class="text-right">{{ $value->total_dr>0 ? create_money_format($value->total_dr) : "" }}</td>
                                        <td class="text-right">{{ $value->total_cr>0 ? create_money_format($value->total_cr) : "" }}</td>
                                    @endif
                                    <td class="text-right">{{ $value->closing_balance }}</td>

                                    <td class="text-center">{{ db_date_month_year_format($value->last_payment_date) }}</td>
                                </tr>
                            @endforeach
                            </thead>
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
        });
    </script>
@endpush
