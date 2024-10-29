<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 18-Feb-20
 * Time: 12:34 PM
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
        'name' => 'Account Head Daywise Balance',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => "Account Head Daywise Balance",
    'title'=> "Account Head Daywise Balance",
    'heading' =>trans('common.account_head_daywise_balance'),
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
                    <h3 class="box-title">{{__('common.search')}}</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>

            {!! Form::open(['route' => 'member.report.account_day_wise_last_balance','method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">

                        @if(Auth::user()->hasRole(['super-admin', 'developer']))
                            <div class="col-md-3">
                                <label>{{__('common.select_company')}}   </label>
                                {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>trans('common.select_company')]); !!}
                            </div>
                        @endif
                        <div class="col-md-3">
                            <label> {{__('common.head_of_accounts')}}   </label>
                            {!! Form::select('account_type_id', $accounts, null,['id'=>'account_type_id','class'=>'form-control select2','placeholder'=>trans('common.select_all'), 'required']); !!}
                        </div>
                        <div class="col-md-3">
                            <label>{{__('common.from_date')}}   ({{__('common.transaction')}} )</label>
                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-3">
                            <label>{{__('common.to_date')}} ({{__('common.transaction')}} )</label>
                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <div class="box-body">

                    <div class="col-md-3">
                        <input class="btn  btn-sm btn-info" value="{{__('common.search')}}" type="submit"/>
                        <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i>{{__('common.reload')}} </a>

                    </div>
                </div>

                {!! Form::close() !!}
            </div>

            <div class="box">
                <div class="box-body">
                    <div class="col-md-12  text-right" style="padding: 0 20px;">
                        <a class="btn btn-sm  btn-primary pull-right ml-1" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=print" id="btn-print"> <i class="fa fa-print"></i> {{__('common.print')}}  </a>
                        <a class="btn btn-sm  btn-success pull-right ml-1" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=download" > <i class="fa fa-download"></i>{{__('common.download')}}   </a>
                    </div>
                    <div class="col-lg-12" id="custom-print">

                            <div class="box" style="overflow: hidden;">
                                <div class="box-header with-border">

                                    <h3 class="box-title" style="margin: 10px 0;">@if(isset($account_types)){{__('common.account_of')}}   {{ $account_types->display_name }} @endif</h3>
                                    <table class="table table-bordered" style="margin-bottom: 50px;">

                                        <tbody>

                                        <tr>
                                            <th class="">{{__('common.date')}} </th>
                                            <th class="text-right">{{__('common.balance')}}  </th>
                                        </tr>

                                        @foreach($modal as $key2=>$value2)
                                            <tr>
                                                <td class="">{{ db_date_month_year_format($value2->date) }}</td>
                                                <td class="text-right ">{{ create_money_format($value2->balance) }}</td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>

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


