<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/26/2019
 * Time: 11:39 AM
 */
?>
<header class="main-header">
@php
    $route = Auth::user()->hasRole(['admin','super-admin','developer']) ? 'admin' : 'member';
    $route .= '.dashboard';
@endphp
    <!-- Logo -->
    <a href="{{ route($route) }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><?= header_shortname(config('app.name', '') ) ?></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">{{ human_words(config('app.name')) }}</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div style="float: left" class="rmm style">
            <ul>

                <li>
                    @php
                        $route = Auth::user()->hasRole(['admin','super-admin','developer']) ? 'admin' : 'member';
                        $route .= '.dashboard';
                    @endphp
                    <a href="{{ route($route) }}">
                        <i class="fa fa-dashboard"></i>
                        <span> {{ __('common.dashboard') }}</span>
                    </a>
                </li>


                @if(config('settings.accounts'))
                @if(Auth::user()->can(['super-admin', 'admin', 'accountant']))
                <li>
                    <a href="#">
                        <i class="fa fa-database"></i>
                        <span>{{ __('transaction.title') }}</span>
                    </a>
                    <ul >
                        <li><a href="{{ route('member.transaction.create', 'Payment') }}"><i class="fa fa-long-arrow-right"></i> {{ __('transaction.payment_transaction') }}</a></li>
                        <li><a href="{{ route('member.transaction.create', 'Deposit') }}"><i class="fa fa-long-arrow-left"></i> {{ __('transaction.receive_transaction') }}</a></li>
{{--                        <li><a href="{{ route('member.transaction.create', 'Expense') }}"><i class="fa fa-long-arrow-left"></i> Expense</a></li>--}}
{{--                        <li><a href="{{ route('member.transaction.transfer.create') }}"><i class="fa fa-long-arrow-left"></i> Transfer</a></li>--}}
{{--                        <li><a href="{{ route('member.transaction.transfer.list') }}"><i class="fa fa-list"></i> Transfer List</a></li>--}}
                        <li><a href="{{ route('member.transaction.index') }}"><i class="fa fa-list"></i> {{ __('transaction.list_transaction') }} </a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-book"></i>
                        <span> {{ __('common.general_ledger') }}</span>
                    </a>
                    <ul >
                        <li><a href="{{ route('member.general_ledger.search') }}"><i class="fa fa-list"></i> {{ __('common.ledger_list') }}</a></li>
{{--                        <li><a href="{{ route('member.general_ledger.list_ledger') }}"><i class="fa fa-list-alt"></i> All Ledger </a></li>--}}
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-university"></i>
                        <span>  {{ __('common.journal_entry') }} </span>
                    </a>
                    <ul >
                        <li><a href="{{ route('member.journal_entry.create') }}"> {{ __('common.add_journal_entry') }}</a></li>
                        <li><a href="{{ route('member.journal_entry.index') }}"> {{ __('common.journal_entry_list') }}</a></li>
                    </ul>
                </li>
                @endif
                @endif

                @if(config('settings.pos'))

                @if(Auth::user()->can(['super-admin', 'admin','create-sale','list-sales', 'sales-due-list','sales-return-list', 'sales']))

                <li>
                    <a href="#">
                        <i class="fa fa-shopping-cart"></i>
                        <span> {{ __('sale.title') }} </span>
                    </a>
                    <ul>
                        <li><a href="{{ route('member.sales.whole_sale_create') }}">{{ __('sale.whole_sale') }}</a></li>
                        <li><a href="{{ route('member.sales.create') }}">{{ __('sale.create_sale') }}</a></li>
                        <li><a href="{{ route('member.sales.index') }}">{{ __('sale.list_sale') }}</a></li>
                        <li><a href="{{ route('member.sales.due_list') }}">{{ __('sale.due_sale') }}</a></li>
                        <li><a href="{{ route('member.sales.sales_return_list') }}">{{ __('sale.return_sale') }} </a></li>
                    </ul>
                </li>
                @endif

                @if(Auth::user()->can(['super-admin','admin','accountant','create-purchase','list-purchase', 'purchase-due-list','purchase-return-list','purchase']))

                <li >
                    <a href="#">
                        <i class="fa fa-shopping-bag"></i>
                        <span> {{ __('purchase.title') }} </span>
                    </a>
                    <ul >
                        <li><a href="{{ route('member.purchase.create') }}">{{ __('purchase.new_purchase') }}</a></li>
                        <li><a href="{{ route('member.purchase.index') }}">{{ __('purchase.list_purchase') }}</a></li>
{{--                        <li><a href="{{ route('member.purchase.due_list') }}">{{ __('purchase.due_purchase') }}</a></li>--}}
                        <li><a href="{{ route('member.purchase_return.index') }}">{{ __('purchase.return_purchase') }}</a></li>
                    </ul>
                </li>
                @endif
                @endif
            </ul>
        </div>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a class="text-uppercase" href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {{ \App::getLocale() }}
                    </a>
                    <ul class="dropdown-menu" style="min-width: 80px;">
                        <li> <a href="{{ route('lang', 'en') }}">EN </a></li>
                        <li> <a href="{{ route('lang', 'bn') }}">BN </a></li>
                    </ul>
                </li>

                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success">4</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 4 messages</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><!-- start message -->
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="{{ !empty(auth()->user()->photo) ? auth()->user()->profile_photo :  asset('public/adminLTE/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Support Team
                                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <!-- end message -->
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="{{ asset('public/adminLTE/dist/img/user3-128x128.jpg') }}" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            adminLTE Design Team
                                            <small><i class="fa fa-clock-o"></i> 2 hours</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="{{ asset('public/adminLTE/dist/img/user4-128x128.jpg') }}" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Developers
                                            <small><i class="fa fa-clock-o"></i> Today</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="{{ asset('public/adminLTE/dist/img/user3-128x128.jpg') }}" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Sales Department
                                            <small><i class="fa fa-clock-o"></i> Yesterday</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="{{ asset('public/adminLTE/dist/img/user4-128x128.jpg') }}" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Reviewers
                                            <small><i class="fa fa-clock-o"></i> 2 days</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">See All Messages</a></li>
                    </ul>
                </li>
                <!-- Notifications: style can be found in dropdown.less -->
{{--                <li class="dropdown notifications-menu">--}}
{{--                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
{{--                        <i class="fa fa-bell-o"></i>--}}
{{--                        <span class="label label-warning">10</span>--}}
{{--                    </a>--}}
{{--                    <ul class="dropdown-menu">--}}
{{--                        <li class="header">You have 10 notifications</li>--}}
{{--                        <li>--}}
{{--                            <!-- inner menu: contains the actual data -->--}}
{{--                            <ul class="menu">--}}
{{--                                <li>--}}
{{--                                    <a href="#">--}}
{{--                                        <i class="fa fa-users text-aqua"></i> 5 new members joined today--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <a href="#">--}}
{{--                                        <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the--}}
{{--                                        page and may cause design problems--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <a href="#">--}}
{{--                                        <i class="fa fa-users text-red"></i> 5 new members joined--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <a href="#">--}}
{{--                                        <i class="fa fa-shopping-cart text-green"></i> 25 sales made--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <a href="#">--}}
{{--                                        <i class="fa fa-user text-red"></i> You changed your username--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                        <li class="footer"><a href="#">View all</a></li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
                <!-- Tasks: style can be found in dropdown.less -->
{{--                <li class="dropdown tasks-menu">--}}
{{--                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
{{--                        <i class="fa fa-flag-o"></i>--}}
{{--                        <span class="label label-danger">9</span>--}}
{{--                    </a>--}}
{{--                    <ul class="dropdown-menu">--}}
{{--                        <li class="header">You have 9 tasks</li>--}}
{{--                        <li>--}}
{{--                            <!-- inner menu: contains the actual data -->--}}
{{--                            <ul class="menu">--}}
{{--                                <li><!-- Task item -->--}}
{{--                                    <a href="#">--}}
{{--                                        <h3>--}}
{{--                                            Design some buttons--}}
{{--                                            <small class="pull-right">20%</small>--}}
{{--                                        </h3>--}}
{{--                                        <div class="progress xs">--}}
{{--                                            <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"--}}
{{--                                                 aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">--}}
{{--                                                <span class="sr-only">20% Complete</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <!-- end task item -->--}}
{{--                                <li><!-- Task item -->--}}
{{--                                    <a href="#">--}}
{{--                                        <h3>--}}
{{--                                            Create a nice theme--}}
{{--                                            <small class="pull-right">40%</small>--}}
{{--                                        </h3>--}}
{{--                                        <div class="progress xs">--}}
{{--                                            <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar"--}}
{{--                                                 aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">--}}
{{--                                                <span class="sr-only">40% Complete</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <!-- end task item -->--}}
{{--                                <li><!-- Task item -->--}}
{{--                                    <a href="#">--}}
{{--                                        <h3>--}}
{{--                                            Some task I need to do--}}
{{--                                            <small class="pull-right">60%</small>--}}
{{--                                        </h3>--}}
{{--                                        <div class="progress xs">--}}
{{--                                            <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar"--}}
{{--                                                 aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">--}}
{{--                                                <span class="sr-only">60% Complete</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <!-- end task item -->--}}
{{--                                <li><!-- Task item -->--}}
{{--                                    <a href="#">--}}
{{--                                        <h3>--}}
{{--                                            Make beautiful transitions--}}
{{--                                            <small class="pull-right">80%</small>--}}
{{--                                        </h3>--}}
{{--                                        <div class="progress xs">--}}
{{--                                            <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar"--}}
{{--                                                 aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">--}}
{{--                                                <span class="sr-only">80% Complete</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <!-- end task item -->--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                        <li class="footer">--}}
{{--                            <a href="#">View all tasks</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ !empty(auth()->user()->photo) ? auth()->user()->profile_photo :  asset('public/adminLTE/dist/img/user2-160x160.jpg') }}" class="user-image" alt="User Image">
                        <span class="hidden-xs username">{{ Auth::user()->full_name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img width="65px" height="65px" src="{{ !empty(auth()->user()->photo) ? auth()->user()->profile_photo :  asset('public/adminLTE/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">

                            <p class="username my-2">
                                {{ Auth::user()->full_name }}
{{--                                <small>Member since Nov. 2012</small>--}}
                            </p>
                            <p class="my-1 h5">{{  Auth::user()->company_id ? Auth::user()->company->company_name : "" }}</p>
                            <p class="my-1 h5">Support Pin: {{ Auth::user()->help_pin }}</p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ route('auth.profile') }}" class="btn btn-default btn-flat">Edit Profile</a>
                            </div>
                            <div class="pull-right">
                                <a class="btn btn-default btn-flat" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Sign out') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
{{--                <li>--}}
{{--                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>--}}
{{--                </li>--}}
            </ul>
        </div>
    </nav>
</header>
