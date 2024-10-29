<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/26/2019
 * Time: 11:40 AM
 */


$user = \Illuminate\Support\Facades\Auth::user();

?>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img
                    src="{{ !empty(auth()->user()->photo) ? auth()->user()->profile_photo :  asset('public/adminLTE/dist/img/user2-160x160.jpg') }}"
                    class="img-circle"
                    alt="User Image">
            </div>
            <div class="pull-left info">
                <p class="username">{{ $user->full_name }}</p>
                <p>{{  $user->company_id ? $user->company->company_name : "" }}</p> <br/>
                {{--                <a class="pt-5" href="javascript:void(0)"><i class="fa fa-circle text-success"></i> Online</a>--}}

            </div>
        </div>
        <!-- search form -->
    {{--<form action="#" method="get" class="sidebar-form">--}}
    {{--<div class="input-group">--}}
    {{--<input type="text" name="q" class="form-control" placeholder="Search...">--}}
    {{--<span class="input-group-btn">--}}
    {{--<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>--}}
    {{--</button>--}}
    {{--</span>--}}
    {{--</div>--}}
    {{--</form>--}}
    <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header text-uppercase">{{ __('common.profile_setting') }}</li>
            <li>
                <a href="{{ route('change_password') }}">
                    <i class="fa fa-key"></i> <span> {{ __('common.change_password') }} </span>
                </a>
            </li>
            <li class="header">{{ __('common.main_navigation') }}</li>
            <li>
                @php
                    $route = $user->hasRole(['admin','super-admin','developer']) ? 'admin' : 'member';
                    $route .= '.dashboard';
                @endphp
                <a href="{{ route($route) }}">
                    <i class="fa fa-dashboard"></i> <span>
                        {{ __('common.dashboard') }}
                    </span>
                </a>
            </li>


            @if(config('settings.warehouse') &&
            $user->can([
            'member.warehouse.index',
            'member.warehouse.create', 'member.warehouse.transfer',
            'member.warehouse.history.index','member.warehouse.history.transfer_list']
            ))
                <li class="treeview menu-open">
                    <a href="javascript:void(0)">
                        <i class="fa fa-industry"></i>
                        <span> {{__('common.warehouses')}}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="display: block;">

                        @if($user->can(['member.warehouse.index']))
                            <li><a href="{{ route('member.warehouse.index') }}"><i class="fa fa-industry"></i> {{__('common.warehouse')}}</a>
                            </li>
                        @endif

                        @if($user->can(['member.warehouse.create']))
                            <li><a href="{{ route('member.warehouse.create') }}"><i class="fa fa-plus"></i>{{__('common.add_warehouse')}}</a>
                            </li>
                        @endif

                        @if($user->can(['member.warehouse.transfer']))
                            <li><a href="{{ route('member.warehouse.transfer') }}"><i class="fa fa-plus"></i>{{__('common.warehouse_product_transfer')}}</a></li>
                        @endif

                        @if($user->can(['member.warehouse.history.index']))
                            <li><a href="{{ route('member.warehouse.history.index') }}"><i class="fa fa-list"></i>{{__('common.warehouse_store_history')}}</a></li>
                        @endif

                        @if($user->can(['member.warehouse.history.transfer_list']))
                            <li><a href="{{ route('member.warehouse.history.transfer_list') }}"><i
                                        class="fa fa-list"></i>Warehouse Transfer History</a></li>
                        @endif

                        @if($user->can(['member.warehouse.unload_not_done']))
                            <li><a href="{{ route('member.warehouse.unload_not_done') }}"><i class="fa fa-list"></i>{{__('common.warehouse_unload_not_complete')}}</a></li>
                            @endif

                        @if($user->can(['member.warehouse.load_not_done']))
                            <li><a href="{{ route('member.warehouse.load_not_done') }}"><i class="fa fa-list"></i>{{__('common.warehouse_load_not_complete')}}</a></li>
                        @endif
                    </ul>
                </li>
            @endif

            @if(config('settings.warehouse') &&
                      $user->can([
                      'member.report.daily_warehouse_stocks',
                      'member.report.warehouse_stocks', 'member.report.warehouse_total_stocks',
                      'member.report.warehouse_type_stocks']
                      ))
                <li class="treeview">
                    <a href="javascript:void(0)">
                        <i class="fa fa-industry"></i>
                        <span> {{ __('common.warehouse_stock_reports') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if($user->can(['member.report.daily_warehouse_stocks']))
                            <li>
                                <a href="{{ route('member.report.daily_warehouse_stocks') }}">  {{ __('common.warehouse_daily_stocks') }}</a>
                            </li>
                        @endif

                        @if($user->can(['member.report.warehouse_stocks']))
                            <li>
                                <a href="{{ route('member.report.warehouse_stocks') }}">   {{ __('common.warehouse_stocks') }}</a>
                            </li>
                        @endif

                        @if($user->can(['member.report.warehouse_total_stocks']))
                            <li>
                                <a href="{{ route('member.report.warehouse_total_stocks') }}"> {{ __('common.warehouse_stock_price') }} </a>
                            </li>
                        @endif

                        @if($user->can(['member.report.warehouse_type_stocks']))
                            <li>
                                <a href="{{ route('member.report.warehouse_type_stocks') }}">   {{ __('common.damage_overflow_stocks') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif


        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

