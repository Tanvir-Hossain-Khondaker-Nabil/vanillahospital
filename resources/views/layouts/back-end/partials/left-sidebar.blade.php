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

            <li class="header">MAIN NAVIGATION</li>

            @if(config('settings.accounts'))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-calculator"></i>
                        <span> Accounting </span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                    <ul class="treeview-menu">

                        @if($user->can(['member.transaction.index', 'member.transaction.create']))
                            <li class="treeview">
                                <a href="javascript:void(0)">
                                    <i class="fa fa-database"></i>
                                    <span>{{ __('transaction.title') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    {{--                    <li><a href="{{ route('member.transaction.manage_daily_sheet') }}"><i class="fa fa-exchange"></i> Manage Daily Sheet</a></li>--}}

                                    @if($user->can(['member.transaction.create']))
                                        <li><a href="{{ route('member.transaction.create', 'Payment') }}"><i
                                                    class="fa fa-long-arrow-right"></i> {{ __('transaction.payment_transaction') }}
                                            </a></li>
                                        <li><a href="{{ route('member.transaction.create', 'Deposit') }}"><i
                                                    class="fa fa-long-arrow-left"></i> {{ __('transaction.receive_transaction') }}
                                            </a></li>
                                        <li><a href="{{ route('member.transaction.create', 'Income') }}"><i
                                                    class="fa fa-long-arrow-left"></i> {{ __('transaction.income_transaction') }}
                                            </a></li>
                                    @endif

                                    {{--                    <a href="{{ route('member.transaction.create', 'Expense') }}"><i class="fa fa-long-arrow-left"></i> Expense</a></li>--}}
                                    {{--                    <li><a href="{{ route('member.transaction.transfer.create') }}"><i class="fa fa-long-arrow-left"></i> Transfer</a></li>--}}
                                    {{--                    <li><a href="{{ route('member.transaction.transfer.list') }}"><i class="fa fa-list"></i> Transfer List</a></li>--}}

                                    @if($user->can(['member.transaction.index']))
                                        <li><a href="{{ route('member.transaction.index') }}"><i
                                                    class="fa fa-list"></i> {{ __('transaction.list_transaction') }}</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>

                        @endif

                        @if($user->can(['member.general_ledger.search']))

                            <li class="treeview">
                                <a href="javascript:void(0)">
                                    <i class="fa fa-book"></i>
                                    <span> {{ __('common.general_ledger') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="{{ route('member.general_ledger.search') }}"><i
                                                class="fa fa-list"></i> {{ __('common.ledger_list') }}</a></li>
                                    {{-- <li> <a href="{{ route('member.general_ledger.list_ledger') }}"><i class="fa fa-list-alt"></i> All Ledger </a></li> --}}
                                </ul>
                            </li>

                        @endif

                        @if($user->can(['member.journal_entry.index', 'member.journal_entry.create']))

                            <li class="treeview">
                                <a href="javascript:void(0)">
                                    <i class="fa fa-book"></i>
                                    <span> {{ __('common.journal_entry') }} </span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if($user->can(['member.journal_entry.create']))

                                        <li>
                                            <a href="{{ route('member.journal_entry.create') }}">{{ __('common.add_journal_entry') }}</a>
                                        </li>
                                    @endif

                                    @if($user->can(['member.journal_entry.index']))

                                        <li>
                                            <a href="{{ route('member.journal_entry.index') }}">{{ __('common.journal_entry_list') }}</a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif

                        @if($user->can(['member.report.list', 'member.report.all_transaction','member.report.all_transfer','member.report.all_expense','member.report.all_income','member.report.all_journal_entry', 'member.report.sharer_balance_report', 'member.report.trail_balance', 'member.report.trail_balance_v2', 'member.report.daily_sheet','member.report.ledger_book', 'member.report.lost_profit', 'member.report.balance_sheet']))

                            <li class="treeview">
                                <a href="javascript:void(0)">
                                    <i class="fa fa-bar-chart"></i>
                                    <span>{{ __('report.title') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">


                                    @if($user->can(['member.report.list', 'member.report.all_transaction','member.report.all_transfer','member.report.all_expense','member.report.all_income','member.report.all_journal_entry']))

                                        <li class="treeview">

                                            <a href="#">
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
                                            <a href="#">
                                                <span> {{ __('report.summary_reports') }} </span>

                                                <i class="fa fa-angle-left pull-right"></i>
                                            </a>
                                            <ul class="treeview-menu">

                                                @if($user->can(['member.report.sharer_balance_report']))
                                                    <li>
                                                        <a href="{{ route('member.report.sharer_balance_report', 'customer_type=customer') }}">
                                                            Debtor Balance </a></li>

                                                @endif

                                                @if($user->can(['member.report.sharer_balance_report']))
                                                    <li>
                                                        <a href="{{ route('member.report.sharer_balance_report', 'customer_type=supplier') }}">
                                                            Creditor Balance </a></li>

                                                @endif

                                                @if($user->can(['member.report.ledger_book']))
                                                    <li>
                                                        <a href="{{ route('member.report.ledger_book') }}"> {{ __('report.ledger_book') }} </a>
                                                    </li>


                                                @endif

                                                @if($user->can(['member.report.trail_balance_v2']))

                                                    @if(config('accounts-system.trail_balance_v2'))
                                                        <li><a href="{{ route('member.report.trail_balance_v2') }}">
                                                                Trail
                                                                Balance
                                                                V2 </a></li>
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

                                </ul>
                            </li>
                        @endif



                        @if($user->can(['member.cheque_entries.index', 'member.cheque_entries.today_cheque_list', 'member.cheque_entries.create']) && config('accounts-system.accounts.cheque'))
                            <li class="treeview">

                                <a href="#">
                                    <i class="fa fa-money"></i>
                                    <span> {{ __('common.manage_cheque') }} </span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if($user->can(['member.cheque_entries.index']))
                                        <li><a href="{{ route('member.cheque_entries.index') }}"><i
                                                    class="fa fa-dollar"></i> {{ __('common.cheques') }}</a></li>
                                    @endif

                                    @if($user->can([ 'member.cheque_entries.today_cheque_list']))
                                        <li><a href="{{ route('member.cheque_entries.today_cheque_list') }}"><i
                                                    class="fa fa-dollar"></i> {{ __('common.today_cheque') }} </a></li>
                                    @endif

                                    @if($user->can([ 'member.cheque_entries.create']))
                                        <li><a href="{{ route('member.cheque_entries.create') }}"><i
                                                    class="fa fa-plus"></i> {{ __('common.add_cheque') }}</a></li>
                                    @endif

                                </ul>
                            </li>
                        @endif


                        @if($user->can(['member.reconciliation.index', 'member.reconciliation.create'])  && config('accounts-system.accounts.reconciliation'))
                            <li class="treeview">


                                <a href="#">
                                    <i class="fa fa-balance-scale"></i>
                                    <span>{{ __('common.reconciliation') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>

                                <ul class="treeview-menu">

                                    @if($user->can(['member.reconciliation.index']))
                                        <li><a href="{{ route('member.reconciliation.index') }}"><i
                                                    class="fa fa-dollar"></i> {{ __('common.reconciliations') }}</a>
                                        </li>

                                    @endif

                                    @if($user->can(['member.reconciliation.create'])  )
                                        <li><a href="{{ route('member.reconciliation.create', 'supplier') }}"><i
                                                    class="fa fa-plus"></i> {{ __('common.supplier_reconciliation') }}
                                            </a></li>
                                        <li><a href="{{ route('member.reconciliation.create', 'bank') }}"><i
                                                    class="fa fa-plus"></i> {{ __('common.bank_reconciliation') }}</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>

                        @endif


                    </ul>
                </li>

            @endif

            @if(config('settings.pos') || config('settings.inventory'))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-shopping-cart"></i>
                        <span> Inventory/POS Manage </span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                    <ul class="treeview-menu">


                        @if($user->can(['member.purchase_return.index','member.purchase.create','member.purchase.index','member.purchase.due_list']))
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span> {{ __('purchase.title') }} </span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    @if($user->can(['member.purchase.create']))
                                        <li>
                                            <a href="{{ route('member.purchase.create') }}">{{ __('purchase.new_purchase') }}</a>
                                            {{--<li><a href="{{ route('member.warehouse_purchases.create') }}">{{ __('Purchase For Warehouse') }}</a>--}}
                                        </li>
                                    @endif

                                    @if($user->can(['member.purchase.index']))
                                        <li>
                                            <a href="{{ route('member.purchase.index') }}">{{ __('purchase.list_purchase') }}</a>
                                        </li>
                                    @endif

                                    @if($user->can(['member.purchase.due_list']))
                                        <li>
                                            <a href="{{ route('member.purchase.due_list') }}">{{ __('purchase.due_purchase') }}</a>
                                        </li>
                                    @endif

                                    @if($user->can(['member.purchase_return.index']))
                                        <li>
                                            <a href="{{ route('member.purchase_return.index') }}">{{ __('purchase.return_purchase') }}</a>
                                        </li>
                                    @endif

                                </ul>
                            </li>

                        @endif

                        @if($user->can(['member.sales.create', 'member.sales.whole_sale_create','member.sales.pos_create','member.sales.index','member.sales.due_list','member.sales.sales_return_list']))

                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-shopping-bag"></i>
                                    <span> {{ __('sale.title') }} </span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    @if(config('settings.inventory') && $user->can(['member.sales.whole_sale_create']))
                                        <li>
                                            <a href="{{ route('member.sales.whole_sale_create') }}">{{ __('sale.whole_sale') }}</a>
                                        </li>
                                    @endif

                                    @if($user->can(['member.sales.create']))
                                        <li>
                                            <a href="{{ route('member.sales.create') }}">{{ __('sale.create_sale') }}</a>
                                        </li>
                                    @endif

                                    @if(config('settings.pos') && $user->can(['member.sales.pos_create']))
                                        <li>
                                            <a href="{{ route('member.sales.pos_create') }}">{{ __('sale.pos_sale') }}</a>
                                        </li>
                                    @endif

                                    @if($user->can(['member.sales.index']))
                                        <li><a href="{{ route('member.sales.index') }}">{{ __('sale.list_sale') }} </a>
                                        </li>
                                    @endif


                                    @if($user->can(['member.sales.due_list']))
                                        <li><a href="{{ route('member.sales.due_list') }}">{{ __('sale.due_sale') }}</a>
                                        </li>
                                    @endif


                                    @if($user->can(['member.sales.sales_return_list']))
                                        <li>
                                            <a href="{{ route('member.sales.sales_return_list') }}">{{ __('sale.return_sale') }} </a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif


                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-bar-chart"></i>
                                <span>{{ __('report.title') }}</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">

                                @if($user->can([
                                    'member.report.sharer_due','member.report.sharer_due_collection',
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
                                                    <a href="{{ route('member.report.purchase_return_report')}}">
                                                        Purchase
                                                        Return Report </a>
                                                </li>
                                            @endif

                                            @if($user->can(['member.report.total-purchases-as-per-day']))
                                                <li>
                                                    <a href="{{ route('member.report.total-purchases-as-per-day')}}">Total
                                                        Purchase Report per Day </a></li>
                                            @endif

                                            @if($user->can(['member.report.total-sales-purchases']))
                                                <li><a href="{{ route('member.report.total-sales-purchases')}}">
                                                        Total Sales
                                                        and Purchases </a></li>
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
                                    'member.report.customer_sale',
                                    'member.report.sale_report_by_product',
                                    'member.report.sale_return_report'
                                ]))

                                    <li class="treeview">
                                        <a href="#">
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
                                                <li><a href="{{ route('member.report.sale_profit_report')}}">Sale
                                                        Profit
                                                        Report </a></li>
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
                                                    <a href="{{ route('member.report.sale_return_report')}}">
                                                        Sale Return
                                                        Report </a>
                                                </li>
                                            @endif

                                            @if($user->can(['member.report.total-sales-as-per-day']))
                                                <li>
                                                    <a href="{{ route('member.report.total-sales-as-per-day')}}">Total
                                                        Sale
                                                        Report per Day </a></li>
                                            @endif

                                            @if($user->can(['member.report.total-sales-purchases']))
                                                <li><a href="{{ route('member.report.total-sales-purchases')}}">
                                                        Total Sales
                                                        and
                                                        Purchases </a></li>
                                            @endif

                                        </ul>
                                    </li>


                                @endif



                                @if($user->can(['member.report.daily_stocks', 'member.report.stocks', 'member.report.loss_reconcile_stocks','member.report.gain_reconcile_stocks', 'member.report.total_stocks']))

                                    <li class="treeview">
                                        <a href="#">
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
                                                <li><a href="{{ route('member.report.stocks') }}"> Stocks</a></li>
                                            @endif

                                            @if($user->can(['member.report.loss_reconcile_stocks']))
                                                <li><a href="{{ route('member.report.loss_reconcile_stocks') }}">
                                                        Stock
                                                        Damage/Loss/Expired </a></li>
                                            @endif

                                            @if($user->can(['member.report.gain_reconcile_stocks']))
                                                <li><a href="{{ route('member.report.gain_reconcile_stocks') }}">
                                                        Stock
                                                        Overflow </a></li>
                                            @endif

                                            @if($user->can(['member.report.total_stocks']))
                                                <li>
                                                    <a href="{{ route('member.report.total_stocks') }}"> {{ __('Stock Price Report') }} </a>
                                                </li>
                                            @endif

                                        </ul>
                                    </li>
                                @endif


                                @if($user->can(['member.report.balance-profit']))
                                    <li class="treeview">
                                        <a href="#">
                                            <span> {{ __('report.summary_reports') }}</span>
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </a>
                                        <ul class="treeview-menu">

                                            @if($user->can(['member.report.balance-profit']))
                                                <li><a href="{{ route('member.report.balance-profit')}}"> Profit
                                                        Balance </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif


                                @if($user->can(['member.categories.index','member.categories.create']))
                                    <li class="treeview">
                                        <a href="#">
                                            <i class="fa fa-list"></i> <span>{{ __('common.categories') }}</span>
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </a>
                                        <ul class="treeview-menu">
                                            @if($user->can(['member.categories.index']))
                                                <li><a href="{{ route('member.categories.index') }}"><i
                                                            class="fa fa-unlock-alt"></i> {{ __('common.categories') }}
                                                    </a></li>
                                            @endif

                                            @if($user->can(['member.categories.create']))
                                                <li><a href="{{ route('member.categories.create') }}"><i
                                                            class="fa fa-plus"></i> {{ __('common.add_category') }}
                                                    </a></li>
                                            @endif

                                        </ul>
                                    </li>

                                @endif

                                @if($user->can(['member.items.create','member.items.print_barcode_form','member.items.index']))

                                    <li class="treeview">
                                        <a href="#">
                                            <i class="fa fa-product-hunt"></i>
                                            <span>{{ __('common.manage_products') }}</span>
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </a>
                                        <ul class="treeview-menu">
                                            @if($user->can(['member.items.index']))
                                                <li><a href="{{ route('member.items.index') }}"><i
                                                            class="fa fa-unlock-alt"></i> {{ __('common.products') }}
                                                    </a></li>
                                            @endif

                                            @if($user->can(['member.items.create']))
                                                <li><a href="{{ route('member.items.create') }}"><i
                                                            class="fa fa-plus"></i> {{ __('common.add_product') }}
                                                    </a></li>
                                            @endif

                                            @if($user->can(['member.items.print_barcode_form']))
                                                <li><a href="{{ route('member.items.print_barcode_form') }}"><i
                                                            class="fa fa-barcode"></i> {{ __('common.barcode_print') }}
                                                    </a>
                                                </li>
                                            @endif

                                        </ul>
                                    </li>
                                @endif

                                @if($user->can(['member.units.index']))
                                    <li>
                                        <a href="{{ route('admin.units.index') }}">
                                            <i class="fa fa-tags"></i> <span>{{ __('common.units') }}</span>
                                        </a>
                                    </li>
                                @endif


                                @if($user->can(['member.variants.index','member.variants.create']))
                                    <li class="treeview">
                                        <a href="#">
                                            <i class="fa fa-map-o"></i>
                                            <span>{{ __('Variants') }}</span>
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </a>
                                        <ul class="treeview-menu">

                                            @if($user->can(['member.variants.index']))
                                                <li><a href="{{ route('member.variants.index') }}"><i
                                                            class="fa fa-map"></i> {{ __('Variants') }}</a>
                                                </li>
                                            @endif

                                            @if($user->can(['member.variants.create']))
                                                <li><a href="{{ route('member.variants.create') }}"><i
                                                            class="fa fa-plus"></i>{{ __('Add Variant') }} </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif

                                @if($user->can(['member.brand.index','member.brand.create']))
                                    <li class="treeview">
                                        <a href="#">
                                            <i class="fa fa-map-o"></i>
                                            <span>{{ __('common.manage_brands') }}</span>
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </a>
                                        <ul class="treeview-menu">

                                            @if($user->can(['member.brand.index']))
                                                <li><a href="{{ route('member.brand.index') }}"><i
                                                            class="fa fa-map"></i> {{ __('common.brands') }}</a>
                                                </li>
                                            @endif

                                            @if($user->can(['member.brand.create']))
                                                <li><a href="{{ route('member.brand.create') }}"><i
                                                            class="fa fa-plus"></i>{{ __('common.add_brand') }} </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif

                            </ul>
                        </li>

                    </ul>
                </li>

            @endif



            @if(config('settings.requisition'))

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-shopping-basket"></i>
                        <span> Requisition Management </span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

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

                                    @if($user->hasRole('dealer') || $user->can(['member.dealer_sales.index']))
                                        <li><a href="{{ route('member.dealer_sales.index') }}">
                                                @if($user->can(['admin','super-admin']))
                                                    Dealer
                                                @endif

                                                {{ __('Sales') }}
                                            </a></li>
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
                                                    <li>
                                                        <a href="{{ route('member.report.dealer_daily_stocks') }}">
                                                            Dealer {{ __('report.daily_stocks') }}</a></li>
                                                @endif

                                                @if( $user->can([ 'member.report.dealer_stocks']))
                                                    <li>
                                                        <a href="{{ route('member.report.dealer_stocks') }}">
                                                            Dealer {{ __('report.stocks') }}</a></li>
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
                                        <li><a href="{{ route('member.customers.index') }}"><i
                                                    class="fa fa-users"></i>
                                                Shopkeepers</a>
                                        </li>
                                    @endif
                                    @if($user->can(['member.customers.create' ]))
                                        <li><a href="{{ route('member.customers.create', 'customer') }}"><i
                                                    class="fa fa-plus"></i>
                                                Add Shopkeeper</a></li>
                                    @endif
                                </ul>
                            </li>

                    </ul>
                </li>
            @endif

            @endif


            @if(config('settings.quotation'))


                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-list-alt"></i> <span>{{ __('Quotation Management') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                    <ul class="treeview-menu">

                        @if($user->can(['member.quotations.create','member.quotations.index','member.quotations.add-others-transaction']))
                            <li class="treeview ">
                                <a href="#">
                                    <i class="fa fa-list-alt"></i>
                                    <span> {{ __('sale.quotation_title') }} </span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

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
                                            <a href="{{ route('member.quotations.add-others-transaction') }}">{{ __('Add Others Transaction') }}</a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif


                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-list-alt"></i>
                                <span> {{ __('sale.quotation_title_setting') }} </span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">


                                @if($user->can(['member.quotation_terms.index','member.quotation_terms.create',
                                'member.quotation_sub_terms.index','member.quotation_sub_terms.create']))
                                    <li class="treeview">
                                        <a href="#">
                                            <span> Quotation Terms & Conditions </span>
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </a>
                                        <ul class="treeview-menu">
                                            @if($user->can(['member.quotation_terms.create']))
                                                <li>
                                                    <a href="{{ route('member.quotation_terms.create') }}">Create
                                                        Terms </a>
                                                </li>
                                            @endif
                                            @if($user->can(['member.quotation_terms.index']))
                                                <li>
                                                    <a href="{{ route('member.quotation_terms.index') }}">List
                                                        Terms </a>
                                                </li>
                                            @endif
                                            @if($user->can(['member.quotation_sub_terms.create']))
                                                <li>
                                                    <a href="{{ route('member.quotation_sub_terms.create') }}">Create
                                                        Sub
                                                        Terms </a>
                                                </li>
                                            @endif
                                            @if($user->can(['member.quotation_sub_terms.index']))
                                                <li>
                                                    <a href="{{ route('member.quotation_sub_terms.index') }}">List
                                                        Sub
                                                        Terms </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif

                                @if($user->can(['member.quote_company.index','member.quote_company.create',
                                'member.quote_attentions.index','member.quote_attentions.create']))

                                    <li class="treeview">
                                        <a href="#">
                                            <span> Quotation Customer/Company </span>
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </a>

                                        <ul class="treeview-menu">

                                            @if($user->can(['member.quote_company.create']))
                                                <li>
                                                    <a href="{{ route('member.quote_company.create') }}">Create
                                                        Quote
                                                        Company</a>
                                                </li>
                                            @endif
                                            @if($user->can(['member.quote_company.index']))
                                                <li>
                                                    <a href="{{ route('member.quote_company.index') }}">List
                                                        Quote
                                                        Company </a>
                                                </li>
                                            @endif
                                            @if($user->can(['member.quote_attentions.create']))
                                                <li>
                                                    <a href="{{ route('member.quote_attentions.create') }}">Create
                                                        Quote
                                                        Customer </a>
                                                </li>
                                            @endif
                                            @if($user->can(['member.quote_attentions.index']))
                                                <li>
                                                    <a href="{{ route('member.quote_attentions.index') }}">List
                                                        Quote
                                                        Customer </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif

                                @if($user->can(['member.quoting.index','member.quoting.create']))

                                    <li class="treeview ">
                                        <a href="#">
                                            <span> Quoting Persons </span>
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </a>
                                        <ul class="treeview-menu">
                                            @if($user->can(['member.quoting.create']))
                                                <li>
                                                    <a href="{{ route('member.quoting.create') }}"> Create
                                                        Quoting
                                                        Person</a>
                                                </li>
                                            @endif

                                            @if($user->can(['member.quoting.index']))
                                                <li>
                                                    <a href="{{ route('member.quoting.index') }}"> List
                                                        Quoting
                                                        Person</a>
                                                </li>
                                            @endif

                                        </ul>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    </ul>
                </li>
            @endif


            @if(config('settings.warehouse'))

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-industry"></i> <span>{{ __('Warehouse Management') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                    <ul class="treeview-menu">

                        @if(
                        $user->can([
                        'member.warehouse.index',
                        'member.warehouse.create', 'member.warehouse.transfer',
                        'member.warehouse.history.index','member.warehouse.history.transfer_list']
                        ))

                            <li class="treeview">
                                <a href="javascript:void(0)">
                                    <i class="fa fa-industry"></i>
                                    <span> Warehouses</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if($user->can(['member.warehouse.index']))
                                        <li><a href="{{ route('member.warehouse.index') }}"><i
                                                    class="fa fa-industry"></i>
                                                Warehouse</a>
                                        </li>
                                    @endif

                                    @if($user->can(['member.warehouse.create']))
                                        <li><a href="{{ route('member.warehouse.create') }}"><i
                                                    class="fa fa-plus"></i>Add
                                                Warehouse</a>
                                        </li>
                                    @endif

                                    @if($user->can(['member.warehouse.transfer']))
                                        <li><a href="{{ route('member.warehouse.transfer') }}"><i
                                                    class="fa fa-plus"></i>Warehouse
                                                Product Transfer</a></li>
                                    @endif

                                    @if($user->can(['member.warehouse.history.index']))
                                        <li><a href="{{ route('member.warehouse.history.index') }}"><i
                                                    class="fa fa-list"></i>Warehouse
                                                Store History</a></li>
                                    @endif

                                    @if($user->can(['member.warehouse.history.transfer_list']))
                                        <li><a href="{{ route('member.warehouse.history.transfer_list') }}"><i
                                                    class="fa fa-list"></i>Warehouse Transfer History</a>
                                        </li>
                                    @endif

                                    @if($user->can(['member.warehouse.unload_not_done']))
                                        <li><a href="{{ route('member.warehouse.unload_not_done') }}"><i
                                                    class="fa fa-list"></i>Warehouse
                                                Unload Not Complete</a></li>
                                    @endif

                                    @if($user->can(['member.warehouse.load_not_done']))
                                        <li><a href="{{ route('member.warehouse.load_not_done') }}"><i
                                                    class="fa fa-list"></i>Warehouse
                                                load Not Complete</a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        @if($user->can([
                                  'member.report.daily_warehouse_stocks',
                                  'member.report.warehouse_stocks', 'member.report.warehouse_total_stocks',
                                  'member.report.warehouse_type_stocks']
                                  ))
                            <li class="treeview">
                                <a href="javascript:void(0)">
                                    <i class="fa fa-industry"></i>
                                    <span> {{ __('Warehouse Stock Reports') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if($user->can(['member.report.daily_warehouse_stocks']))
                                        <li>
                                            <a href="{{ route('member.report.daily_warehouse_stocks') }}">  {{ __('Warehouse Daily Stocks') }}</a>
                                        </li>
                                    @endif

                                    @if($user->can(['member.report.warehouse_stocks']))
                                        <li>
                                            <a href="{{ route('member.report.warehouse_stocks') }}">   {{ __('Warehouse Stocks') }}</a>
                                        </li>
                                    @endif

                                    @if($user->can(['member.report.warehouse_total_stocks']))
                                        <li>
                                            <a href="{{ route('member.report.warehouse_total_stocks') }}"> {{ __('Warehouse Stock Price ') }} </a>
                                        </li>
                                    @endif

                                    @if($user->can(['member.report.warehouse_type_stocks']))
                                        <li>
                                            <a href="{{ route('member.report.warehouse_type_stocks') }}">   {{ __(' Damage/Overflow Stocks') }}</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>

                        @endif


                    </ul>
                </li>

            @endif

            @if(config('settings.HR'))

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-id-badge"></i> <span>{{ __('HR Management') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                    <ul class="treeview-menu">
                        @if($user->can([
                'member.employee.index',
                'member.salary_management.index',
                'member.employee.create',
                'member.salary_management.salary-update']))

                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-id-badge"></i> <span>{{ __('Employee') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">


                                    @if($user->can(['member.employee.index']))
                                        <li><a href="{{ route('member.employee.index') }}"><i
                                                    class="fa fa-address-book"></i> {{ __('List') }}</a>
                                        </li>
                                    @endif

                                    @if($user->can(['member.employee.create']))
                                        <li><a href="{{ route('member.employee.create') }}"><i
                                                    class="fa fa-plus"></i>{{ __('Create') }} </a></li>
                                    @endif

                                    @if($user->can(['member.salary_management.index']))
                                        <li><a href="{{ route('member.salary_management.index') }}"><i
                                                    class="fa fa-plus"></i>{{ __('Salary Management') }}
                                            </a></li>
                                    @endif

                                    @if($user->can(['member.salary_management.salary-update']))
                                        <li><a href="{{ route('member.salary_management.salary-update') }}"><i
                                                    class="fa fa-plus"></i>{{ __('Salary Update') }} </a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif

                        {{-- @if(config('settings.hr')) --}}

                        @if($user->can(['member.department.index','member.department.create']))
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-briefcase"></i><span>{{ __('Department') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if($user->can(['member.department.index']))
                                        <li><a href="{{ route('member.department.index') }}"><i
                                                    class="fa fa-briefcase"></i> {{ __('List') }}</a></li>
                                    @endif

                                    @if($user->can(['member.department.create']))
                                        <li><a href="{{ route('member.department.create') }}"><i
                                                    class="fa fa-plus"></i>{{ __('Create') }} </a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        @if(config('settings.designation') && $user->can(['member.designation.index','member.designation.create']))

                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-briefcase"></i><span>{{ __('Designation') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    @if($user->can(['member.designation.index']))
                                        <li><a href="{{ route('member.designation.index') }}"><i
                                                    class="fa fa-briefcase"></i> {{ __('List') }}</a></li>
                                    @endif

                                    @if($user->can(['member.designation.create']))
                                        <li><a href="{{ route('member.designation.create') }}"><i
                                                    class="fa fa-plus"></i>{{ __('Create') }} </a></li>
                                    @endif

                                </ul>
                            </li>

                        @endif


                        @if($user->can(['member.holiday.create','member.holiday.index']))
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-calendar"></i><span>{{ __('Holiday') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if($user->can(['member.holiday.index']))
                                        <li><a href="{{ route('member.holiday.index') }}"><i
                                                    class="fa fa-calendar"></i> {{ __('List') }}</a></li>
                                    @endif

                                    @if($user->can(['member.holiday.create']))
                                        <li><a href="{{ route('member.holiday.create') }}"><i
                                                    class="fa fa-plus"></i>{{ __('Create') }} </a></li>
                                    @endif

                                </ul>
                            </li>

                        @endif

                        @if($user->can(['member.shift.create','member.shift.index']))
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-clock-o"></i><span>{{ __('Shift') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    @if($user->can(['member.shift.create']))
                                        <li><a href="{{ route('member.shift.index') }}"><i
                                                    class="fa fa-clock-o"></i> {{ __('List') }}</a></li>
                                    @endif

                                    @if($user->can(['member.shift.create']))
                                        <li><a href="{{ route('member.shift.create') }}"><i
                                                    class="fa fa-plus"></i>{{ __('Create') }} </a></li>
                                    @endif
                                </ul>
                            </li>

                        @endif



                        @if($user->hasRole(['super-admin','admin']) && $user->can(['admin.staff_support.index']))
                            <li class="treeview">
                                <a href="#">
                                    {{-- <i class="fa fa-torii-gate"></i> --}}
                                    <i class="fa fa-support"></i><span>{{ __('Staff Support') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    <li><a href="{{ route('admin.staff_support.index') }}"><i
                                                class="fa fa-support"></i> {{ __('List') }}</a>
                                    </li>

                                </ul>
                            </li>

                        @elseif($user->can(['member.support.index', 'member.support.create']))

                            <li class="treeview">
                                <a href="#">
                                    {{-- <i class="fa fa-torii-gate"></i> --}}
                                    <i class="fa fa-support"></i><span>{{ __('Supports') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if($user->can(['member.support.index']))
                                        <li><a href="{{ route('member.support.index') }}">
                                                <i class="fa fa-support"></i> {{ __('Support List') }}</a>
                                        </li>
                                    @endif

                                    @if($user->can(['member.support.create']))
                                        <li><a href="{{ route('member.support.create') }}">
                                                <i class="fa fa-plus-circle"></i> {{ __('Support Request') }}
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>

                        @endif


                        @if($user->can(['member.process-attendances','member.checkinout-attendances','member.master-attendances','member.attendances.create','member.summary-attendances']))

                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-calendar-check-o"></i><span>{{ __('Attendance') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if($user->can(['member.process-attendances']))
                                        <li><a href="{{ route('member.process-attendances') }}"><i
                                                    class="fa fa-spinner"></i> {{ __('Process Attendance') }}
                                            </a></li>
                                    @endif


                                    @if($user->can(['member.checkinout-attendances']))
                                        <li><a href="{{ route('member.checkinout-attendances') }}"><i
                                                    class="fa fa-calendar-check-o"></i> {{ __('See Daily Checkin-out') }}
                                            </a></li>
                                    @endif


                                    @if($user->can(['member.master-attendances']))
                                        <li><a href="{{ route('member.master-attendances') }}"><i
                                                    class="fa fa-calendar-check-o"></i> {{ __('See Attendance Master') }}
                                            </a></li>
                                    @endif


                                    @if($user->can(['member.summary-attendances']))
                                        <li><a href="{{ route('member.summary-attendances') }}"><i
                                                    class="fa fa-calendar-check-o"></i> {{ __('Attendance Summary') }}
                                            </a></li>
                                    @endif

                                    @if($user->can(['member.attendances.create']))
                                        <li><a href="{{ route('member.attendances.create') }}"><i
                                                    class="fa fa-plus"></i>{{ __('Set Manual Attendance') }}
                                            </a></li>
                                    @endif


                                </ul>
                            </li>


                        @endif

                        @if($user->can(['member.leaves.create','member.leaves.index']))
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-calendar-minus-o"></i><span>{{ __('Type of Leaves') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    @if($user->can(['member.leaves.index']))
                                        <li><a href="{{ route('member.leaves.index') }}"><i
                                                    class="fa fa-calendar-minus-o"></i>{{ __('Leave List') }}
                                            </a></li>
                                    @endif

                                    @if($user->can(['member.leaves.create']))
                                        <li><a href="{{ route('member.leaves.create') }}"><i
                                                    class="fa fa-plus"></i>{{ __('Create ') }} </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>

                        @endif

                        @if($user->can(['member.employee-leave.create','member.employee-leaves.index']))
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-calendar-minus-o"></i><span>{{ __('Employee Leaves') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    @if($user->can(['member.employee-leaves.index']))
                                        <li><a href="{{ route('member.employee-leaves.index') }}"><i
                                                    class="fa fa-calendar-minus-o"></i>{{ __('Employee Leave List') }}
                                            </a></li>
                                    @endif

                                    @if($user->can(['member.employee-leaves.create']))
                                        <li><a href="{{ route('member.employee-leaves.create') }}"><i
                                                    class="fa fa-plus"></i>{{ __('Create ') }} </a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        @if($user->can(['member.report.employee-attendance']))

                            {{--<li class="treeview">--}}
                            {{--<a href="javascript:void(0)">--}}
                            {{--<i class="fa fa-bar-chart"></i>--}}
                            {{--<span>{{ __('report.title') }}</span>--}}
                            {{--<i class="fa fa-angle-left pull-right"></i>--}}
                            {{--</a>--}}
                            {{--<ul class="treeview-menu">--}}

                            @if(config('settings.requisition') && config('settings.employee') )
                                @if($user->can(['member.report.employee-attendance']))

                                    {{--<li class="treeview">--}}
                                    {{--<a href="#">--}}
                                    {{--<span>{{ __('Employees Report') }}</span>--}}
                                    {{--<i class="fa fa-angle-left pull-right"></i>--}}
                                    {{--</a>--}}
                                    {{--<ul class="treeview-menu">--}}

                                    {{--<li><a href="{{ route('member.report.employee-attendance')}}"> Attendance Report </a></li>--}}


                                    {{--</ul>--}}
                                    {{--</li>--}}
                                @endif
                            @endif
                            {{--</ul>--}}
                            {{--</li>--}}
                    </ul>
                </li>


            @endif


            @endif

            @if(config('settings.project'))

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-th"></i><span>{{ __('Project Management') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if($user->can(['member.project_category.index', 'member.project_category.create']))



                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-file-text"></i><span>{{ __('Project Category') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if($user->can([ 'member.project_category.index']))
                                        <li><a href="{{ route('member.project_category.index') }}">
                                                <i class="fa fa-building"></i> {{ __('List') }}</a></li>

                                    @endif

                                    @if($user->can([ 'member.project_category.create']))
                                        <li><a href="{{ route('member.project_category.create') }}">
                                                <i class="fa fa-plus"></i>{{ __('Create') }} </a></li>

                                    @endif

                                </ul>
                            </li>

                        @endif
                        @if($user->can(['member.project.index', 'member.project.create']))

                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-file-text-o"></i><span>{{ __('Project') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">


                                    @if($user->can([ 'member.project.index']))
                                        <li><a href="{{ route('member.project.index') }}"><i
                                                    class="fa fa-building"></i> {{ __('List') }}</a>
                                        </li>

                                    @endif

                                    @if($user->can([ 'member.project.create']))
                                        <li><a href="{{ route('member.project.create') }}"><i
                                                    class="fa fa-plus"></i> {{ __('Create') }}</a>
                                        </li>
                                    @endif

                                </ul>
                            </li>

                        @endif


                        @if($user->can(['member.lead_category.index', 'member.lead_category.create']))

                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-th-large"></i><span>{{ __('Lead Category') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">


                                    @if($user->can([ 'member.lead_category.index']))
                                        <li><a href="{{ route('member.lead_category.index') }}"><i
                                                    class="fa fa-th-large"></i> {{ __('List') }}</a></li>

                                    @endif

                                    @if($user->can([ 'member.lead_category.create']))
                                        <li><a href="{{ route('member.lead_category.create') }}">
                                                <i class="fa fa-plus"></i>{{ __('Create') }} </a></li>

                                    @endif

                                </ul>
                            </li>

                        @endif



                        @if($user->can(['member.client.index', 'member.client.create']))

                            <li class="treeview">
                                <a href="#">
                                    {{-- <i class="fa fa-torii-gate"></i> --}}
                                    <i class="fa fa-male"></i><span>{{ __('Client') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    @if($user->can([ 'member.client.index']))
                                        <li><a href="{{ route('member.client.index') }}"><i
                                                    class="fa fa-male"></i> {{ __('List') }}</a>
                                        </li>

                                    @endif

                                    @if($user->can([ 'member.client.create']))
                                        <li><a href="{{ route('member.client.create') }}"><i
                                                    class="fa fa-plus"></i> {{ __('Create') }}</a>
                                        </li>
                                    @endif

                                </ul>
                            </li>


                        @endif


                        @if($user->can(['member.client_company.index', 'member.client_company.create']))
                            <li class="treeview">
                                <a href="#">
                                    {{-- <i class="fa fa-torii-gate"></i> --}}
                                    <i class="fa fa-building-o"></i><span>{{ __('Client Company') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">


                                    @if($user->can([ 'member.client_company.index']))
                                        <li><a href="{{ route('member.client_company.index') }}"><i
                                                    class="fa fa-building-o"></i> {{ __('List') }}</a>
                                        </li>

                                    @endif

                                    @if($user->can([ 'member.client_company.create']))
                                        <li><a href="{{ route('member.client_company.create') }}"><i
                                                    class="fa fa-plus"></i> {{ __('Create') }}</a>
                                        </li>
                                    @endif

                                </ul>
                            </li>


                        @endif


                        @if($user->can(['member.task.index', 'member.task.create']))
                            <li class="treeview">
                                <a href="#">
                                    {{-- <i class="fa fa-torii-gate"></i> --}}
                                    <i class="fa fa-tasks"></i><span>{{ __('Tasks') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">

                                    @if($user->can(['member.task.index']))
                                        <li><a href="{{ route('member.task.index') }}"><i
                                                    class="fa fa-tasks"></i> {{ __('List') }}</a>
                                        </li>

                                    @endif

                                    @if($user->can([ 'member.task.create']))
                                        <li><a href="{{ route('member.task.create') }}"><i
                                                    class="fa fa-plus"></i> {{ __('Create') }}</a>
                                        </li>
                                    @endif

                                </ul>
                            </li>



                        @endif


                        @if($user->can(['member.lead.index', 'member.lead.create']))

                            <li class="treeview">
                                <a href="#">
                                    {{-- <i class="fa fa-torii-gate"></i> --}}
                                    <i class="fa fa-cubes"></i><span>{{ __('Lead') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">


                                    @if($user->can([ 'member.lead.index']))
                                        <li><a href="{{ route('member.lead.index') }}"><i
                                                    class="fa fa-cubes"></i> {{ __('List') }}</a>
                                        </li>


                                    @endif

                                    @if($user->can([ 'member.lead.create']))
                                        <li><a href="{{ route('member.lead.create') }}"><i
                                                    class="fa fa-plus-square"></i> {{ __('Create') }}</a>
                                        </li>

                                    @endif

                                </ul>
                            </li>

                        @endif



                        @if($user->can(['member.sharer.broker_list', 'member.broker.create']))
                            <li class="treeview">
                                <a href="javascript:void(0)">
                                    <i class="fa fa-users"></i>
                                    <span> {{ __('Brokers') }}</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    @if($user->can(['member.sharer.broker_list']))
                                        <li><a href="{{ route('member.sharer.broker_list') }}"><i
                                                    class="fa fa-users"></i> {{ __('Broker List') }}</a>
                                        </li>
                                    @endif

                                    @if($user->can(['member.broker.create']))
                                        <li><a href="{{ route('member.sharer.create', 'broker') }}"><i
                                                    class="fa fa-plus"></i> {{ __('Create Broker') }}</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>

            @endif


            @if($user->can(['super-admin', 'admin']))
                <li class="header">Manage Function</li>

            @endif


            @if($user->can([
            'member.cash_or_bank_accounts.create', 'member.cash_or_bank_accounts.index',
            'member.cash_or_bank_accounts.bank_gl_account'
            ]))

                <li class="treeview">
                    <a href="javascript:void(0)">
                        <i class="fa fa-university"></i>
                        <span> {{ __('cash_bank.title') }} </span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if($user->can(['member.cash_or_bank_accounts.create']))
                            <li><a href="{{ route('member.cash_or_bank_accounts.create') }}">
                                    {{ __('cash_bank.add') }} </a></li>
                        @endif

                        @if($user->can(['member.cash_or_bank_accounts.index']))
                            <li><a href="{{ route('member.cash_or_bank_accounts.index') }}">
                                    {{ __('cash_bank.list') }} </a></li>
                        @endif

                        @if($user->can(['member.cash_or_bank_accounts.bank_gl_account']))

                            <li><a href="{{ route('member.cash_or_bank_accounts.bank_gl_account') }}">
                                    {{ __('cash_bank.gl') }}</a></li>
                        @endif

                    </ul>
                </li>

            @endif


            @if($user->can(['member.sharer.supplier_list', 'member.supplier.create']))
                <li class="treeview">
                    <a href="javascript:void(0)">
                        <i class="fa fa-users"></i>
                        <span> {{ __('sharer.supplier_title') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if($user->can(['member.sharer.supplier_list']))
                            <li><a href="{{ route('member.sharer.supplier_list') }}"><i
                                        class="fa fa-users"></i> {{ __('sharer.supplier_list') }}</a></li>
                        @endif

                        @if($user->can(['member.supplier.create']))
                            <li><a href="{{ route('member.sharer.create', 'supplier') }}"><i
                                        class="fa fa-plus"></i> {{ __('sharer.supplier_add') }}</a></li>
                        @endif
                    </ul>
                </li>
            @endif



            @if($user->can(['member.sharer.customer_list', 'member.customer.create']))

                <li class="treeview">
                    <a href="javascript:void(0)">
                        <i class="fa fa-users"></i>
                        <span>{{ __('sharer.customer_title') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if($user->can(['member.sharer.customer_list']))
                            <li><a href="{{ route('member.sharer.customer_list') }}"><i
                                        class="fa fa-users"></i> {{ __('sharer.customer_list') }}</a></li>
                        @endif


                        @if($user->can(['member.customer.create']))
                            <li><a href="{{ route('member.sharer.create', 'customer') }}"><i
                                        class="fa fa-plus"></i> {{ __('sharer.customer_add') }}</a></li>
                        @endif

                    </ul>
                </li>

            @endif


            @if($user->can(['admin.account_types.create', 'admin.account_types.index', 'admin.gl_account_class.index']))
                <li class="treeview">

                    <a href="#">
                        <i class="fa fa-medium"></i> <span>{{ __('common.gl_accounts') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">


                        @if($user->can(['admin.account_types.create']))
                            <li><a href="{{ route('admin.account_types.create') }}"><i
                                        class="fa fa-plus"></i> {{ __('common.account_add') }}</a></li>

                        @endif

                        @if($user->can(['admin.account_types.index']))
                            <li><a href="{{ route('admin.account_types.index') }}"><i
                                        class="fa fa-list"></i> {{ __('common.account_list') }} </a></li>
                        @endif
                        {{--<li><a href="{{ route('admin.account_types.group') }}"><i--}}
                        {{--class="fa fa-group"></i> {{ __('common.account_group') }} </a></li>--}}
                        {{--<li><a href="{{ route('admin.account_types.heads') }}"><i--}}
                        {{--class="fa fa-medium"></i> {{ __('common.account_heads') }} </a></li>--}}
                        {{--<li><a href="{{ route('admin.account_types.sub_heads') }}"><i--}}
                        {{--class="fa fa-medium"></i> {{ __('common.account_sub_heads') }} </a></li>--}}


                        @if($user->can([ 'admin.gl_account_class.index']))
                            <li><a href="{{ route('admin.gl_account_class.index') }}"><i
                                        class="fa fa-medium"></i> {{ __('common.account_class') }} </a></li>
                        @endif
                    </ul>
                </li>
            @endif

            @if($user->can(['super-admin']))
                <li class="header">Admin Settings</li>

            @endif


            @if($user->can(['admin.transactions-error.index']))
                <li class="treeview">
                    <a href="javascript:void(0)">
                        <i class="fa fa-money"></i> <span>Error Reports</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('admin.transactions-error.index')}}"> Transaction Errors </a>
                        </li>
                    </ul>
                </li>

            @endif


            @if(config('settings.accounts'))

                @if(config('accounts-system.balance_adjustment') && $user->can(['admin.ledger_book.set_account_head_balance', 'admin.ledger_book.update_all_account_head_balance']))

                    <li class="treeview">

                        <a href="#">
                            <i class="fa fa-money"></i> <span>Report Balance Adjustment</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            @if($user->can(['admin.ledger_book.set_account_head_balance']))
                                <li><a href="{{ route('admin.ledger_book.set_account_head_balance') }}"><i
                                            class="fa fa-bar-chart-o"></i> Account Head wise </a></li>
                            @endif

                            @if($user->can(['admin.ledger_book.update_all_account_head_balance']))
                                <li>
                                    <a href="{{ route('admin.ledger_book.update_all_account_head_balance') }}"
                                       class="process-confirm"><i class="fa fa-bar-chart"></i> All Account
                                        Head At A
                                        Time </a></li>
                            @endif

                            @if($user->can(['member.report.account_day_wise_last_balance']))
                                <li><a href="{{ route('member.report.account_day_wise_last_balance')}}">
                                        Account Head
                                        Daywise Balance </a></li>
                            @endif

                        </ul>
                    </li>

                @endif


            @endif


            @if($user->can(['admin.stock_reconcile', 'admin.yearly_stock_reconcile' ]))

                <li class="treeview">
                    <a href="javascript:void(0)">
                        <i class="fa fa-shopping-basket"></i> <span> Stock Reconcile</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if($user->can(['admin.stock_reconcile']))
                            <li><a href="{{ route('admin.stock_reconcile', 'damage')}}"> Damage Stock </a>
                            </li>
                        @endif

                        @if($user->can(['admin.stock_reconcile']))
                            <li><a href="{{ route('admin.stock_reconcile', 'loss')}}"> Loss Stock </a></li>
                        @endif

                        @if($user->can(['admin.stock_reconcile']))
                            <li><a href="{{ route('admin.stock_reconcile', 'expired')}}"> Expired Stock </a>
                            </li>
                        @endif

                        @if($user->can(['admin.yearly_stock_reconcile']))
                            <li><a href="{{ route('admin.yearly_stock_reconcile')}}"> Yearly Damage/Overflow
                                    Stock </a></li>
                        @endif

                    </ul>
                </li>

            @endif


            @if($user->can(['admin.get_update_stock_report', 'admin.check_update_stock_report']))

                <li class="treeview">

                    <a href="#">
                        <i class="fa fa-pie-chart"></i> <span>Update Stock</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if($user->can(['admin.get_update_stock_report']))
                            <li><a href="{{ route('admin.get_update_stock_report') }}">
                                    Update Stock Report </a></li>
                        @endif

                        @if($user->can(['admin.check_update_stock_report']))
                            <li><a href="{{ route('admin.check_update_stock_report') }}">
                                    SP Check and Update Stock Report </a></li>
                        @endif
                    </ul>
                </li>

            @endif

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

