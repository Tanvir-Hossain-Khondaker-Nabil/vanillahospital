<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 04-Feb-20
 * Time: 12:28 PM
 */
 $route =  \Auth::user()->can(Route::current()->getName()) ? route(Route::current()->getName()) : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => "Account Head Balance Adjustment and Update",
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => "Account Head Balance Adjustment and Update",
    'title' => "Account Head Balance Adjustment and Update",
    'heading' =>trans('common.account_head_balance_adjustment_and_update'),
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
            @include('common._error')

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('common.all_transaction_form_begin')}} *</h3>

                    {{--                    <div class="box-tools pull-right">--}}
                    {{--                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>--}}
                    {{--                    </div>--}}
                </div>

            {!! Form::open(['route' => 'admin.ledger_book.update_account_head_balance','method' => 'POST', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        @if(Auth::user()->hasRole(['super-admin', 'developer']))
                            <div class="col-md-3">
                                <label> {{__('common.select_company')}} </label>
                                {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>trans('common.select_company'), 'required']); !!}
                            </div>
                        @endif
                        <div class="col-md-3">
                            <label>{{__('common.head_of_accounts')}}   </label>
                            {!! Form::select('account_type_id', $accountHeads, null,['id'=>'account_type_id','class'=>'form-control select2','placeholder'=>trans('common.select_all'), 'required']); !!}
                        </div>
                        {{--                        <div class="col-md-3">--}}
                        {{--                            <label> From Date (Transaction)</label>--}}
                        {{--                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="col-md-3">--}}
                        {{--                            <label> To Date (Transaction)</label>--}}
                        {{--                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>--}}
                        {{--                        </div>--}}
                    </div>
                    <!-- /.row -->
                </div>
                <div class="box-body">

                    <div class="col-md-3">
                        <input class="btn  btn-sm btn-info" value="{{__('common.update')}}" type="submit"/>
                        <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i
                                class="fa fa-refresh"></i> {{__('common.reload')}} </a>

                    </div>
                </div>

                {!! Form::close() !!}
            </div>

            {{--<div class="box box-default">--}}
                {{--<div class="box-header with-border">--}}
                    {{--<h3 class="box-title">All Transaction Using Month </h3>--}}
                {{--</div>--}}

                {{--{!! Form::open(['route' => 'admin.ledger_book.update_account_head_balance','method' => 'POST', 'role'=>'form' ]) !!}--}}

                {{--<div class="box-body">--}}
                    {{--<div class="row">--}}
                        {{--@if(Auth::user()->hasRole(['super-admin', 'developer']))--}}
                            {{--<div class="col-md-3">--}}
                                {{--<label> Select Company </label>--}}
                                {{--{!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>'Select Company', 'required']); !!}--}}
                            {{--</div>--}}
                        {{--@endif--}}
                        {{--<div class="col-md-3">--}}
                            {{--<label> Head of Accounts </label>--}}
                            {{--{!! Form::select('account_type_id', $accountHeads, null,['id'=>'account_type_id','class'=>'form-control select2','placeholder'=>'Select All', 'required']); !!}--}}
                        {{--</div>--}}
                        {{--<div class="col-md-3">--}}
                            {{--<label> From Month </label>--}}
                            {{--{!! Form::select('month_select', $processing_month, null,['class'=>'form-control select2','placeholder'=>'Select All', 'required']); !!}--}}
                        {{--</div>--}}

                    {{--</div>--}}
                    {{--<!-- /.row -->--}}
                {{--</div>--}}
                {{--<div class="box-body">--}}

                    {{--<div class="col-md-3">--}}
                        {{--<input class="btn  btn-sm btn-info" value="Update" type="submit"/>--}}
                        {{--<a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i--}}
                                {{--class="fa fa-refresh"></i> Reload</a>--}}

                    {{--</div>--}}
                {{--</div>--}}

                {{--{!! Form::close() !!}--}}
            {{--</div>--}}


            {{--<div class="box box-default">--}}
                {{--<div class="box-header with-border">--}}
                    {{--<h3 class="box-title">Deleted Transaction Using Month </h3>--}}
                {{--</div>--}}

                {{--{!! Form::open(['route' => 'admin.ledger_book.update_account_head_balance','method' => 'POST', 'role'=>'form' ]) !!}--}}

                {{--<div class="box-body">--}}
                    {{--<div class="row">--}}
                        {{--@if(Auth::user()->hasRole(['super-admin', 'developer']))--}}
                            {{--<div class="col-md-3">--}}
                                {{--<label> Select Company </label>--}}
                                {{--{!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>'Select Company', 'required']); !!}--}}
                            {{--</div>--}}
                        {{--@endif--}}
                        {{--<div class="col-md-3">--}}
                            {{--<label> Head of Accounts </label>--}}
                            {{--{!! Form::select('account_type_id', $accountHeads, null,['id'=>'account_type_id','class'=>'form-control select2','placeholder'=>'Select All', 'required']); !!}--}}
                        {{--</div>--}}
                        {{--<div class="col-md-3">--}}
                            {{--<label> From Month </label>--}}
                            {{--{!! Form::select('month_select', $processing_month, null,['class'=>'form-control select2','placeholder'=>'Select All', 'required']); !!}--}}
                        {{--</div>--}}

                    {{--</div>--}}
                    {{--<!-- /.row -->--}}
                {{--</div>--}}
                {{--<div class="box-body">--}}

                    {{--<div class="col-md-3">--}}
                        {{--<input class="btn  btn-sm btn-info" value="Update" type="submit"/>--}}
                        {{--<a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i--}}
                                {{--class="fa fa-refresh"></i> Reload</a>--}}

                    {{--</div>--}}
                {{--</div>--}}

                {{--{!! Form::close() !!}--}}
            {{--</div>--}}



            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('common.deleted_failed_transaction_with_without_account')}}</h3>
                    <p class="text-red">{{__('common.deleted_failed_transaction_with_without_account')}}  {{__('common.you_can_select_single_account_ead_or_if_you_do_with_only_company_it_will_check_all_account_types_and_delete_and_fixed')}}</p>
                </div>

                {!! Form::open(['route' => 'admin.ledger_book.delete_account_head_daywise_balance','method' => 'POST', 'role'=>'form' ]) !!}

                <div class="box-body">
                    <div class="row">
                        @if(Auth::user()->hasRole(['super-admin', 'developer']))
                        <div class="col-md-3">
                            <label> {{__('common.select_company')}} *</label>
                            {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>trans('common.select_company'), 'required']); !!}
                        </div>
                        @else
                            <div class="col-md-3">
                                <label>{{__('common.select_company')}}*</label>
                                {!! Form::select('company_id', $companies, Auth::user()->company_id,['id'=>'company_id','class'=>'form-control select2','placeholder'=>trans('common.select_company'), 'required']); !!}
                            </div>
                        @endif

                        <div class="col-md-3">
                            <label>{{__('common.head_of_accounts')}} </label>
                            {!! Form::select('account_type_id', $accountHeads, null,['id'=>'account_type_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                        </div>

                    </div>
                </div>
                <div class="box-body">

                    <div class="col-md-3">
                        <input class="btn  btn-sm btn-danger" value="{{__('common.Delete')}}" type="submit" onclick=" return confirm('Are you Sure');"/>
                        <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i
                                class="fa fa-refresh"></i> {{__('common.reload')}} </a>

                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        $(document).ready(function () {
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



