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

            @if(config('settings.quotation'))

                <li class="header">{{__('common.quotation')}}</li>

                @if($user->can(['member.quotations.create','member.quotations.index','member.quotations.add-others-transaction']))
                    <li class="treeview  menu-open">
                        <a href="#">
                            <i class="fa fa-list-alt"></i>
                            <span> {{ __('sale.quotation_title') }} </span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu" style="display: block;">

                            @if($user->can(['member.quotations.create']))
                                <li>
                                    <a href="{{ route('member.quotations.create') }}">{{ __('sale.new_quotation') }}</a>
                                </li>
                            @endif


                            @if($user->can(['member.quotations.index']))
                                <li>
                                    <a href="{{ route('member.quotations.index') }}">{{ __('sale.list_quotation') }}</a>
                                </li>
                            @endif


                            @if($user->can(['member.quotations.add-others-transaction']))
                                <li>
                                    <a href="{{ route('member.quotations.add-others-transaction') }}">{{ __('common.add_others_transaction') }}</a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif




                <li class="treeview menu-open">
                    <a href="#">
                        <i class="fa fa-list-alt"></i>
                        <span> {{ __('sale.quotation_title_setting') }} </span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="display: block;">


                        @if($user->can(['member.quotation_terms.index','member.quotation_terms.create',
                        'member.quotation_sub_terms.index','member.quotation_sub_terms.create']))
                            <li class="treeview">
                                <a href="#">
                                    <span> {{__('common.quotation_terms_condition')}} </span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    @if($user->can(['member.quotation_terms.create']))
                                        <li>
                                            <a href="{{ route('member.quotation_terms.create') }}">{{__('common.create_terms')}} </a>
                                        </li>
                                    @endif
                                    @if($user->can(['member.quotation_terms.index']))
                                        <li>
                                            <a href="{{ route('member.quotation_terms.index') }}">{{__('common.list_terms')}} </a>
                                        </li>
                                    @endif
                                    @if($user->can(['member.quotation_sub_terms.create']))
                                        <li>
                                            <a href="{{ route('member.quotation_sub_terms.create') }}">{{__('common.create_sub_terms')}} </a>
                                        </li>
                                    @endif
                                    @if($user->can(['member.quotation_sub_terms.index']))
                                        <li>
                                            <a href="{{ route('member.quotation_sub_terms.index') }}">{{__('common.list_sub_terms')}} </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        @if($user->can(['member.quote_company.index','member.quote_company.create',
                        'member.quote_attentions.index','member.quote_attentions.create']))

                            <li class="treeview">
                                <a href="#">
                                    <span> {{__('common.quotation_customer_company')}} </span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>

                                <ul class="treeview-menu">

                                    @if($user->can(['member.quote_company.create']))
                                        <li>
                                            <a href="{{ route('member.quote_company.create') }}">{{__('common.create_quote_company')}}</a>
                                        </li>
                                    @endif
                                    @if($user->can(['member.quote_company.index']))
                                        <li>
                                            <a href="{{ route('member.quote_company.index') }}">{{__('common.list_quote_company')}} </a>
                                        </li>
                                    @endif
                                    @if($user->can(['member.quote_attentions.create']))
                                        <li>
                                            <a href="{{ route('member.quote_attentions.create') }}">{{__('common.create_quote_customer')}} </a>
                                        </li>
                                    @endif
                                    @if($user->can(['member.quote_attentions.index']))
                                        <li>
                                            <a href="{{ route('member.quote_attentions.index') }}">{{__('common.list_quote_customer')}} </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        @if($user->can(['member.quoting.index','member.quoting.create']))

                            <li class="treeview ">
                                <a href="#">
                                    <span> {{__('common.quoting_persons')}} </span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    @if($user->can(['member.quoting.create']))
                                        <li>
                                            <a href="{{ route('member.quoting.create') }}"> {{__('common.create_quoting_person')}}</a>
                                        </li>
                                    @endif

                                    @if($user->can(['member.quoting.index']))
                                        <li>
                                            <a href="{{ route('member.quoting.index') }}"> {{__('common.list_quoting_person')}}</a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif


        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

