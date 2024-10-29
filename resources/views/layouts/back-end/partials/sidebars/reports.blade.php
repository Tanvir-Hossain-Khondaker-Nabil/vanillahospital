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


            @if(config('settings.accounts') && $user->can(['reports']) )

                <li class="header">{{  __('common.account_report') }}</li>

                @if($user->can(['member.report.list', 'member.report.all_transaction','member.report.all_transfer','member.report.all_expense','member.report.all_income','member.report.all_journal_entry']))

                    <li class="treeview">

                        <a href="javascript:void(0)">
                            <i class="fa fa-line-chart"></i>
                            <span> {{ __('report.transaction_reports') }} </span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            @if($user->can(['member.report.list']))
                                <li>
                                    <a href="{{ route('member.report.list') }}"> {{ __('report.all_report') }}  </a>
                                </li>
                            @endif


                            @if($user->can(['member.report.all_transaction']))
                                <li>
                                    <a href="{{ route('member.report.all_transaction') }}"> {{ __('report.all_transactions') }}  </a>
                                </li>
                            @endif

                            @if($user->can(['member.report.all_transfer']))
                                <li>
                                    <a href="{{ route('member.report.all_transfer') }}"> {{ __('report.all_transfers') }}  </a>
                                </li>
                            @endif

                            @if($user->can(['member.report.all_expense']))
                                <li>
                                    <a href="{{ route('member.report.all_expense') }}"> {{ __('report.all_deposit') }}  </a>
                                </li>
                            @endif


                            @if($user->can(['member.report.all_income']))
                                <li>
                                    <a href="{{ route('member.report.all_income') }}"> {{ __('report.all_income') }}  </a>
                                </li>
                            @endif

                            @if($user->can(['member.report.all_journal_entry']))
                                <li>
                                    <a href="{{ route('member.report.all_journal_entry') }}"> {{ __('report.all_journal_entry') }} </a>
                                </li>
                            @endif
                        </ul>
                    </li>

                @endif

                @if($user->can(['member.report.sharer_balance_report', 'member.report.trail_balance', 'member.report.trail_balance_v2', 'member.report.daily_sheet','member.report.ledger_book', 'member.report.lost_profit', 'member.report.balance_sheet']))

                    <li class="treeview">
                        <a href="javascript:void(0)">
                            <i class="fa fa-pie-chart"></i>
                            <span> {{ __('report.summary_reports') }} </span>

                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            @if($user->can(['member.report.sharer_balance_report']))
                                <li>
                                    <a href="{{ route('member.report.sharer_balance_report', 'customer_type=customer') }}">
                                        {{__('common.debtor_balance')}} </a></li>

                            @endif

                            @if($user->can(['member.report.sharer_balance_report']))
                                <li>
                                    <a href="{{ route('member.report.sharer_balance_report', 'customer_type=supplier') }}">
                                        {{__('common.creditor_balance')}} </a></li>

                            @endif

                            @if($user->can(['member.report.ledger_book']))
                                <li>
                                    <a href="{{ route('member.report.ledger_book') }}"> {{ __('report.ledger_book') }} </a>
                                </li>


                            @endif

                            @if($user->can(['member.report.trail_balance_v2']))

                                @if(config('accounts-system.trail_balance_v2'))
                                    <li><a href="{{ route('member.report.trail_balance_v2') }}"> {{ __('report.trial_balance') }} V2 </a>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ route('member.report.trail_balance') }}"> {{ __('report.trial_balance') }} </a>
                                    </li>
                                @endif
                                {{--                            <li><a href="{{ route('member.report.ledger_book_by_manager') }}"> {{ __('report.ledger_book_by_manager') }}</a></li>--}}
                                {{--                            <li><a href="{{ route('member.report.ledger_book_by_sharer') }}"> {{ __('report.ledger_book_by_sharer') }}</a></li>--}}
                            @endif

                            @if($user->can(['member.report.daily_sheet']))
                                <li>
                                    <a href="{{ route('member.report.daily_sheet') }}"> {{ __('report.daily_sheet') }}</a>
                                </li>

                            @endif

                            @if($user->can(['member.report.lost_profit']))
                                <li>
                                    <a href="{{ route('member.report.lost_profit')}}"> {{ __('report.profit_loss') }} </a>
                                </li>
                            @endif

                            @if($user->can(['member.report.balance_sheet']))
                                <li>
                                    <a href="{{ route('member.report.balance_sheet')}}"> {{ __('report.balance_sheet') }} </a>
                                </li>
                            @endif

                        </ul>
                    </li>

                @endif


            @endif


            @if( (config('settings.pos') || config('settings.inventory')) && $user->can(['inventory_reports']) )


                <li class="header">{{  __('common.inventory_report') }}</li>


                @if(config('settings.pos') || config('settings.inventory'))

                    @if($user->can([
                    'member.report.sharer_due','member.report.sharer_due_collection','member.report.total-sales-purchases',
                    'member.report.inventory_due', 'member.report.total-purchases-as-per-day',
                    'member.report.supplier_purchase', 'member.report.product_purchase_report', 'member.report.purchase_return_report',
                    ]))
                        <li class="treeview">
                            <a href="javascript:void(0)">
                                <i class="fa fa-bar-chart"></i>
                                <span> {{ __('report.purchase_reports') }}</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                {{--<li>--}}
                                {{--<a href="{{ route('member.report.purchase') }}">{{ __('report.purchase_reports') }} </a>--}}
                                {{--</li>--}}
                                @if($user->can(['member.report.sharer_due']))
                                    <li>
                                        <a href="{{ route('member.report.sharer_due', 'supplier') }}">{{ __('report.supplier_due') }} </a>
                                    </li>
                                @endif

                                @if($user->can(['member.report.sharer_due_collection']))
                                    <li>
                                        <a href="{{ route('member.report.sharer_due_collection', 'supplier') }}">{{ __('report.supplier_due_collection') }}  </a>
                                    </li>
                                @endif

                                @if($user->can(['member.report.inventory_due']))
                                    <li>
                                        <a href="{{ route('member.report.inventory_due', 'purchase') }}"> {{ __('report.purchase_due') }} </a>
                                    </li>
                                @endif

                                @if($user->can(['member.report.product_purchase_report']))
                                    {{--<li>--}}
                                    {{--<a href="{{ route('member.report.cost', 'transport') }}">{{ __('report.transport_cost') }} </a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                    {{--<a href="{{ route('member.report.cost', 'unload')}}"> {{ __('report.unload_cost') }} </a>--}}
                                    {{--</li>--}}
                                    <li>
                                        <a href="{{ route('member.report.product_purchase_report', 'product')}}"> {{ __('report.purchase_report_by_product') }} </a>
                                    </li>
                                @endif

                                @if($user->can(['member.report.supplier_purchase']))
                                    {{--<li>--}}
                                    {{--<a href="{{ route('member.report.product_purchase_report', 'manager')}}"> {{ __('report.purchase_report_by_manager') }} </a>--}}
                                    {{--</li>--}}
                                    <li>
                                        <a href="{{ route('member.report.supplier_purchase')}}">{{ __('report.purchase_report_by_supplier') }}  </a>
                                    </li>
                                @endif

                                @if($user->can(['member.report.purchase_return_report']))

                                    <li>
                                        <a href="{{ route('member.report.purchase_return_report')}}"> {{__('common.purchase_return_report')}} </a>
                                    </li>
                                @endif

                                @if($user->can(['member.report.total-purchases-as-per-day']))
                                    <li><a href="{{ route('member.report.total-purchases-as-per-day')}}">{{__('common.total_purchase_report_per_day')}} </a></li>
                                @endif

                                @if($user->can(['member.report.total-sales-purchases']))
                                    <li><a href="{{ route('member.report.total-sales-purchases')}}"> {{__('common.total_sales_and_purchases')}} </a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if($user->can([
                        'sales_report','member.report.sharer_due',
                        'member.report.sharer_due_collection',
                        'member.report.total-sales-purchases',
                        'member.report.inventory_due',
                        'member.report.sale_profit_report',
                        'member.report.total-sales-as-per-day',
                        'member.report.customer_sale','member.report.sale_report_by_product',
                        'member.report.sale_return_report'
                    ]))

                        <li class="treeview">
                            <a href="javascript:void(0)">
                                <i class="fa fa-line-chart"></i>
                                <span> {{ __('report.sale_reports') }}</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                {{--<li>--}}
                                {{--<a href="{{ route('member.report.sale') }}">{{ __('report.sale_reports') }} </a>--}}
                                {{--</li>--}}
                                @if($user->can(['member.report.sharer_due']))
                                    <li>
                                        <a href="{{ route('member.report.sharer_due', 'customer') }}">{{ __('report.customer_due') }} </a>
                                    </li>
                                @endif

                                @if($user->can(['member.report.sharer_due_collection']))
                                    <li>
                                        <a href="{{ route('member.report.sharer_due_collection', 'customer') }}">{{ __('report.customer_due_collection') }} </a>
                                    </li>
                                @endif

                                @if($user->can(['member.report.inventory_due']))
                                    <li>
                                        <a href="{{ route('member.report.inventory_due', 'sale') }}"> {{ __('report.sale_due') }}  </a>
                                    </li>
                                @endif

                                @if($user->can(['member.report.sale_profit_report']))
                                    <li><a href="{{ route('member.report.sale_profit_report')}}">{{__('common.sale_profit_report')}} </a></li>
                                @endif

                                @if($user->can(['member.report.sale_report_by_product']))
                                    <li>
                                        <a href="{{ route('member.report.sale_report_by_product', 'product')}}">{{ __('report.sale_report_by_product') }} </a>
                                    </li>
                                @endif

                                @if($user->can(['member.report.customer_sale']))
                                    {{--<li>--}}
                                    {{--<a href="{{ route('member.report.sale_report_by_product', 'manager')}}">{{ __('report.sale_report_by_manager') }} </a>--}}
                                    {{--</li>--}}
                                    <li>
                                        <a href="{{ route('member.report.customer_sale')}}"> {{ __('report.sale_report_by_customer') }} </a>
                                    </li>
                                @endif

                                @if($user->can(['member.report.sale_return_report']))
                                    <li>
                                        <a href="{{ route('member.report.sale_return_report')}}"> {{__('common.sale_return_report')}} </a>
                                    </li>
                                @endif

                                @if($user->can(['member.report.total-sales-as-per-day']))
                                    <li><a href="{{ route('member.report.total-sales-as-per-day')}}">{{__('common.total_sale_report_per_day')}} </a></li>
                                @endif

                                @if($user->can(['member.report.total-sales-purchases']))
                                    <li><a href="{{ route('member.report.total-sales-purchases')}}"> {{__('common.total_sales_and_purchases')}} </a></li>
                                @endif

                            </ul>
                        </li>
                    @endif

                @endif



                @if($user->can(['member.report.daily_stocks', 'member.report.stocks', 'member.report.loss_reconcile_stocks','member.report.gain_reconcile_stocks', 'member.report.total_stocks']))

                    <li class="treeview">
                        <a href="javascript:void(0)">
                            <i class="fa fa-area-chart"></i>
                            <span> {{ __('report.stock_reports') }}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            @if($user->can(['member.report.daily_stocks']))
                                <li>
                                    <a href="{{ route('member.report.daily_stocks') }}"> {{ __('report.daily_stocks') }}</a>
                                </li>
                            @endif

                            @if($user->can(['member.report.stocks']))
                                <li><a href="{{ route('member.report.stocks') }}"> {{__('common.stocks')}}</a></li>
                            @endif

                            @if($user->can(['member.report.loss_reconcile_stocks']))
                                <li><a href="{{ route('member.report.loss_reconcile_stocks') }}"> {{__('common.stock_damage_loss_expired')}} </a></li>
                            @endif

                            @if($user->can(['member.report.gain_reconcile_stocks']))
                                <li><a href="{{ route('member.report.gain_reconcile_stocks') }}"> {{__('common.stock_overflow')}} </a>
                                </li>
                            @endif

                            @if($user->can(['member.report.total_stocks']))
                                <li>
                                    <a href="{{ route('member.report.total_stocks') }}"> {{ __('common.stock_price_report') }} </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif




                @if(config('settings.warehouse') &&
                $user->can(['member.warehouse.transfer',
                'member.warehouse.history.index','member.warehouse.history.transfer_list']
                ))
                    <li class="treeview ">
                        <a href="javascript:void(0)">
                            <i class="fa fa-industry"></i>
                            <span> {{__('common.warehouses_report')}}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            @if($user->can(['member.warehouse.history.index']))
                                <li><a href="{{ route('member.warehouse.history.index') }}"><i class="fa fa-list"></i>{{__('common.warehouse_store_history')}}</a></li>
                            @endif

                            @if($user->can(['member.warehouse.history.transfer_list']))
                                <li><a href="{{ route('member.warehouse.history.transfer_list') }}"><i
                                            class="fa fa-list"></i>{{__('common.warehouse_transfer_history')}}</a></li>
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

                @if(config('settings.requisition') && $user->can(['member.report.dealer_daily_stocks', 'member.report.dealer_stocks','member.report.daily_stock_by_rsrd','member.report.requistion_report', 'member.report.salesman_requistion_report']))

                    <li class="treeview">
                        <a href="javascript:void(0)">
                            <i class="fa fa-pie-chart"></i>
                            <span>{{ __('common.requisition_report') }}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">


                            @if($user->can(['member.report.dealer_daily_stocks', 'member.report.dealer_stocks']))

                                <li class="treeview">
                                    <a href="javascript:void(0)">
                                        <span> {{ __('report.stock_reports') }}</span>
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </a>
                                    <ul class="treeview-menu">

                                        @if( $user->can([ 'member.report.dealer_daily_stocks']))
                                            <li><a href="{{ route('member.report.dealer_daily_stocks') }}">
                                                    Dealer {{ __('report.daily_stocks') }}</a></li>
                                        @endif

                                        @if( $user->can([ 'member.report.dealer_stocks']))
                                            <li><a href="{{ route('member.report.dealer_stocks') }}">
                                                    Dealer {{ __('report.stocks') }}</a></li>
                                        @endif


                                    </ul>
                                </li>
                            @endif



                            @if($user->can(['member.report.requistion_report', 'member.report.salesman_requistion_report']))

                                <li class="treeview">
                                    <a href="javascript:void(0)">
                                        <span> {{ __('common.requisition_report') }}</span>
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </a>
                                    <ul class="treeview-menu">
                                        @if($user->can(['member.report.requistion_report']))
                                            <li>
                                                <a href="{{ route('member.report.requistion_report') }}">  {{ __('common.summary_report') }}</a>
                                            </li>
                                        @endif
                                        @if($user->can(['member.report.salesman_requistion_report']))
                                            <li>
                                                <a href="{{ route('member.report.salesman_requistion_report') }}">  {{ __('common.salesman_requisition_report') }}</a>
                                            </li>

                                        @endif

                                    </ul>
                                </li>
                            @endif

                            @if($user->can(['member.report.daily_stock_by_rsrd']))

                                <li class="treeview">
                                    <a href="javascript:void(0)">
                                        <span> {{ __('report.summary_reports') }}</span>
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </a>
                                    <ul class="treeview-menu">

                                        {{--<li>--}}
                                        {{--<a href="{{ route('member.report.daily_stock_report_by_requisition_damage') }}"> {{ __('Daily RSRD Report') }} </a>--}}
                                        {{--</li>--}}
                                        <li>
                                            <a href="{{ route('member.report.daily_stock_by_rsrd') }}"> {{ __('common.daily_actual_sale_rsrd_report') }} </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

            {{--

                @if(config('settings.requisition') &&  config('settings.store'))
                    @if($user->can(['member.report.sale-commission', 'member.report.paid-sale-commission']))

                        <li class="treeview">
                            <a href="#">
                                <span> {{ __('Commission Report') }}</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">

                                @if($user->can(['member.report.sale-commission']))
                                    <li><a href="{{ route('member.report.sale-commission')}}"> Sale Commission Due Report </a></li>
                                @endif
                                @if($user->can(['member.report.paid-sale-commission']))
                                    <li><a href="{{ route('member.report.paid-sale-commission')}}"> Sale Commission Paid Report </a></li>
                                @endif


                            </ul>
                        </li>
                    @endif
                @endif

                --}}

                @if($user->can(['member.report.balance-profit']))
                    <li class="treeview">
                        <a href="javascript:void(0)">
                            <i class="fa fa-balance-scale"></i>
                            <span> {{ __('report.summary_reports') }}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            @if($user->can(['member.report.balance-profit']))
                                <li><a href="{{ route('member.report.balance-profit')}}"> {{__('common.profit_balance')}} </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

            @endif


            @if($user->can(['super-admin']))
                <li class="header">{{ __('common.admin_settings') }}</li>

            @endif


            @if($user->can(['admin.stock_reconcile', 'admin.yearly_stock_reconcile', 'admin.transactions-error.index']))

                @if($user->can(['admin.transactions-error.index']))

                    <li class="treeview">
                        <a href="javascript:void(0)">
                            <i class="fa fa-money"></i> <span>{{__('common.error_reports')}}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.transactions-error.index')}}"> {{__('common.transaction_errors')}} </a></li>
                        </ul>
                    </li>

                @endif


                @if($user->can(['admin.stock_reconcile', 'admin.yearly_stock_reconcile' ]))

                    <li class="treeview">
                        <a href="javascript:void(0)">
                            <i class="fa fa-shopping-basket"></i> <span> {{__('common.stock_reconcile')}}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            @if($user->can(['admin.stock_reconcile']))
                                <li><a href="{{ route('admin.stock_reconcile', 'damage')}}"> {{__('common.damage_stock')}} </a></li>
                            @endif

                            @if($user->can(['admin.stock_reconcile']))
                                <li><a href="{{ route('admin.stock_reconcile', 'loss')}}"> {{__('common.loss_stock')}} </a></li>
                            @endif

                            @if($user->can(['admin.stock_reconcile']))
                                <li><a href="{{ route('admin.stock_reconcile', 'expired')}}"> {{__('common.expired_stock')}} </a></li>
                            @endif

                            @if($user->can(['admin.yearly_stock_reconcile']))
                                <li><a href="{{ route('admin.yearly_stock_reconcile')}}"> {{__('common.yearly_damage_overflow_stock')}} </a></li>
                            @endif

                        </ul>
                    </li>

                @endif


            @endif


            @if( (config('settings.pos') || config('settings.inventory')) && config('pos.check_stock_update') && $user->can(['admin.get_update_stock_report', 'admin.check_update_stock_report']))

                <li class="treeview">

                    <a href="javascript:void(0)">
                        <i class="fa fa-pie-chart"></i> <span>{{__('common.update_stock')}}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if($user->can(['admin.get_update_stock_report']))
                            <li><a href="{{ route('admin.get_update_stock_report') }}">
                                    {{__('common.update_stock_report')}} </a></li>
                        @endif

                        @if($user->can(['admin.check_update_stock_report']))
                            <li><a href="{{ route('admin.check_update_stock_report') }}">
                                    {{__('common.sp_check_and_update_stock_report')}} </a></li>
                        @endif
                    </ul>
                </li>

            @endif

            @if(config('settings.accounts'))

                @if(config('accounts-system.balance_adjustment') && $user->can(['admin.ledger_book.set_account_head_balance', 'admin.ledger_book.update_all_account_head_balance']))

                    <li class="treeview">

                        <a href="javascript:void(0)">
                            <i class="fa fa-money"></i> <span>{{__('common.report_balance_adjustment')}}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            @if($user->can(['admin.ledger_book.set_account_head_balance']))
                                <li><a href="{{ route('admin.ledger_book.set_account_head_balance') }}"><i
                                            class="fa fa-bar-chart-o"></i> {{__('common.account_head_wise')}} </a></li>
                            @endif

                            @if($user->can(['admin.ledger_book.update_all_account_head_balance']))
                                <li><a href="{{ route('admin.ledger_book.update_all_account_head_balance') }}"
                                       class="process-confirm"><i class="fa fa-bar-chart"></i> {{__('common.all_account_head_at_a_time')}} </a></li>
                            @endif

                            @if($user->can(['member.report.account_day_wise_last_balance']))
                                <li><a href="{{ route('member.report.account_day_wise_last_balance')}}"> {{__('common.account_head_daywise_balance')}} </a></li>
                            @endif

                        </ul>
                    </li>

                @endif


            @endif


        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

