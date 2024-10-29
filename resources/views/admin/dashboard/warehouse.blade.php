<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/26/2019
 * Time: 2:45 PM
 */

$route = \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : '#';
$home1 = \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : '#';

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
    'heading' => trans('dashboard.warehouse_dashboard'),
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
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $active_warehouses }}</h3>

                    <p>{{ __('dashboard.active_warehouse') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-android-checkbox"></i>
                </div>
            </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $inactive_warehouses }} </h3>

                    <p>{{ __('dashboard.inactive_warehouse') }}</p>
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
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $total_warehouses }}</h3>

                    <p>{{ __('dashboard.total_warehouse') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-information-circled"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-navy">
                <div class="inner">
                    <h3>{{ $total_products }}</h3>

                    <p>{{ __('dashboard.total_product') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-coffee"></i>
                </div>
            </div>
        </div>

    </div>
@endsection
