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
                <img src="{{ !empty(auth()->user()->photo) ? auth()->user()->profile_photo : asset('public/adminLTE/dist/img/user2-160x160.jpg') }}"
                    class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p class="username">{{ $user->full_name }}</p>
                <p>{{ $user->company_id ? $user->company->company_name : '' }}</p> <br />
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


            @if (config('settings.pos') || config('settings.inventory'))

            <li class="header">{{ __('common.inventory') }}</li>

                @if (
                    $user->can([
                        'member.purchase_return.index',
                        'member.purchase.create',
                        'member.purchase.index',
                        'member.purchase.due_list',
                    ]))
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-shopping-cart"></i>
                            <span> {{ __('purchase.title') }} </span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            @if ($user->can(['member.purchase.create']))
                                <li>
                                    <a
                                        href="{{ route('member.purchase.create') }}">{{ __('purchase.new_purchase') }}</a>
                                    {{-- <li><a href="{{ route('member.warehouse_purchases.create') }}">{{ __('Purchase For Warehouse') }}</a> --}}
                                </li>
                            @endif

                            @if ($user->can(['member.purchase.index']))
                                <li>
                                    <a
                                        href="{{ route('member.purchase.index') }}">{{ __('purchase.list_purchase') }}</a>
                                </li>
                            @endif

                            @if ($user->can(['member.purchase.due_list']))
                                <li>
                                    <a
                                        href="{{ route('member.purchase.due_list') }}">{{ __('purchase.due_purchase') }}</a>
                                </li>
                            @endif

                            @if ($user->can(['member.purchase_return.index']))
                                <li>
                                    <a
                                        href="{{ route('member.purchase_return.index') }}">{{ __('purchase.return_purchase') }}</a>
                                </li>
                            @endif

                        </ul>
                    </li>

                @endif

                @if (
                    $user->can([
                        'member.sales.create',
                        'member.sales.whole_sale_create',
                        'member.sales.pos_create',
                        'member.sales.index',
                        'member.sales.due_list',
                        'member.sales.sales_return_list',
                    ]))

                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-shopping-bag"></i>
                            <span> {{ __('sale.title') }} </span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            @if (config('settings.inventory') && $user->can(['member.sales.whole_sale_create']))
                                <li>
                                    <a
                                        href="{{ route('member.sales.whole_sale_create') }}">{{ __('sale.whole_sale') }}</a>
                                </li>
                            @endif

                            @if ($user->can(['member.sales.create']))
                                <li><a href="{{ route('member.sales.create') }}">{{ __('sale.create_sale') }}</a></li>
                            @endif

                            @if (config('settings.pos') && $user->can(['member.sales.pos_create']))
                                <li><a href="{{ route('member.sales.pos_create') }}">{{ __('sale.pos_sale') }}</a>
                                </li>
                            @endif

                            @if ($user->can(['member.sales.index']))
                                <li><a href="{{ route('member.sales.index') }}">{{ __('sale.list_sale') }} </a></li>
                            @endif


                            @if ($user->can(['member.sales.due_list']))
                                <li><a href="{{ route('member.sales.due_list') }}">{{ __('sale.due_sale') }}</a></li>
                            @endif


                            @if ($user->can(['member.sales.sales_return_list']))
                                <li>
                                    <a href="{{ route('member.sales.sales_return_list') }}">{{ __('sale.return_sale') }}
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif
            @endif



            @if ((config('settings.pos') || config('settings.inventory')) && $user->can(['inventory_reports']))


                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-bar-chart"></i>
                        <span>{{ __('report.title') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if (config('settings.pos') || config('settings.inventory'))

                            @if (
                                $user->can([
                                    'member.report.sharer_due',
                                    'member.report.sharer_due_collection',
                                    'member.report.total-sales-purchases',
                                    'member.report.inventory_due',
                                    'member.report.total-purchases-as-per-day',
                                    'member.report.supplier_purchase',
                                    'member.report.product_purchase_report',
                                    'member.report.purchase_return_report',
                                ]))
                                <li class="treeview">
                                    <a href="#">
                                        <span> {{ __('report.purchase_reports') }}</span>
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </a>
                                    <ul class="treeview-menu">
                                        {{-- <li> --}}
                                        {{-- <a href="{{ route('member.report.purchase') }}">{{ __('report.purchase_reports') }} </a> --}}
                                        {{-- </li> --}}
                                        @if ($user->can(['member.report.sharer_due']))
                                            <li>
                                                <a href="{{ route('member.report.sharer_due', 'supplier') }}">{{ __('report.supplier_due') }}
                                                </a>
                                            </li>
                                        @endif

                                        @if ($user->can(['member.report.sharer_due_collection']))
                                            <li>
                                                <a
                                                    href="{{ route('member.report.sharer_due_collection', 'supplier') }}">{{ __('report.supplier_due_collection') }}
                                                </a>
                                            </li>
                                        @endif

                                        @if ($user->can(['member.report.inventory_due']))
                                            <li>
                                                <a href="{{ route('member.report.inventory_due', 'purchase') }}">
                                                    {{ __('report.purchase_due') }} </a>
                                            </li>
                                        @endif

                                        @if ($user->can(['member.report.product_purchase_report']))
                                            {{-- <li> --}}
                                            {{-- <a href="{{ route('member.report.cost', 'transport') }}">{{ __('report.transport_cost') }} </a> --}}
                                            {{-- </li> --}}
                                            {{-- <li> --}}
                                            {{-- <a href="{{ route('member.report.cost', 'unload')}}"> {{ __('report.unload_cost') }} </a> --}}
                                            {{-- </li> --}}
                                            <li>
                                                <a
                                                    href="{{ route('member.report.product_purchase_report', 'product') }}">
                                                    {{ __('report.purchase_report_by_product') }} </a>
                                            </li>
                                        @endif

                                        @if ($user->can(['member.report.supplier_purchase']))
                                            {{-- <li> --}}
                                            {{-- <a href="{{ route('member.report.product_purchase_report', 'manager')}}"> {{ __('report.purchase_report_by_manager') }} </a> --}}
                                            {{-- </li> --}}
                                            <li>
                                                <a href="{{ route('member.report.supplier_purchase') }}">{{ __('report.purchase_report_by_supplier') }}
                                                </a>
                                            </li>
                                        @endif

                                        @if ($user->can(['member.report.purchase_return_report']))
                                            <li>
                                                <a href="{{ route('member.report.purchase_return_report') }}"> {{__('common.purchase_return_report')}} </a>
                                            </li>
                                        @endif

                                        @if ($user->can(['member.report.total-purchases-as-per-day']))
                                            <li><a href="{{ route('member.report.total-purchases-as-per-day') }}">{{__('common.total_purchase_report_per_day')}} </a></li>
                                        @endif

                                        @if ($user->can(['member.report.total-sales-purchases']))
                                            <li><a href="{{ route('member.report.total-sales-purchases') }}"> {{__('common.total_sales_and_purchases')}} </a></li>
                                        @endif
                                    </ul>
                                </li>
                            @endif

                            @if (
                                $user->can([
                                    'sales_report',
                                    'member.report.sharer_due',
                                    'member.report.sharer_due_collection',
                                    'member.report.total-sales-purchases',
                                    'member.report.inventory_due',
                                    'member.report.sale_profit_report',
                                    'member.report.total-sales-as-per-day',
                                    'member.report.customer_sale',
                                    'member.report.sale_report_by_product',
                                    'member.report.sale_return_report',
                                ]))

                                <li class="treeview">
                                    <a href="#">
                                        <span> {{ __('report.sale_reports') }}</span>
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </a>
                                    <ul class="treeview-menu">
                                        {{-- <li> --}}
                                        {{-- <a href="{{ route('member.report.sale') }}">{{ __('report.sale_reports') }} </a> --}}
                                        {{-- </li> --}}
                                        @if ($user->can(['member.report.sharer_due']))
                                            <li>
                                                <a href="{{ route('member.report.sharer_due', 'customer') }}">{{ __('report.customer_due') }}
                                                </a>
                                            </li>
                                        @endif

                                        @if ($user->can(['member.report.sharer_due_collection']))
                                            <li>
                                                <a
                                                    href="{{ route('member.report.sharer_due_collection', 'customer') }}">{{ __('report.customer_due_collection') }}
                                                </a>
                                            </li>
                                        @endif

                                        @if ($user->can(['member.report.inventory_due']))
                                            <li>
                                                <a href="{{ route('member.report.inventory_due', 'sale') }}">
                                                    {{ __('report.sale_due') }} </a>
                                            </li>
                                        @endif

                                        @if ($user->can(['member.report.sale_profit_report']))
                                            <li><a href="{{ route('member.report.sale_profit_report') }}">{{__('common.sale_profit_report')}} </a></li>
                                        @endif

                                        @if ($user->can(['member.report.sale_report_by_product']))
                                            <li>
                                                <a
                                                    href="{{ route('member.report.sale_report_by_product', 'product') }}">{{ __('report.sale_report_by_product') }}
                                                </a>
                                            </li>
                                        @endif

                                        @if ($user->can(['member.report.customer_sale']))
                                            {{-- <li> --}}
                                            {{-- <a href="{{ route('member.report.sale_report_by_product', 'manager')}}">{{ __('report.sale_report_by_manager') }} </a> --}}
                                            {{-- </li> --}}
                                            <li>
                                                <a href="{{ route('member.report.customer_sale') }}">
                                                    {{ __('report.sale_report_by_customer') }} </a>
                                            </li>
                                        @endif

                                        @if ($user->can(['member.report.sale_return_report']))
                                            <li>
                                                <a href="{{ route('member.report.sale_return_report') }}"> {{__('common.sale_return_report')}} </a>
                                            </li>
                                        @endif

                                        @if ($user->can(['member.report.total-sales-as-per-day']))
                                            <li><a href="{{ route('member.report.total-sales-as-per-day') }}">{{__('common.total_sale_report_per_day')}} </a></li>
                                        @endif

                                        @if ($user->can(['member.report.total-sales-purchases']))
                                            <li><a href="{{ route('member.report.total-sales-purchases') }}"> {{__('common.total_sales_and_purchases')}} </a></li>
                                        @endif

                                    </ul>
                                </li>
                            @endif

                        @endif



                        @if (
                            $user->can([
                                'member.report.daily_stocks',
                                'member.report.stocks',
                                'member.report.loss_reconcile_stocks',
                                'member.report.gain_reconcile_stocks',
                                'member.report.total_stocks',
                            ]))

                            <li class="treeview">
                                <a href="#">
                                    <span> {{ __('report.stock_reports') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if ($user->can(['member.report.daily_stocks']))
                                        <li>
                                            <a href="{{ route('member.report.daily_stocks') }}">
                                                {{ __('report.daily_stocks') }}</a>
                                        </li>
                                    @endif

                                    @if ($user->can(['member.report.stocks']))
                                        <li><a href="{{ route('member.report.stocks') }}"> {{__('common.stocks')}}</a></li>
                                    @endif

                                    @if ($user->can(['member.report.loss_reconcile_stocks']))
                                        <li><a href="{{ route('member.report.loss_reconcile_stocks') }}"> {{__('common.stock_damage_loss_expired')}} </a></li>
                                    @endif

                                    @if ($user->can(['member.report.gain_reconcile_stocks']))
                                        <li><a href="{{ route('member.report.gain_reconcile_stocks') }}"> {{__('common.stock_overflow')}} </a></li>
                                    @endif

                                    @if ($user->can(['member.report.total_stocks']))
                                        <li>
                                            <a href="{{ route('member.report.total_stocks') }}">
                                                {{ __('common.stock_price_report') }} </a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif


                        @if ($user->can(['member.report.balance-profit']))
                            <li class="treeview">
                                <a href="#">
                                    <span> {{ __('report.summary_reports') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if ($user->can(['member.report.balance-profit']))
                                        <li><a href="{{ route('member.report.balance-profit') }}"> {{__('common.profit_balance')}} </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
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
                            <li><a href="{{ route('member.items.create') }}{{ config('settings.variant') ? '?variant=inactive' : '' }}"><i class="fa fa-plus"></i>
                                    {{ __('common.add_product') }}</a></li>

                                @if(config('settings.variant'))
                                    <li><a href="{{ route('member.items.create') }}?variant=active"><i class="fa fa-plus"></i>
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

            @if ($user->can(['admin.units.index']))
                <li>
                    <a href="{{ route('admin.units.index') }}">
                        <i class="fa fa-tags"></i> <span>{{ __('common.units') }}</span>
                    </a>
                </li>
            @endif

            @if (config('settings.variant') )
                @if($user->can(['member.variants.index','member.variants.create']))
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-map-o"></i>
                            <span>{{ __('common.variants') }}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            @if($user->can(['member.variants.index']))
                                <li><a href="{{ route('member.variants.index') }}"><i
                                            class="fa fa-map"></i> {{ __('common.variants') }}</a>
                                </li>
                            @endif

                            @if($user->can(['member.variants.create']))
                                <li><a href="{{ route('member.variants.create') }}"><i
                                            class="fa fa-plus"></i>{{ __('common.add_variant') }} </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            @endif

            @if (config('settings.pos') || config('settings.inventory'))


                @if ($user->can(['member.brand.index', 'member.brand.create']))
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-map-o"></i>
                            <span>{{ __('common.manage_brands') }}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            @if ($user->can(['member.brand.index']))
                                <li><a href="{{ route('member.brand.index') }}"><i class="fa fa-map"></i>
                                        {{ __('common.brands') }}</a></li>
                            @endif

                            @if ($user->can(['member.brand.create']))
                                <li><a href="{{ route('member.brand.create') }}"><i
                                            class="fa fa-plus"></i>{{ __('common.add_brand') }} </a></li>
                            @endif
                        </ul>
                    </li>
                @endif

            @endif


            @if ($user->can(['super-admin', 'admin']))
            <li class="header">{{ __('common.manage_function') }}</li>
            @endif


            @if (config('settings.accounts') || $user->hasRole(['super-admin']))

                @if (
                    $user->can([
                        'member.cash_or_bank_accounts.create',
                        'member.cash_or_bank_accounts.index',
                        'member.cash_or_bank_accounts.bank_gl_account',
                    ]))

                    <li class="treeview">
                        <a href="javascript:void(0)">
                            <i class="fa fa-university"></i>
                            <span> {{ __('cash_bank.title') }} </span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            @if ($user->can(['member.cash_or_bank_accounts.create']))
                                <li><a href="{{ route('member.cash_or_bank_accounts.create') }}">
                                        {{ __('cash_bank.add') }} </a></li>
                            @endif

                            @if ($user->can(['member.cash_or_bank_accounts.index']))
                                <li><a href="{{ route('member.cash_or_bank_accounts.index') }}">
                                        {{ __('cash_bank.list') }} </a></li>
                            @endif

                            @if ($user->can(['member.cash_or_bank_accounts.bank_gl_account']))
                                <li><a href="{{ route('member.cash_or_bank_accounts.bank_gl_account') }}">
                                        {{ __('cash_bank.gl') }}</a></li>
                            @endif

                        </ul>
                    </li>

                @endif


            @endif


            @if ($user->can(['member.sharer.supplier_list', 'member.supplier.create']))
                <li class="treeview">
                    <a href="javascript:void(0)">
                        <i class="fa fa-users"></i>
                        <span> {{ __('sharer.supplier_title') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if ($user->can(['member.sharer.supplier_list']))
                            <li><a href="{{ route('member.sharer.supplier_list') }}"><i class="fa fa-users"></i>
                                    {{ __('sharer.supplier_list') }}</a></li>
                        @endif

                        @if ($user->can(['member.supplier.create']))
                            <li><a href="{{ route('member.sharer.create', 'supplier') }}"><i class="fa fa-plus"></i>
                                    {{ __('sharer.supplier_add') }}</a></li>
                        @endif
                    </ul>
                </li>
            @endif



            @if ($user->can(['member.sharer.customer_list', 'member.customer.create']))

                <li class="treeview">
                    <a href="javascript:void(0)">
                        <i class="fa fa-users"></i>
                        <span>{{ __('sharer.customer_title') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['member.sharer.customer_list']))
                            <li><a href="{{ route('member.sharer.customer_list') }}"><i class="fa fa-users"></i>
                                    {{ __('sharer.customer_list') }}</a></li>
                        @endif


                        @if ($user->can(['member.customer.create']))
                            <li><a href="{{ route('member.sharer.create', 'customer') }}"><i class="fa fa-plus"></i>
                                    {{ __('sharer.customer_add') }}</a></li>
                        @endif

                    </ul>
                </li>

            @endif



            @if (config('settings.imports') && $user->can(['admin.product_import.create', 'admin.sharer_import.create']))

                <li class="treeview">
                    <a href="#">

                        <i class="fa fa-upload"></i> <span>{{ __('common.import') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if ($user->can(['admin.product_import.create']))
                            <li><a href="{{ route('admin.product_import.create') }}"><i class="fa fa-upload"></i>
                                    {{ __('common.product_import') }}</a></li>
                        @endif

                        @if ($user->can(['admin.sharer_import.create']))
                            <li><a href="{{ route('admin.sharer_import.create') }}"><i class="fa fa-upload"></i>
                                    {{ __('common.sharer_import') }}</a></li>
                        @endif

                    </ul>
                </li>
            @endif



            @if (
                $user->can([
                    'super-admin',
                    'admin.stock_reconcile',
                    'admin.yearly_stock_reconcile',
                    'admin.transactions-error.index',
                ]))

                @if ($user->can(['admin.transactions-error.index']))
                    <li class="treeview">
                        <a href="javascript:void(0)">
                            <i class="fa fa-money"></i> <span>{{ __('common.error_reports') }}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.transactions-error.index') }}"> {{ __('common.transaction_errors') }} </a></li>
                        </ul>
                    </li>
                @endif


                @if ($user->can(['admin.stock_reconcile', 'admin.yearly_stock_reconcile']))

                    <li class="treeview">
                        <a href="javascript:void(0)">
                            <i class="fa fa-shopping-basket"></i> <span> {{ __('common.stock_reconcile') }}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            @if ($user->can(['admin.stock_reconcile']))
                                <li><a href="{{ route('admin.stock_reconcile', 'damage') }}"> {{ __('common.damage_stock') }} </a></li>
                            @endif

                            @if ($user->can(['admin.stock_reconcile']))
                                <li><a href="{{ route('admin.stock_reconcile', 'loss') }}"> {{ __('common.loss_stock') }} </a></li>
                            @endif

                            @if ($user->can(['admin.stock_reconcile']))
                                <li><a href="{{ route('admin.stock_reconcile', 'expired') }}"> {{ __('common.expired_stock') }} </a>
                                </li>
                            @endif

                            @if ($user->can(['admin.yearly_stock_reconcile']))
                                <li><a href="{{ route('admin.yearly_stock_reconcile') }}"> {{ __('common.yearly_damage_overflow_stock') }}
                                         </a></li>
                            @endif

                        </ul>
                    </li>

                @endif


            @endif


            @if (config('pos.check_stock_update') &&
                    $user->can(['admin.get_update_stock_report', 'admin.check_update_stock_report']))

                <li class="treeview">

                    <a href="#">
                        <i class="fa fa-pie-chart"></i> <span> {{ __('common.update_stock') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if ($user->can(['admin.get_update_stock_report']))
                            <li><a href="{{ route('admin.get_update_stock_report') }}">
                                {{ __('common.update_stock_report') }}t </a></li>
                        @endif

                        @if ($user->can(['admin.check_update_stock_report']))
                            <li><a href="{{ route('admin.check_update_stock_report') }}">
                                {{ __('common.sp_check_and_update_stock_report') }} </a></li>
                        @endif
                    </ul>
                </li>

            @endif


        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
