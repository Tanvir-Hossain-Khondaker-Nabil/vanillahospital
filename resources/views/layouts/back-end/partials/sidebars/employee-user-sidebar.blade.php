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
                    // dd($route);
                @endphp
                <a href="{{ route($route) }}">
                    <i class="fa fa-dashboard"></i> <span>
                       {{ __('common.dashboard') }}
                    </span>
                </a>
            </li>

            <li>
                <a href="{{route('users.user_profile')}}">
                    <i class="fa fa-user"></i><span>{{ __('Profile') }}</span>
                    {{-- <i class="fa fa-angle-left pull-right"></i> --}}
                </a>
            </li>

            <li>
                <a href="{{route('member.users.user_task')}}">
                    <i class="fa fa-tasks"></i><span>{{ __('Task') }}</span>
                    {{-- <i class="fa fa-angle-left pull-right"></i> --}}
                </a>
            </li>


            <li >
                <a href="{{route('member.users.user_project')}}">
                    <i class="fa fa-file-text-o"></i><span>{{ __('Project') }}</span>
                    {{-- <i class="fa fa-angle-left pull-right"></i> --}}
                </a>
            </li>

            <li>
                <a href="{{route('member.employee-leaves.create')}}">
                    <i class="fa fa-calendar-minus-o"></i><span>{{ __('Set Leave') }}</span>


                </a>
            </li>

            <li>
                <a href="{{route('member.employee-leaves.index')}}">
                    <i class="fa fa-calendar"></i><span>{{ __('List Leaves') }}</span>

                </a>
            </li>

            <li class="treeview">
                <a href="#">
                    {{-- <i class="fa fa-torii-gate"></i> --}}
                    <i class="fa fa-support"></i><span>{{ __('Supports') }}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">

                    {{-- @if($user->can(['member.support.index'])) --}}
                        <li><a href="{{ route('member.support.index') }}">
                                <i class="fa fa-support"></i> {{ __('Support List') }}</a>
                        </li>
                    {{-- @endif --}}

                    {{-- @if($user->can(['member.support.create'])) --}}
                        <li><a href="{{ route('member.support.create') }}">
                                <i class="fa fa-plus-circle"></i> {{ __('Support Request') }}</a>
                        </li>
                    {{-- @endif --}}
                </ul>
            </li>

            @if($user->can(['member.requisition_expenses.index','member.requisition_expenses.create']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-briefcase"></i><span>{{ __('Requisition Expenses') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if($user->can(['member.requisition_expenses.index']))
                            <li><a href="{{ route('member.requisition_expenses.index') }}"><i
                                        class="fa fa-briefcase"></i> {{ __('List') }}</a></li>
                        @endif

                        @if($user->can(['member.requisition_expenses.create']))
                            <li><a href="{{ route('member.requisition_expenses.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('Create') }} </a></li>
                        @endif
                    </ul>
                </li>
            @endif
            <li>
                <a href="{{route('member.salary_management.employee-data')}}">
                    <i class="fa fa-dollar"></i><span>{{ __('Salary History') }}</span>
                </a>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

