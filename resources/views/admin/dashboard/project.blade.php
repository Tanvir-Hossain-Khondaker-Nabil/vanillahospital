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
    'heading' => trans('dashboard.project_dashboard'),
];
?>
@extends('layouts.back-end.master', $data)


@push('styles')
    @include('admin.dashboard.style')

    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
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


    <!-- Small boxes (Stat box) -->
    <div class="row">
        <!-- ./col -->
        <div class="col-lg-12 col-xs-12">
            <div class="box box-primary">

                {!! Form::open(['route' => Route::current()->getName(),'method' => 'get', 'role'=>'form' ]) !!}

                <div class="box-body">

                    <div class="row">
                        <div class="col-md-5">
                            <label>{{__('common.project_name')}}</label>
                            {!! Form::select('project_id', $projects, null,['id'=>'project_id','required','class'=>'form-control select2','placeholder'=>trans('common.select_project')]); !!}
                        </div>
                        <div class="col-md-2 pl-0  text-center">
                            <label> {{__('common.date')}} </label>
                            <input class="form-control " name="date" type="date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-2 pl-0 text-center">
                            <label> {{__('common.project_status')}} </label>
                            <input class="form-control bg-white text-center text-capitalize" disabled  value="{{ $progress_status ?? '' }}" autocomplete="off"/>
                        </div>
                        <div class="col-md-2  pl-0 text-center">
                            <label> % {{__('common.complete')}} </label>
                            <input class="form-control bg-white text-center" disabled value="{{ isset($total_complete) ? $total_complete."%" : '' }}" autocomplete="off"/>
                        </div>
                        <div class="col-md-1 margin-top-23 px-0">
                            <label></label>
                            <button type="submit" class="btn btn-primary"> <i class="fa fa-search"> </i></button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

    </div>


    @push('scripts')


        <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

        <script type="text/javascript">
            $(function () {
                $('.select2').select2();
                // $('.date').datepicker({
                //     "setDate": new Date(),
                //     "format": 'mm/dd/yyyy',
                //     "todayHighlight": true,
                //     "autoclose": true
                // });
            });
        </script>
    @endpush

    @if(request()->get('project_id') == "")
    <div class="row" id="search_data">

        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-navy">
                <div class="inner">
                    <h3>{{ $active_projects }}</h3>

                    <p>{{ __('common.active_projects') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-android-checkbox"></i>
                </div>
            </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-orange">
                <div class="inner">
                    <h3>{{ $inactive_projects }} </h3>

                    <p>{{ __('common.inactive_projects') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-close"></i>
                </div>

            </div>
        </div>
        <!-- ./col -->

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-maroon">
                <div class="inner">
                    <h3>{{ $total_projects }}</h3>

                    <p>{{ __('common.total_projects') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-information-circled"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>{{ $total_leads }}</h3>

                    <p>{{ __('common.total_leads') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
            </div>
        </div>


        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $active_clients }}</h3>

                    <p>{{ __('common.active_clients') }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-user-circle-o"></i>
                </div>
            </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $inactive_clients }} </h3>

                    <p>{{ __('common.inactive_clients') }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-user-times"></i>
                </div>

            </div>
        </div>
        <!-- ./col -->

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $total_clients }}</h3>

                    <p>{{ __('common.total_clients') }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua-gradient">
                <div class="inner">
                    <h3>{{ $pending_tasks }}</h3>

                    <p>{{ __('common.in_progress_tasks') }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-tasks"></i>
                </div>
            </div>
        </div>


        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-olive">
                <div class="inner">
                    <h3>{{ $review_tasks }}</h3>

                    <p>{{ __('common.review_tasks') }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-tasks"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-gray">
                <div class="inner">
                    <h3>{{ $to_do_tasks }}</h3>

                    <p>{{ __('common.todo_tasks') }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-tasks"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->

        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-fuchsia-active">
                <div class="inner">
                    <h3>{{ $total_tasks }}</h3>

                    <p>{{ __('common.total_tasks') }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-tasks"></i>
                </div>
            </div>
        </div>


        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-blue-active">
                <div class="inner">
                    <h3>{{ $complete_tasks }}</h3>

                    <p>{{ __('common.completed_tasks') }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-tasks"></i>
                </div>
            </div>
        </div>

    </div>

    @else

        @include('member.project.project_analytics')

    @endif


@endsection
