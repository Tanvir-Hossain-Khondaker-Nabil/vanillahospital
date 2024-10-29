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
            <li class="header">{{__('common.main_navigation')}}</li>
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

            @if($user->can(['super-admin', 'admin']))


                @if($user->can(['member.settings.general_settings', 'member.settings.company_fiscal_year']))
                    <li class="treeview">

                        <a href="#">
                            <i class="fa fa-gears"></i> <span>{{ __('common.settings') }}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('member.settings.general_settings') }}"><i
                                        class="fa fa-cog"></i> {{ __('common.general_settings') }}</a></li>
                            <li><a href="{{ route('member.settings.company_fiscal_year') }}"><i
                                        class="fa fa-cog"></i> {{ __('common.set_company_fiscal_year') }}</a></li>

                        </ul>
                    </li>
                @endif


                @if(config('settings.accounts') && $user->can(['member.fiscal_year.index','member.fiscal_year.create']))

                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-calendar"></i> <span>{{ __('common.fiscal_years') }}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">


                            @if($user->can(['member.fiscal_year.index']))
                                <li><a href="{{ route('member.fiscal_year.index') }}"><i
                                            class="fa fa-calendar"></i> {{ __('common.fiscal_years_list') }}</a></li>
                            @endif
                            @if($user->can(['member.fiscal_year.index']))
                                <li><a href="{{route('member.fiscal_year.create') }}"><i
                                            class="fa fa-calendar-plus-o"></i> {{ __('common.fiscal_year_add') }} </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(config('settings.banks') && $user->can(['member.banks.index','member.banks.create']))

                    <li class="treeview">
                        <a href="javascript:void(0)">
                            <i class="fa fa-building"></i>
                            <span>{{ __('common.manage_banks') }}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            @if($user->can(['member.banks.index']))
                                <li><a href="{{ route('member.banks.index') }}">
                                        <i class="fa fa-building-o"></i> {{ __('common.banks') }} </a></li>
                            @endif
                            @if($user->can(['member.banks.create']))
                                <li><a href="{{ route('member.banks.create') }}">
                                        <i class="fa fa-plus"></i> {{ __('common.add_bank') }}</a></li>
                            @endif
                        </ul>
                    </li>

                @endif

                @if(config('settings.banks') && $user->can(['member.bank_branch.index','member.bank_branch.create']))

                    <li class="treeview">
                        <a href="javascript:void(0)">
                            <i class="fa fa-building"></i>
                            <span>{{ __('common.bank_branches') }}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            @if($user->can(['member.bank_branch.index']))
                                <li><a href="{{ route('member.bank_branch.index') }}">
                                        <i class="fa fa-building-o"></i> {{ __('common.list') }} </a></li>
                            @endif
                            @if($user->can(['member.bank_branch.create']))
                                <li><a href="{{ route('member.bank_branch.create') }}">
                                        <i class="fa fa-plus"></i> {{ __('common.create') }}</a></li>
                            @endif
                        </ul>
                    </li>

                @endif


            @endif


            @if(config('settings.areas'))


                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-map-o"></i>
                        <span>{{ __('common.manage_locations') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">


                        @if($user->can(['member.divisions.index','member.divisions.create']))

                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-map-o"></i>
                                    <span>{{ __('common.states') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if($user->can(['member.divisions.index']))
                                        <li><a href="{{ route('member.divisions.index') }}"><i
                                                    class="fa fa-map"></i> {{ __('common.list') }}</a></li>
                                    @endif


                                    @if($user->can(['member.divisions.create']))
                                        <li><a href="{{ route('member.divisions.create') }}"><i
                                                    class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                                    @endif
                                </ul>
                            </li>

                        @endif


                        @if($user->can(['member.regions.index','member.regions.create']))
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-map-o"></i>
                                    <span>{{ __('common.regions') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if($user->can(['member.regions.index']))
                                        <li><a href="{{ route('member.regions.index') }}">
                                                <i class="fa fa-map"></i> {{ __('common.list') }}</a></li>
                                    @endif

                                    @if($user->can(['member.regions.create']))
                                        <li><a href="{{ route('member.regions.create') }}">
                                                <i class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                                    @endif
                                </ul>
                            </li>

                        @endif

                        @if($user->can(['member.districts.index','member.districts.create']))
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-map-o"></i>
                                    <span>{{ __('common.cities') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if($user->can(['member.districts.index']))
                                        <li><a href="{{ route('member.districts.index') }}">
                                                <i class="fa fa-map"></i> {{ __('common.list') }}</a></li>
                                    @endif

                                    @if($user->can(['member.districts.create']))
                                        <li><a href="{{ route('member.districts.create') }}">
                                                <i class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                                    @endif

                                </ul>
                            </li>
                        @endif


                        @if($user->can(['member.thanas.index','member.thanas.create']))
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-map-o"></i>
                                    <span>{{ __('common.thanas') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if($user->can(['member.thanas.index']))
                                        <li><a href="{{ route('member.thanas.index') }}">
                                                <i class="fa fa-map"></i> {{ __('common.list') }}</a></li>
                                    @endif

                                    @if($user->can(['member.thanas.create']))
                                        <li><a href="{{ route('member.thanas.create') }}">
                                                <i class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif


                        @if($user->can(['member.area.index','member.area.create']))
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-map-o"></i>
                                    <span>{{ __('common.areas') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if($user->can(['member.area.index']))
                                        <li><a href="{{ route('member.area.index') }}">
                                                <i class="fa fa-map"></i> {{ __('common.list') }}</a></li>
                                    @endif


                                    @if($user->can(['member.area.create']))
                                        <li><a href="{{ route('member.area.create') }}">
                                                <i class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif


                    </ul>
                </li>
            @endif



            @if(config('settings.imports') && $user->can(['admin.product_import.create', 'admin.sharer_import.create']))

                <li class="treeview">
                    <a href="#">

                        <i class="fa fa-upload"></i> <span>{{ __('common.import') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if($user->can(['admin.product_import.create']))
                            <li><a href="{{ route('admin.product_import.create') }}"><i
                                        class="fa fa-upload"></i> {{ __('common.product_import') }}</a></li>
                        @endif

                        @if($user->can(['admin.sharer_import.create']))
                            <li><a href="{{ route('admin.sharer_import.create') }}"><i
                                        class="fa fa-upload"></i> {{ __('common.sharer_import') }}</a></li>
                        @endif

                    </ul>
                </li>
            @endif


            @if($user->can(['member.users.index', 'member.users.create', 'member.users.set_users_company']))

                <li class="treeview">

                    <a href="#">
                        <i class="fa fa-users"></i> <span>{{ __('common.users') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if($user->can(['member.users.index']))

                            <li><a href="{{ route('member.users.index') }}">
                                    <i class="fa fa-user"></i> {{ __('common.users') }}</a></li>

                        @endif

                        @if($user->can([ 'member.users.create']))
                            <li><a href="{{ route('member.users.create') }}">
                                    <i class="fa fa-user-plus"></i> {{ __('common.add_user') }} </a></li>

                        @endif


                        @if($user->can(['member.users.set_users_company']) && config('settings.multi_company'))

                            <li><a href="{{ route('member.users.set_users_company') }}">
                                    <i class="fa fa-user-plus"></i> {{ __('common.assign_user_company') }}</a></li>
                        @endif
                    </ul>
                </li>
            @endif


            @if($user->can(['admin.roles.index', 'admin.roles.create']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-unlock-alt"></i> <span>{{ __('common.roles') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if($user->can(['admin.roles.index']))
                            <li><a href="{{ route('admin.roles.index') }}">
                                    <i class="fa fa-unlock-alt"></i> {{ __('common.roles') }}</a></li>
                        @endif

                        @if($user->can(['admin.roles.create']))
                            <li><a href="{{ route('admin.roles.create') }}"><i class="fa fa-plus"></i>
                                    {{ __('common.add_role') }} </a></li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($user->hasRole(['super-admin']) && $user->can(['member.company.index','member.company.create','member.company.edit']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-building"></i>
                        <span>{{ __('common.manage_companies') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if(config('settings.multi_company'))

                            @if($user->can(['member.company.index']))
                                <li><a href="{{ route('member.company.index') }}">
                                        <i class="fa fa-industry"></i> {{ __('common.companies') }}</a></li>

                            @endif

                            @if($user->can(['member.company.create']))
                                <li><a href="{{ route('member.company.create') }}"><i
                                            class="fa fa-plus"></i> {{ __('common.add_company') }} </a></li>
                            @endif

                        @else

                            @if(!is_null($user->company_id))
                                @php
                                    $company = $user->company_id;
                                @endphp
                            @else
                                @php
                                    $company = 1;
                                @endphp
                            @endif

                            @if($user->can(['member.company.edit']))
                                <li>
                                    <a href="{{ route( 'member.company.edit', $company) }}">
                                        <i class="fa fa-building-o"></i>
                                        <span>Edit  Company Info </span>
                                    </a>
                                </li>
                            @endif

                        @endif


                        @if($user->can(['member.company.feature']) )
                            <li><a href="{{ route('member.company.feature') }}"><i
                                        class="fa fa-industry"></i> {{ __('Set Company Feature') }}</a></li>
                        @endif

                    </ul>
                </li>

            @elseif($user->can(['member.company.edit']))
                <li>
                    <a href="{{ route( 'member.company.edit', $user->company_id) }}">
                        <i class="fa fa-building"></i>
                        <span>{{ __('common.manage_companies') }}</span>
                    </a>
                </li>
            @endif


            @if($user->hasRole(['super-admin']) && (config('app.feature.member') || config('app.feature.package')))

                @if(config('app.feature.member'))
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-id-card"></i> <span>Members</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.members.index') }}"><i class="fa fa-user"></i> Members</a></li>
                            <li><a href="{{ route('admin.members.create') }}"><i class="fa fa-user-plus"></i> Add Member</a>
                            </li>
                        </ul>
                    </li>
                @endif

                {{--                <li class="treeview">--}}
                {{--                    <a href="javascript:void(0)">--}}
                {{--                        <i class="fa fa-address-card"></i> <span>Membership Feature</span>--}}
                {{--                        <i class="fa fa-angle-left pull-right"></i>--}}
                {{--                    </a>--}}
                {{--                    <ul class="treeview-menu">--}}
                {{--                        <li><a href="{{ route('admin.memberships.index') }}"><i class="fa fa-user"></i> Memberships</a></li>--}}
                {{--                        <li><a href="{{ route('admin.memberships.create') }}"><i class="fa fa-user-plus"></i> Add Membership</a></li>--}}
                {{--                    </ul>--}}
                {{--                </li>--}}

                @if(config('app.feature.package'))
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-address-card"></i> <span>Packages Feature</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            <li><a href="{{ route('admin.packages.index') }}"><i class="fa fa-user"></i> Packages</a>
                            </li>
                            <li><a href="{{ route('admin.packages.create') }}"><i class="fa fa-user-plus"></i> Add Package</a></li>

                        </ul>
                    </li>
                @endif

            @else
                @if($user->email == "superadmin@hisebi.com")
                    <li>
                        <a href="{{ route('admin.members.edit', $user->member_id) }}">
                            <i class="fa fa-address-card"></i> <span>Membership Update</span>
                        </a>
                    </li>

                @endif
            @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

