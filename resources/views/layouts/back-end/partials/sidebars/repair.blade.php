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
                    src="{{ !empty(auth()->user()->photo) ? auth()->user()->profile_photo : asset('public/adminLTE/dist/img/user2-160x160.jpg') }}"
                    class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p class="username">{{ $user->full_name }}</p>
                <p>{{ $user->company_id ? $user->company->company_name : '' }}</p> <br/>
                {{--                <a class="pt-5" href="javascript:void(0)"><i class="fa fa-circle text-success"></i> Online</a> --}}

            </div>
        </div>
        <!-- search form -->
        {{-- <form action="#" method="get" class="sidebar-form"> --}}
        {{-- <div class="input-group"> --}}
        {{-- <input type="text" name="q" class="form-control" placeholder="Search..."> --}}
        {{-- <span class="input-group-btn"> --}}
        {{-- <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i> --}}
        {{-- </button> --}}
        {{-- </span> --}}
        {{-- </div> --}}
        {{-- </form> --}}
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
                    $route = $user->hasRole(['admin', 'super-admin', 'developer']) ? 'admin' : 'member';
                    $route .= '.dashboard';
                @endphp
                <a href="{{ route($route) }}">
                    <i class="fa fa-dashboard"></i> <span>
                        {{ __('common.dashboard') }}
                    </span>
                </a>
            </li>


            @if (config('settings.repair'))
                <li class="header">{{ __('common.maintainence_repair') }}</li>
            @endif



{{--            @if ($user->can(['member.repair_orders.index', 'member.repair_orders.create']))--}}
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-shopping-bag"></i>
                        <span>{{ __('common.repairing_orders') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

{{--                        @if ($user->can(['member.repair_orders.index']))--}}
                            <li><a href="{{ route('member.repair_orders.index') }}"><i class="fa fa-list"></i>
                                    {{ __('common.list') }}</a></li>
{{--                        @endif--}}

{{--                        @if ($user->can(['member.repair_orders.create']))--}}
                            <li><a href="{{ route('member.repair_orders.create') }}"><i
                                        class="fa fa-cart-plus"></i>{{ __('common.add') }} {{ __('common.order') }}
                                </a></li>
{{--                        @endif--}}
                    </ul>
                </li>
{{--            @endif--}}


            @if ($user->can(['super-admin', 'admin']))
                <li class="header">{{ __('common.manage_function') }}</li>
            @endif



            @if ($user->can(['member.services.index', 'member.services.create']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-magnet"></i>
                        <span>{{ __('common.service') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.services.index']))
                            <li><a href="{{ route('member.services.index') }}"><i class="fa fa-magnet"></i>
                                    {{ __('common.services') }}</a></li>
                        @endif

                        @if ($user->can(['member.services.create']))
                            <li><a href="{{ route('member.services.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.add') }} {{ __('common.service') }} </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif



            @if ($user->can(['member.defect_types.index', 'member.defect_types.create']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-snowflake-o"></i>
                        <span>{{ __('common.defect_types') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.defect_types.index']))
                            <li><a href="{{ route('member.defect_types.index') }}"><i class="fa fa-snowflake-o"></i>
                                    {{ __('common.defect_types') }}</a></li>
                        @endif

                        @if ($user->can(['member.defect_types.create']))
                            <li><a href="{{ route('member.defect_types.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.add') }} {{ __('common.defect_type') }}
                                </a></li>
                        @endif
                    </ul>
                </li>
            @endif


            @if ($user->can(['member.categories.index', 'member.categories.create']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-list"></i> <span>{{ __('common.categories') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if ($user->can(['member.categories.index']))
                            <li><a href="{{ route('member.categories.index') }}"><i class="fa fa-unlock-alt"></i>
                                    {{ __('common.categories') }}</a></li>
                        @endif

                        @if ($user->can(['member.categories.create']))
                            <li><a href="{{ route('member.categories.create') }}"><i class="fa fa-plus"></i>
                                    {{ __('common.add_category') }}</a></li>
                        @endif

                    </ul>
                </li>

            @endif

            @if ($user->can(['member.items.create', 'member.items.print_barcode_form', 'member.items.index']))

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-product-hunt"></i> <span>{{ __('common.manage_products') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if ($user->can(['member.items.index']))
                            <li><a href="{{ route('member.items.index') }}"><i class="fa fa-unlock-alt"></i>
                                    {{ __('common.products') }}</a></li>
                        @endif

                        @if ($user->can(['member.items.create']))
                            <li>
                                <a href="{{ route('member.items.create') }}{{ config('settings.variant') ? '?variant=inactive' : '' }}"><i
                                        class="fa fa-plus"></i>
                                    {{ __('common.add_product') }}</a></li>

                            @if(config('settings.variant'))
                                <li><a href="{{ route('member.items.create') }}?variant=active"><i
                                            class="fa fa-plus"></i>
                                        {{ __('common.add_variant_product') }}</a></li>
                            @endif
                        @endif

                        @if ($user->can(['member.items.print_barcode_form']))
                            <li><a href="{{ route('member.items.print_barcode_form') }}"><i
                                        class="fa fa-barcode"></i> {{ __('common.barcode_print') }} </a></li>
                        @endif

                    </ul>
                </li>
            @endif


        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
