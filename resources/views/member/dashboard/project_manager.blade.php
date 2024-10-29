<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/26/2019
 * Time: 2:45 PM
 */
   $route =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";
   $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

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
    'title'=>'Dashboard',
    'heading' => 'Dashboard',
];


$employee = auth()->user()->employee ?? '';
?>
@extends('layouts.back-end.master', $data)

@section('contents')
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-8 pr-0">

            @if($employee)
                <div class="row">

                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="info-box bg-maroon-gradient">
                            <span class="info-box-icon"><i class="fa fa-calendar"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text ">Join Date</span>
                                <h2 class="mt-1">{{ $employee->join_date ? \Carbon\Carbon::parse($employee->join_date)->toFormattedDateString() : "Not Found" }}</h2>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="info-box {{ $employee->passport_expire > date("Y-m-d") ? "bg-navy" : "bg-red" }} ">
                            <span class="info-box-icon"><i class="fa {{ $employee->passport_expire > date("Y-m-d") ? "fa-calendar" : "fa-calendar-times-o" }} "></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text ">Passport {{ $employee->passport_expire > date("Y-m-d") ? "Expiry Date" : "Expired" }} </span>
                                <h2 class="mt-1">{{ $employee->passport_expire ? \Carbon\Carbon::parse($employee->passport_expire)->toFormattedDateString()  : "Not Found"}}</h2>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="info-box  {{ $employee->visa_expire > date("Y-m-d") ? "bg-blue-gradient" : "bg-red" }} ">
                            <span class="info-box-icon"><i class="fa {{ $employee->visa_expire > date("Y-m-d") ? "fa-calendar" : "fa-calendar-times-o" }} "></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text ">Visa {{ $employee->visa_expire > date("Y-m-d") ? "Expiry Date" : "Expired" }} </span>
                                <h2 class="mt-1">{{ $employee->visa_expire ? \Carbon\Carbon::parse($employee->visa_expire)->toFormattedDateString() : "Not Found" }}</h2>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="info-box {{ $employee->pr_expire > date("Y-m-d") ? "bg-orange-active" : "bg-red" }} ">
                            <span class="info-box-icon"><i class="fa {{ $employee->pr_expire > date("Y-m-d") ? "fa-calendar" : "fa-calendar-times-o" }} "></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text ">PR {{ $employee->pr_expire > date("Y-m-d") ? "Expiry Date" : "Expired" }} </span>
                                <h2 class="mt-1">{{ $employee->pr_expire ? \Carbon\Carbon::parse($employee->pr_expire)->toFormattedDateString() : "Not Found" }}</h2>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                </div>
            @endif


            <div class="row">
                    <div class="col-lg-4 col-xs-6">
                        {{--<!-- small box -->--}}
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>{{ $total_assigned }}</h3>

                                <p>Project Assigned</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-android-list"></i>
                            </div>
                        </div>
                    </div>
                    {{--<!-- ./col -->--}}
                    <div class="col-lg-4 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3>{{$total_complete_project}}</h3>

                                <p>Project Completed</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-android-list"></i>
                            </div>
                        </div>
                    </div>
                    {{--<!-- ./col -->--}}
                    <div class="col-lg-4 col-xs-6">
                        {{--<!-- small box -->--}}
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3>{{$total_project}}</h3>

                                <p>Total Projects</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-android-list"></i>
                            </div>
                        </div>
                    </div>
                    {{--<!-- ./col -->--}}
                    <div class="col-lg-4 col-xs-6">
                        {{--<!-- small box -->--}}
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3>{{$total_task}}</h3>

                                <p>Total Tasks</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-filing"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xs-6">
                        {{--<!-- small box -->--}}
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{$total_pending}}</h3>

                                <p>Pending Tasks</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-filing"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xs-6">
                        {{--<!-- small box -->--}}
                        <div class="small-box bg-purple">
                            <div class="inner">
                                <h3>{{$total_complete}}</h3>

                                <p>Completed Tasks</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-filing"></i>
                            </div>
                        </div>
                    </div>
                <div class="col-lg-4 col-xs-6">
                    {{--<!-- small box -->--}}
                    <div class="small-box bg-lime-active">
                        <div class="inner">
                            <h3>{{ create_money_format($salary_unpaid) }}</h3>

                            <p>Salary Unpaid</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-dollar"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    {{--<!-- small box -->--}}
                    <div class="small-box bg-fuchsia-active">
                        <div class="inner">
                            <h3>{{$unpaid_months}}</h3>

                            <p>Unpaid Months</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-times-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    {{--<!-- small box -->--}}
                    <div class="small-box bg-maroon-active">
                        <div class="inner">
                            <h3>{{ $last_salary ? $last_salary->en_month : "Not Found" }}</h3>

                            <p>Last Paid Salary</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                </div>

                    {{--<!-- ./col -->--}}
                </div>
        </div>
        <div class="col-lg-4">

            <!-- Small boxes (Stat box) -->
            <div class="row mx-0">

                <div class="col-lg-12 px-0">
                    <div class="box box-solid bg-green-gradient">
                        <div class="box-header  text-center">
                            <i class="fa fa-calendar"></i>

                            <h3 class="box-title">Calendar</h3>
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
                <div class="col-lg-12 px-0">
                    <div class="box border-top-0">
                        <div class="box-header  bg-light-blue-gradient text-center">
                            <h3 class="box-title">Salary History</h3>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th> Month</th>
                                    <th class="text-center"> Amount</th>
                                </tr>

                                @foreach($salary as $value)

                                    <tr>
                                        <th> {{ $value->en_month }}</th>
                                        <th class="text-right">
                                            {{ create_money_format($value->net_payable) }}
                                        </th>
                                    </tr>
                                @endforeach
                                </thead>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
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

