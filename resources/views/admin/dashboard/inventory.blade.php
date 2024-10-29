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
    'title'=>'Dashboard',
    'heading' => trans('dashboard.inventory_dashboard'),
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


<!-- Small boxes (Stat box) -->
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
</div>
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ $total_products }}</h3>

                <p>{{ __('dashboard.total_product') }}</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="{{ route('member.items.index') }}" class="small-box-footer">{{__('dashboard.more_info')}} <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $total_purchases }}</h3>

                <p>{{ __('dashboard.total_purchase') }}</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="{{ route('member.purchase.index') }}" class="small-box-footer">{{__('dashboard.more_info')}} <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $total_sales }} </h3>

                <p>{{ __('dashboard.total_sale') }}</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="{{ route('member.sales.index') }}" class="small-box-footer">{{__('dashboard.more_info')}} <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->

    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ $total_users }}</h3>

                <p>{{ __('dashboard.total_user') }}</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="{{ route('member.users.index') }}" class="small-box-footer">{{__('dashboard.more_info')}} <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

    <!-- Info boxes -->
    <div class="row">

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-shopping-cart"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">{{ __('dashboard.today_purchase_amount') }}</span>
                    <span class="info-box-number">{{ create_money_format($today_purchase_amount )}} {{ getCurrencySymbol() }}
</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">{{ __('dashboard.total_purchase_amount') }}</span>
                    <span class="info-box-number">{{ create_money_format($total_purchase_amount) }}  {{ getCurrencySymbol() }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>


        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-shopping-bag"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">{{ __('dashboard.today_sales_amount') }}</span>
                    <span class="info-box-number">{{ create_money_format($today_sales_amount) }}  {{ getCurrencySymbol() }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-shopping-basket"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">{{ __('dashboard.total_Sales_amount') }}</span>
                    <span class="info-box-number">{{ create_money_format($total_sales_amount) }}  {{ getCurrencySymbol() }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->


        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Info boxes -->
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-lime-active"><i class="fa fa-money"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">{{ __('dashboard.total_due') }}</span>
                    <span class="info-box-number">{{ create_money_format($total_due) }}  {{ getCurrencySymbol() }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-maroon"><i class="fa fa-money"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">{{ __('dashboard.today_due') }}</span>
                    <span class="info-box-number">{{ create_money_format($today_due) }}  {{ getCurrencySymbol() }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-lime-active"><i class="fa fa-money"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">{{ __('dashboard.total_out_standing') }}</span>
                    <span class="info-box-number">{{ create_money_format($total_out_standing) }}  {{ getCurrencySymbol() }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-navy"><i class="fa fa-money"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text" style="text-wrap:wrap;">{{ __('dashboard.today_out_standing') }}</span>
                    <span class="info-box-number">{{ create_money_format($today_out_standing) }}  {{ getCurrencySymbol() }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
    </div>

@endsection

@push('scripts')

    @include('admin.dashboard.scripts')

@endpush
