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
            <li class="header">MAIN NAVIGATION</li>
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

            @if(config('settings.requisition'))

                <li class="header">Inventory</li>

                @if($user->can(['member.purchase-requisition.index']))
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-shopping-cart"></i>
                            <span> {{ __('purchase.title') }} </span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <a href="{{ route('member.purchase-requisition.index') }}">{{ __('purchase.purchase_by_requisition') }}</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if($user->can(['member.sales.by-requisition']))
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-shopping-bag"></i>
                            <span> {{ __('sale.title') }} </span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            <li>
                                <a href="{{ route('member.sales.by-requisition') }}">{{ __('sale.sale_by_requisition') }}</a>
                            </li>
                        </ul>
                    </li>
                @endif
            @endif

            @if($user->can(['member.requisition.create','member.requisition.index', 'member.purchase.from-requisition']))

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-shopping-basket"></i>
                        <span> {{ __('purchase.requisition_title') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if($user->can(['member.requisition.create']))
                            <li>
                                <a href="{{ route('member.requisition.create') }}">{{ __('purchase.new_requisition') }}</a>
                            </li>
                        @endif

                        @if($user->can(['member.requisition.index']))
                            <li>
                                <a href="{{ route('member.requisition.index') }}">{{ __('purchase.list_requisition') }}</a>
                            </li>
                        @endif

                        @if($user->can(['member.purchase.from-requisition']))
                            <li>
                                <a href="{{ route('member.purchase.from-requisition') }}">{{ __('purchase.purchase_from_requisition') }}</a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif

            @if($user->can([
                    'member.dealer_sales.index',
                    'member.dealer_sales.create'
            ]))

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-list-alt"></i>
                        <span> {{ __('Sales') }} </span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if($user->hasRole('dealer') || $user->can(['member.dealer_sales.index']))
                            <li><a href="{{ route('member.dealer_sales.index') }}">
                                    @if(!$user->hasRole('dealer'))
                                        Dealer
                                    @endif

                                    {{ __('Sales') }}
                                </a></li>
                        @endif


                    </ul>
                </li>
            @endif


            @if($user->can([
                    'member.sales_requisitions.create',
                    'member.sales_requisitions.index',
                    'member.sales_requisitions.dealer_index',
                    'member.sales.from-requisition'
            ]))

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-list-alt"></i>
                        <span> {{ __('sale.requisition_title') }} </span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if($user->can(['member.sales_requisitions.create']))
                            <li>
                                <a href="{{ route('member.sales_requisitions.create') }}">{{ __('sale.new_requisition') }}</a>
                            </li>
                        @endif

                        @if($user->can(['member.sales_requisitions.index']))
                            <li>
                                <a href="{{ route('member.sales_requisitions.index') }}">{{ __('sale.list_requisition') }}</a>
                            </li>
                        @endif

                        @if($user->can(['member.sales_requisitions.dealer_index']))

                            <li>
                                <a href="{{ route('member.sales_requisitions.dealer_index') }}">{{ __('sale.list_requisition_sale_man') }}</a>
                            </li>
                        @endif

                        @if($user->can(['member.sales.from-requisition']))
                            <li>
                                <a href="{{ route('member.sales.from-requisition') }}">{{ __('sale.sale_from_requisition') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif


            @if($user->can(['member.report.dealer_daily_stocks', 'member.report.dealer_stocks','member.report.daily_stock_by_rsrd','member.report.requistion_report', 'member.report.salesman_requistion_report']))

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-bar-chart"></i>
                        <span>{{ __('report.title') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">


                        @if($user->can(['member.report.dealer_daily_stocks', 'member.report.dealer_stocks']))

                            <li class="treeview">
                                <a href="#">
                                    <span> {{ __('report.stock_reports') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if( $user->can([ 'member.report.dealer_daily_stocks']))
                                        <li> <a href="{{ route('member.report.dealer_daily_stocks') }}">
                                                @if(!$user->hasRole('dealer'))
                                                    Dealer
                                                @endif
                                                {{ __('report.daily_stocks') }}</a>  </li>
                                    @endif

                                    @if( $user->can([ 'member.report.dealer_stocks']))
                                        <li><a href="{{ route('member.report.dealer_stocks') }}">
                                                @if(!$user->hasRole('dealer'))
                                                    Dealer
                                                @endif
                                                    {{ __('report.stocks') }}</a> </li>
                                    @endif


                                </ul>
                            </li>
                        @endif



                        @if($user->can(['member.report.requistion_report', 'member.report.salesman_requistion_report']))

                            <li class="treeview">
                                <a href="#">
                                    <span> {{ __('Requisition Report') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    @if($user->can(['member.report.requistion_report']))
                                        <li>
                                            <a href="{{ route('member.report.requistion_report') }}">  {{ __('Summary Report') }}</a>
                                        </li>
                                    @endif
                                    @if($user->can(['member.report.salesman_requistion_report']))
                                        <li>
                                            <a href="{{ route('member.report.salesman_requistion_report') }}">  {{ __('Salesman Requisition Report') }}</a>
                                        </li>

                                    @endif

                                </ul>
                            </li>
                        @endif

                        @if($user->can(['member.report.daily_stock_by_rsrd']))

                            <li class="treeview">
                                <a href="#">
                                    <span> {{ __('report.summary_reports') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    {{--<li>--}}
                                    {{--<a href="{{ route('member.report.daily_stock_report_by_requisition_damage') }}"> {{ __('Daily RSRD Report') }} </a>--}}
                                    {{--</li>--}}
                                    <li>
                                        <a href="{{ route('member.report.daily_stock_by_rsrd') }}"> {{ __('Daily Actual Sale RSRD Report') }} </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif




            @if($user->can(['member.customers.index', 'member.customers.create']))
                <li class="treeview">
                    <a href="javascript:void(0)">
                        <i class="fa fa-users"></i>
                        <span> Dealer Shopkeepers</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if($user->can(['member.customers.index']))
                            <li><a href="{{ route('member.customers.index') }}"><i class="fa fa-users"></i> Shopkeepers</a>
                            </li>
                        @endif
                        @if($user->can(['member.customers.create' ]))
                            <li><a href="{{ route('member.customers.create', 'customer') }}"><i class="fa fa-plus"></i>  Add Shopkeeper</a></li>
                        @endif
                    </ul>
                </li>
            @endif


        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

