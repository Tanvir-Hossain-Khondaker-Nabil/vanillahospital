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


            @if($user->can(['member.project_category.index', 'member.project_category.create']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-file-text"></i><span>{{ __('common.project_category') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if($user->can([ 'member.project_category.index']))
                        <li><a href="{{ route('member.project_category.index') }}">
                                <i class="fa fa-building"></i> {{ __('common.list') }}</a></li>

                        @endif

                        @if($user->can([ 'member.project_category.create']))
                        <li><a href="{{ route('member.project_category.create') }}">
                                <i class="fa fa-plus"></i>{{ __('common.create') }} </a></li>

                            @endif

                    </ul>
                </li>

@endif
            @if($user->can(['member.project.index', 'member.project.create']))

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-file-text-o"></i><span>{{ __('common.project') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">


                        @if($user->can([ 'member.project.index']))
                        <li><a href="{{ route('member.project.index') }}"><i
                                    class="fa fa-building"></i> {{ __('common.list') }}</a>
                        </li>

                        @endif

                        @if($user->can([ 'member.project.create']))
                        <li><a href="{{ route('member.project.create') }}"><i
                                    class="fa fa-plus"></i> {{ __('common.create') }}</a>
                        </li>
                        @endif

                    </ul>
                </li>

            @endif


            @if($user->can(['member.lead_category.index', 'member.lead_category.create']))

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-th-large"></i><span>{{ __('common.lead_category') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">


                        @if($user->can([ 'member.lead_category.index']))
                        <li><a href="{{ route('member.lead_category.index') }}"><i
                                    class="fa fa-th-large"></i> {{ __('common.list') }}</a></li>

                        @endif

                        @if($user->can([ 'member.lead_category.create']))
                        <li><a href="{{ route('member.lead_category.create') }}">
                                <i class="fa fa-plus"></i>{{ __('common.create') }} </a></li>

                        @endif

                    </ul>
                </li>

            @endif



            @if($user->can(['member.client.index', 'member.client.create']))

                <li class="treeview">
                    <a href="#">
                        {{-- <i class="fa fa-torii-gate"></i> --}}
                        <i class="fa fa-male"></i><span>{{ __('common.client') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if($user->can([ 'member.client.index']))
                        <li><a href="{{ route('member.client.index') }}"><i
                            class="fa fa-male"></i> {{ __('common.list') }}</a>
                        </li>

                        @endif

                        @if($user->can([ 'member.client.create']))
                        <li><a href="{{ route('member.client.create') }}"><i
                            class="fa fa-plus"></i> {{ __('common.create') }}</a>
                        </li>
                        @endif

                    </ul>
                </li>


            @endif


            @if($user->can(['member.client_company.index', 'member.client_company.create']))
                <li class="treeview">
                    <a href="#">
                        {{-- <i class="fa fa-torii-gate"></i> --}}
                        <i class="fa fa-building-o"></i><span>{{ __('common.client_company') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">


                        @if($user->can([ 'member.client_company.index']))
                        <li><a href="{{ route('member.client_company.index') }}"><i
                            class="fa fa-building-o"></i> {{ __('common.list') }}</a>
                        </li>

                        @endif

                        @if($user->can([ 'member.client_company.create']))
                        <li><a href="{{ route('member.client_company.create') }}"><i
                            class="fa fa-plus"></i> {{ __('common.create') }}</a>
                        </li>
                        @endif

                    </ul>
                </li>


            @endif


            @if($user->can(['member.task.index', 'member.task.create']))
                 <li class="treeview">
                    <a href="#">
                        {{-- <i class="fa fa-torii-gate"></i> --}}
                        <i class="fa fa-tasks"></i><span>{{ __('common.tasks') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if($user->can(['member.task.index']))
                        <li><a href="{{ route('member.task.index') }}"><i
                            class="fa fa-tasks"></i> {{ __('common.list') }}</a>
                        </li>

                        @endif

                            @if($user->can([ 'member.task.create']))
                        <li><a href="{{ route('member.task.create') }}"><i
                            class="fa fa-plus"></i> {{ __('common.create') }}</a>
                        </li>
                            @endif

                    </ul>
                </li>



            @endif


            @if($user->can(['member.lead.index', 'member.lead.create']))

                <li class="treeview">
                    <a href="#">
                        {{-- <i class="fa fa-torii-gate"></i> --}}
                        <i class="fa fa-cubes"></i><span>{{ __('common.lead') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">



                        @if($user->can([ 'member.lead.index']))
                        <li><a href="{{ route('member.lead.index') }}"><i
                            class="fa fa-cubes"></i> {{ __('common.list') }}</a>
                        </li>


                        @endif

                        @if($user->can([ 'member.lead.create']))
                        <li><a href="{{ route('member.lead.create') }}"><i
                            class="fa fa-plus-square"></i> {{ __('common.create') }}</a>
                        </li>

                            @endif

                    </ul>
                </li>

            @endif



            @if($user->can(['member.sharer.broker_list', 'member.broker.create']))
                <li class="treeview">
                    <a href="javascript:void(0)">
                        <i class="fa fa-users"></i>
                        <span> {{ __('common.brokers') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if($user->can(['member.sharer.broker_list']))
                            <li><a href="{{ route('member.sharer.broker_list') }}"><i
                                        class="fa fa-users"></i> {{ __('common.broker_list') }}</a></li>
                        @endif

                        @if($user->can(['member.broker.create']))
                            <li><a href="{{ route('member.sharer.create', 'broker') }}"><i
                                        class="fa fa-plus"></i> {{ __('common.create_broker') }}</a></li>
                        @endif
                    </ul>
                </li>
            @endif


            @if($user->can(['member.project_expense_types.index', 'member.project_expense_types.create']))
                <li class="treeview">
                    <a href="javascript:void(0)">
                        <i class="fa fa-ticket"></i>
                        <span> {{ __('common.expenses_type') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if($user->can(['member.project_expense_types.index']))
                            <li><a href="{{ route('member.project_expense_types.index') }}"><i
                                        class="fa fa-ticket"></i> {{ __('common.expenses_list') }}</a></li>
                        @endif

                        @if($user->can(['member.project_expenses.create']))
                            <li><a href="{{ route('member.project_expense_types.create') }}"><i
                                        class="fa fa-plus"></i> {{ __('common.create_expense_type') }}</a></li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($user->can(['member.project_expenses.index', 'member.project_expenses.create']))
                <li class="treeview">
                    <a href="javascript:void(0)">
                        <i class="fa fa-money"></i>
                        <span> {{ __('common.project_expenses') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if($user->can(['member.project_expenses.index']))
                            <li><a href="{{ route('member.project_expenses.index') }}"><i
                                        class="fa fa-money"></i> {{ __('common.expenses_list') }}</a></li>
                        @endif

                        @if($user->can(['member.project_expenses.create']))
                            <li><a href="{{ route('member.project_expenses.create') }}"><i
                                        class="fa fa-plus"></i> {{ __('common.create_project_expense') }}</a></li>
                        @endif
                    </ul>
                </li>
            @endif


            @if($user->can(['member.report.project_expense_report', 'member.report.project_profit_report']))
                <li class="treeview">
                    <a href="javascript:void(0)">
                        <i class="fa fa-bar-chart"></i>
                        <span> {{ __('common.reports') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if($user->can(['member.report.project_expense_report']))
                            <li><a href="{{ route('member.report.project_expenses_report') }}"><i
                                        class="fa fa-bar-chart"></i> {{ __('common.project_expense_report') }}</a></li>
                        @endif
{{--                        @if($user->can(['member.report.project_profit_report']))--}}
{{--                            <li><a href="{{ route('member.report.project_profit_report') }}"><i--}}
{{--                                        class="fa fa-bar-chart"></i> {{ __('Project Profit Report') }}</a></li>--}}
{{--                        @endif--}}

                    </ul>
                </li>
            @endif

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

