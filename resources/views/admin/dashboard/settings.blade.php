<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/26/2019
 * Time: 2:45 PM
 */

 $route =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Dashboard',
        'href' => $route,
    ],
    ];
$data['data'] = [
    'name' => 'Dashboard',
    'title' => 'Dashboard',
    'heading' => trans('common.dashboard'),
];
?>
@extends('layouts.back-end.master', $data)


@push('styles')

    @include('admin.dashboard.style')

    <style type="text/css">
        #settings_dash span.pull-left.h6 {
            width: 67%;
        }
    </style>

@endpush

@section('contents')

    @if (session('set_expired_days'))
        <div class="alert alert-danger alert-out text-left">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-exclamation-circle"></i> {{__('common.system_access_expired')}}</h4>
            <span class="text-capitalize">{{__('common.your_access_expired_soon')}}. {{ session('set_expired_days') }} {{__('common.days_remaining')}}. {{__('common.please_contact_and_pay_soon')}}. </span>
        </div>
    @endif

    @include('common._alert')

    <div class="row" id="settings_dash">
        <div class="col-md-9">

            <!-- Small boxes (Stat box) -->
            <div class="row mx-0">
                <!-- ./col -->
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="box box-solid bg-blue-gradient">
                        <div class="box-header  text-center">
                            <h3 class="box-title">{{__('common.last_joined')}}</h3>
                        </div>

                        <div class="box-footer text-black">
                            <div class="row">

                                @foreach($employee_joins as $date)
                                    <div class="col-sm-12">
                                        <!-- Progress bars -->
                                        <div class="clearfix">
                                            <span class="pull-left h6">{{ $date->employee_name_id }}</span>
                                            <small class="pull-right h6">{{  \Carbon\Carbon::parse($date->join_date)->toFormattedDateString() }}</small>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            <!-- /.row -->
                        </div>

                    </div>



                </div>

                <div class="col-lg-4 col-xs-6">

                    <div class="box box-solid bg-blue-active">
                        <div class="box-header  text-center">
                            <h3 class="box-title">{{__('common.passport_expired')}}</h3>
                        </div>

                        <div class="box-footer text-black">
                            <div class="row">

                                @foreach($passport_expires as $date)
                                    <div class="col-sm-12">
                                        <!-- Progress bars -->
                                        <div class="clearfix">
                                            <span class="pull-left h6">{{ $date->employee_name_id }}</span>
                                            <small class="pull-right h6">{{  \Carbon\Carbon::parse($date->join_date)->toFormattedDateString() }}</small>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            <!-- /.row -->
                        </div>

                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <div class="box box-solid bg-aqua-gradient">
                        <div class="box-header  text-center">
                            <h3 class="box-title">{{__('common.visa_expired')}}</h3>
                        </div>

                        <div class="box-footer text-black">
                            <div class="row">

                                @foreach($visa_expires as $date)
                                    <div class="col-sm-12">
                                        <!-- Progress bars -->
                                        <div class="clearfix">
                                            <span class="pull-left h6">{{ $date->employee_name_id }}</span>
                                            <small class="pull-right h6">{{  \Carbon\Carbon::parse($date->join_date)->toFormattedDateString() }}</small>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            <!-- /.row -->
                        </div>

                    </div>
                </div>

            </div>
        </div>


        <div class="col-md-3 pl-0">

            <!-- Small boxes (Stat box) -->
            <div class="row mx-0">

                <div class="col-lg-12 px-0">
                    <div class="small-box bg-blue-gradient">
                        <div class="inner">
                            <h3>{{ countWorkingDaysInMonth(date('Y'), date('M')) }}</h3>

                            <p>{{ __('common.total_working_days') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-calendar"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 px-0">
                    <div class="box box-solid bg-navy">
                        <div class="box-header  text-center">
                            <i class="fa fa-calendar"></i>

                            <h3 class="box-title">{{__('common.calendar')}}</h3>
                            <!-- tools box -->
                            <!-- /. tools -->
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <!--The calendar -->
                            <div id="calendar" style="width: 100%"></div>
                        </div>


                    </div>

                </div>
            </div>
            <!-- ./col -->
        </div>
    </div>


@endsection


@push('scripts')

    <script>

        // The Calender
        $('#calendar').datepicker({
            "setDate": new Date(),
            "todayHighlight": true
        });
    </script>
@endpush
