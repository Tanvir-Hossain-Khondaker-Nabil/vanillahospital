<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/7/2020
 * Time: 2:31 PM
 */
 $route = \Auth::user()->can(Route::current()->getName()) ? route(Route::current()->getName()): '#';
 $home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => "Ledger Book By Manager",
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => "Ledger Book By Manager",
    'title'=> "Ledger Book By Manager",
    'heading' => "Ledger Book By Manager",
];

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

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>

            {!! Form::open(['route' => 'member.report.ledger_book_by_manager','method' => 'GET', 'role'=>'form' ]) !!}
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
                            <label>  Managers </label>
                            {!! Form::select('manager_id', $managers, null,['id'=>'manager_id','class'=>'form-control select2','placeholder'=>'Select All', 'required']); !!}
                        </div>
{{--                        <div class="col-md-3">--}}
{{--                            <label>  Select Division </label>--}}
{{--                            {!! Form::select('division_id', $divisions, null,['id'=>'division_id','class'=>'form-control select2','placeholder'=>'Select Division']); !!}--}}
{{--                        </div>--}}
{{--                        <div class="col-md-3">--}}
{{--                            <label>  Select District </label>--}}
{{--                            {!! Form::select('district_id', $districts, null,['id'=>'district_id','class'=>'form-control select2','placeholder'=>'Select District']); !!}--}}
{{--                        </div>--}}
{{--                        <div class="col-md-3">--}}
{{--                            <label>  Select Upazilla </label>--}}
{{--                            {!! Form::select('upazilla_id', $upazillas, null,['id'=>'upazilla_id','class'=>'form-control select2','placeholder'=>'Select Upazilla']); !!}--}}
{{--                        </div>--}}
{{--                        <div class="col-md-3">--}}
{{--                            <label>  Select Union </label>--}}
{{--                            {!! Form::select('union_id', $unions, null,['id'=>'union_id','class'=>'form-control select2','placeholder'=>'Select Union']); !!}--}}
{{--                        </div>--}}
{{--                        <div class="col-md-3">--}}
{{--                            <label>  Select Area </label>--}}
{{--                            {!! Form::select('area_id', $areas, null,['id'=>'area_id','class'=>'form-control select2','placeholder'=>'Select Area']); !!}--}}
{{--                        </div>--}}
{{--                        <div class="col-md-3">--}}
{{--                            <label>  Head of Accounts </label>--}}
{{--                            {!! Form::select('account_type_id', $accounts, null,['id'=>'account_type_id','class'=>'form-control select2','placeholder'=>'Select All']); !!}--}}
{{--                        </div>--}}
                        <div class="col-md-3">
                            <label> From Date (Transaction)</label>
                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-3">
                            <label> To Date (Transaction)</label>
                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <div class="box-body">

                    <div class="col-md-3">
                        <input class="btn  btn-sm btn-info" value="Search" type="submit"/>
                        <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Reload</a>

                    </div>
                </div>

                {!! Form::close() !!}
            </div>

            @if(count($modal) > 0)
                <div class="box">
                    <div class="box-body">
                        <div class="col-lg-12" id="custom-print">

                            @foreach($account_types as $key1=>$account_type)
                                @if($loop->first)

                                    <div class="col-md-12  text-right" style="padding: 0 20px;">
                                        <a class="btn btn-sm  btn-primary pull-right ml-1" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=print" id="btn-print"> <i class="fa fa-print"></i> Print </a>
                                        <a class="btn btn-sm  btn-success pull-right ml-1" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=download" > <i class="fa fa-download"></i> Download </a>
                                    </div>
                                @endif
                                <div class="box" style="overflow: hidden;">
                                    <div class="box-header with-border">

                                        <h3 class="box-title" style="margin: 10px 0;"> {{ $account_type->display_name }}</h3>
                                        <table class="table table-bordered" style="margin-bottom: 50px;">

                                            <tbody>

                                            <tr>
                                                <th style="width: 100px;">Date</th>
                                                <th >Particulars</th>
                                                {{--                                        <th class="">Payment Type</th>--}}
                                                {{--                                        <th class="">Cash or Bank Account Name</th>--}}
                                                {{--                                        <th> Supplier/Customer Name</th>--}}
                                                <th>Remarks</th>
                                                <th class="text-right">DR. TK. </th>
                                                <th class="text-right">CR. TK. </th>
                                                <th class="text-right">Balance </th>
                                            </tr>

                                            @php
                                                $total_dr = 0.00;
                                                $total_cr = 0.00;
                                                $balance = 0.00;
                                            @endphp

                                            @if(isset($account_type_balance[$account_type->id]['bf_balance']) && $account_type_balance[$account_type->id]['bf_balance'] !=0 )
                                                @php
                                                    $bf_balance = $account_type_balance[$account_type->id]['bf_balance'];
                                                    $bf_date = $account_type_balance[$account_type->id]['bf_date'];
                                                    $total_dr += $bf_balance>0 ? $bf_balance : 0;
                                                    $total_cr += $bf_balance<0 ? $bf_balance*(-1) : 0;
                                                    $balance = $bf_balance;
                                                @endphp

                                                <tr>
                                                    <td class="">{{ db_date_month_year_format($bf_date) }}</td>
                                                    <td class="">Balance B/F</td>
                                                    <td class="">B/F</td>
                                                    <td class="text-right ">{{ $bf_balance>0 ? create_money_format($bf_balance) : "-" }}</td>
                                                    <td class="text-right ">{{ $bf_balance<0 ? create_money_format($bf_balance*(-1)) : "-" }}</td>
                                                    <td class="text-right ">{{ create_money_format($balance) }}</td>
                                                </tr>
                                            @endif
                                            @foreach($modal as $key2=>$value2)
                                                @if($value2->account_name == $account_type->display_name)

                                                    @php
                                                        if($value2->transaction_type=='dr'){
                                                            $total_dr += $value2->td_amount;
                                                        }else{
                                                            $total_cr += $value2->td_amount;
                                                        }
                                                    $balance = $value2->transaction_type=='dr' ? $balance+$value2->td_amount : $balance-$value2->td_amount;
                                                    @endphp
                                                    <tr>
                                                        <td class="">{{ db_date_month_year_format($value2->date) }}</td>
                                                        <td class="">{{ $value2->against_account_name }}</td>
                                                        {{--                                                <td class="">{{ $value2->payment_type_name }}</td>--}}
                                                        {{--                                                <td class="">{{ $value2->title }}</td>--}}
                                                        {{--                                                <td class="">{{ $value2->sharer_name }}</td>--}}
                                                        <td class="">{{ $value2->remarks }}</td>
                                                        <td class="text-right ">{{ $value2->transaction_type=="dr" ? create_money_format($value2->td_amount) : "-"}}</td>
                                                        <td class="text-right ">{{ $value2->transaction_type=="cr" ? create_money_format($value2->td_amount) : "-"}}</td>
                                                        <td class="text-right ">{{ create_money_format($balance) }}</td>
                                                    </tr>

                                                @endif
                                            @endforeach
                                            <tr>
                                                <th colspan="3" class="text-center">Balance C/d</th>
                                                <th class="text-right">{{ create_money_format($total_dr)}}</th>
                                                <th class="text-right">{{ create_money_format($total_cr) }}</th>
                                                <th class="text-right">{{ create_money_format($balance) }}</th>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @if($loop->last)

                                    <div class="col-md-12  text-right" style="padding: 0 20px;">
                                        <a class="btn btn-sm  btn-primary pull-right ml-1" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=print" id="btn-print"> <i class="fa fa-print"></i> Print </a>
                                        <a class="btn btn-sm  btn-success pull-right ml-1" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=download" > <i class="fa fa-download"></i> Download </a>
                                    </div>
                                @endif
                            @endforeach

                        </div>


                    </div>
                </div>
            @endif
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
