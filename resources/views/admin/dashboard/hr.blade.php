<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/26/2019
 * Time: 2:45 PM
 */

$route = \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";
$home1 = \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Dashboard',
        'href' => $route,
    ]
];

$data['data'] = [
    'name' => 'Dashboard',
    'title' => 'Dashboard',
    'heading' => trans('dashboard.hr_dashboard'),
];


?>
@extends('layouts.back-end.master', $data)


@push('styles')

    @include('admin.dashboard.style')

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

    <div class="row">
        <div class="col-md-12">

            <div class="box">
                <div class="box-body pb-0">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12 px-0 text-right">

                                <a class="btn btn-success btn-sm"
                                   href="{{ url()->current() }}?view_item=graphical"> {{ __('dashboard.graphical_analysis') }}</a>

                                <a class="btn btn-info btn-sm"
                                   href="{{ url()->current() }}?view_item=normal">{{ __('dashboard.normal_analysis') }}</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">

            <!-- Small boxes (Stat box) -->
            <div class="row mx-0">
                <!-- ./col -->

                <!-- ./col -->
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green-gradient">
                        <div class="inner">
                            <h3>{{ $total_employee }}</h3>

                            <p>{{ __('common.total_employees') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-information-circled"></i>
                        </div>
                    </div>
                </div>


                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-maroon">
                        <div class="inner">
                            <h3>{{ 0 }}</h3>

                            <p>{{ __('common.active_employees') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-android-checkbox"></i>
                        </div>
                    </div>
                </div>

                <!-- ./col -->
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-orange">
                        <div class="inner">
                            <h3>{{ $inactive_employee }} </h3>

                            <p>{{ __('common.inactive_employees') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-close"></i>
                        </div>

                    </div>
                </div>
                <!-- ./col -->


                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{ $on_leaves }}</h3>

                            <p class="text-white"><a  href="{{ route('member.hr.employee-on-leaves') }}" class="text-white">{{ __('common.on_leave') }}</a></p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-calendar-minus-o"></i>
                        </div>
                    </div>
                </div>


                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-olive">
                        <div class="inner">
                            <h3>{{ $total_present }}</h3>

                            <p>{{ __('common.total_present') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-calendar-check-o"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{ $total_employee-$total_present }}</h3>

                            <p>{{ __('common.total_absent') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-calendar-times-o"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>{{ $next_attends }}</h3>

                            <p class="text-white"><a  href="{{ route('member.hr.employee-next-attends') }}" class="text-white">{{ __('common.next_day_attend') }}</a></p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-calendar-plus-o"></i>
                        </div>
                    </div>
                </div>


                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-teal">
                        <div class="inner">
                            <h3>{{ $unread_messages }}</h3>

                            <p>{{ __('common.unread_message') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-chatbox"></i>
                        </div>
                    </div>
                </div>


                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-blue-active">
                        <div class="inner">
                            <h3>{{ $total_shifts }}</h3>

                            <p>{{ __('common.total_shift') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-fuchsia">
                        <div class="inner">
                            <h3>{{ $total_department }}</h3>

                            <p>{{ __('common.total_department') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-briefcase"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{ $total_designation }}</h3>

                            <p>{{ __('common.total_designation') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-briefcase"></i>
                        </div>
                    </div>
                </div>


            </div>
        </div>


        <div class="col-md-3 pl-0">

            <!-- Small boxes (Stat box) -->
            <div class="row mx-0">


                <div class="col-lg-12 px-0">
                    <!-- small box -->
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

    <div class="row ml-0">


        <div class="col-md-4" id="employee_join_month">

                @include('admin.dashboard.hr_common_joined')

        </div>

        <div class="col-md-4">

            <div class="box box-solid bg-aqua-gradient">
                <div class="box-header  text-center">
                    <h3 class="box-title">   <a class=" text-white bg-transparent" href="{{ route('member.hr.employee-visa-expires') }}"> {{__('common.visa_expired')}} ({{ $count_visa_expires }}) </a> </h3>

                    {{--@if($count_visa_expires>count($visa_expires))--}}
                        {{--<div class="col-sm-12">--}}
                            {{--<a class="btn btn-primary btn-sm" href="{{ route('member.hr.employee-passport-expires') }}"> View More </a>--}}
                        {{--</div>--}}

                    {{--@endif--}}

                </div>

                <div class="box-footer text-black">
                    <div class="row">

                        @foreach($visa_expires as $date)
                            <div class="col-sm-12">
                                <!-- Progress bars -->
                                <div class="clearfix">
                                    <span class="pull-left h6">{{ $date->employee_name_id }}</span>
                                    <small
                                        class="pull-right h6">{{  \Carbon\Carbon::parse($date->join_date)->toFormattedDateString() }}</small>
                                </div>

                            </div>
                        @endforeach

                    </div>
                    <!-- /.row -->
                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="box box-solid bg-blue-active">
                <div class="box-header  text-center">
                    <h3 class="box-title">  <a class="text-white bg-transparent" href="{{ route('member.hr.employee-passport-expires') }}"> {{__('common.passport_expired')}} ({{ $count_passport_expires }}) </a> </h3>
                    {{--@if($count_passport_expires>count($passport_expires))--}}
                        {{--<div class="col-sm-12">--}}
                            {{--<a class="btn btn-primary  btn-sm" href="{{ route('member.hr.employee-passport-expires') }}"> View More </a>--}}
                        {{--</div>--}}
                    {{--@endif--}}
                </div>

                <div class="box-footer text-black">
                    <div class="row">

                        @foreach($passport_expires as $date)
                            <div class="col-sm-12 text-center">
                                <!-- Progress bars -->
                                <div class="clearfix">
                                    <span class="pull-left h6">{{ $date->employee_name_id }}</span>
                                    <small
                                        class="pull-right h6">{{  \Carbon\Carbon::parse($date->join_date)->toFormattedDateString() }}</small>
                                </div>

                            </div>
                        @endforeach


                    </div>
                    <!-- /.row -->
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


        $(document).on('click', '.month-join', function() {
            var target = $(this).data("target");
            var month = $(this).data("content");
            var year = $(this).data("title");

            $.ajax({
                type: "get",
                url: "{{ route('admin.employee.show-join-month') }}",
                data: {
                    'target': target,
                    'month' : month,
                    'year'  : year,
                },
                success: function(result) {
                    // var result = JSON.parse(data);
                    $('#employee_join_month').html(result.html);
                }
            });

        });

    </script>
@endpush
