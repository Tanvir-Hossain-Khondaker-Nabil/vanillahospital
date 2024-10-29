<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 7/6/2023
 * Time: 11:29 AM
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


            </div>
        </div>

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

            @if(config('settings.accounts'))

                    <li class="header">{{__('common.accounting')}}</li>



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
                                        class="fa fa-list"></i> {{ __('transaction.list_transaction') }}</a></li>
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


            @endif

            @if($user->can(['member.requisition_expenses.index','member.requisition_expenses.create']))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-briefcase"></i><span>{{ __('common.requisition_expenses') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if($user->can(['member.requisition_expenses.index']))
                            <li><a href="{{ route('member.requisition_expenses.index') }}"><i
                                        class="fa fa-briefcase"></i> {{ __('common.list') }}</a></li>
                        @endif

                        @if($user->can(['member.requisition_expenses.create']))
                            <li><a href="{{ route('member.requisition_expenses.create') }}"><i
                                        class="fa fa-plus"></i>{{ __('common.create') }} </a></li>
                        @endif
                    </ul>
                </li>
            @endif

            @if(config('settings.accounts') && $user->can(['reports']) )

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
                                                <a href="{{ route('member.report.sharer_balance_report', 'customer_type=customer') }}"> {{__('common.debtor_balance')}} </a></li>

                                        @endif

                                        @if($user->can(['member.report.sharer_balance_report']))
                                            <li>
                                                <a href="{{ route('member.report.sharer_balance_report', 'customer_type=supplier') }}">  {{__('common.creditor_balance')}} </a></li>

                                        @endif

                                        @if($user->can(['member.report.ledger_book']))
                                        <li>
                                            <a href="{{ route('member.report.ledger_book') }}"> {{ __('report.ledger_book') }} </a>
                                        </li>


                                        @endif

                                        @if($user->can(['member.report.trail_balance_v2']))

                                        @if(config('accounts-system.trail_balance_v2'))
                                            <li><a href="{{ route('member.report.trail_balance_v2') }}"> {{ __('report.trial_balance') }} V2 </a></li>
                                        @else
                                            <li> <a href="{{ route('member.report.trail_balance') }}"> {{ __('report.trial_balance') }} </a> </li>
                                        @endif
                                            {{--                            <li><a href="{{ route('member.report.ledger_book_by_manager') }}"> {{ __('report.ledger_book_by_manager') }}</a></li>--}}
                                            {{--                            <li><a href="{{ route('member.report.ledger_book_by_sharer') }}"> {{ __('report.ledger_book_by_sharer') }}</a></li>--}}
                                        @endif

                                        @if($user->can(['member.report.daily_sheet']))
                                            <li>
                                                <a href="{{ route('member.report.daily_sheet') }}"> {{ __('report.daily_sheet') }}</a>  </li>

                                        @endif

                                        @if($user->can(['member.report.lost_profit']))
                                            <li>
                                                <a href="{{ route('member.report.lost_profit')}}"> {{ __('report.profit_loss') }} </a> </li>
                                        @endif

                                        @if($user->can(['member.report.balance_sheet']))
                                            <li>
                                                <a href="{{ route('member.report.balance_sheet')}}"> {{ __('report.balance_sheet') }} </a> </li>
                                        @endif

                                    </ul>
                                </li>

                            @endif

                        </ul>
                    </li>


            @endif


            @if(config('settings.accounts'))

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
                                        class="fa fa-dollar"></i> {{ __('common.reconciliations') }}</a></li>

                            @endif

                            @if($user->can(['member.reconciliation.create'])  )
                            <li><a href="{{ route('member.reconciliation.create', 'supplier') }}"><i
                                        class="fa fa-plus"></i> {{ __('common.supplier_reconciliation') }} </a></li>
                            <li><a href="{{ route('member.reconciliation.create', 'bank') }}"><i
                                        class="fa fa-plus"></i> {{ __('common.bank_reconciliation') }}</a></li>
                            @endif
                        </ul>
                    </li>

                @endif

            @endif


            @if($user->can(['super-admin', 'admin']))
                <li class="header">{{ __('common.manage_function') }}</li>

            @endif


            @if(config('settings.accounts') || $user->hasRole(['super-admin']))

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

            <li class="treeview">

                <a href="#">
                    <i class="fa fa-medium"></i> <span>{{ __('common.gl_accounts') }}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.account_types.create') }}"><i
                                class="fa fa-plus"></i> {{ __('common.account_add') }}</a></li>
                    <li><a href="{{ route('admin.account_types.index') }}"><i
                                class="fa fa-list"></i> {{ __('common.account_list') }} </a></li>
                    {{--<li><a href="{{ route('admin.account_types.group') }}"><i--}}
                    {{--class="fa fa-group"></i> {{ __('common.account_group') }} </a></li>--}}
                    {{--<li><a href="{{ route('admin.account_types.heads') }}"><i--}}
                    {{--class="fa fa-medium"></i> {{ __('common.account_heads') }} </a></li>--}}
                    {{--<li><a href="{{ route('admin.account_types.sub_heads') }}"><i--}}
                    {{--class="fa fa-medium"></i> {{ __('common.account_sub_heads') }} </a></li>--}}
                    <li><a href="{{ route('admin.gl_account_class.index') }}"><i
                                class="fa fa-medium"></i> {{ __('common.account_class') }} </a></li>
                </ul>
            </li>

            @if($user->can(['super-admin']))
                <li class="header">{{ __('common.admin_settings') }}</li>

            @endif

            @if(config('settings.imports') && $user->can(['admin.sharer_import.create']))

                <li class="treeview">
                    <a href="#">

                        <i class="fa fa-upload"></i> <span>{{ __('common.import') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        @if($user->can(['admin.sharer_import.create']))
                            <li><a href="{{ route('admin.sharer_import.create') }}"><i
                                        class="fa fa-upload"></i> {{ __('common.sharer_import') }}</a></li>
                        @endif

                    </ul>
                </li>
            @endif

            @if($user->can(['admin.transactions-error.index']))
                <li class="treeview">
                    <a href="javascript:void(0)">
                        <i class="fa fa-money"></i> <span>{{ __('common.error_reports') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('admin.transactions-error.index')}}">  {{ __('common.transaction_errors') }} </a></li>
                    </ul>
                </li>

            @endif


            @if(config('settings.accounts'))

                @if(config('accounts-system.balance_adjustment') && $user->can(['admin.ledger_book.set_account_head_balance', 'admin.ledger_book.update_all_account_head_balance']))

                    <li class="treeview">

                        <a href="#">
                            <i class="fa fa-money"></i> <span>{{__('common.report_balance_adjustment')}}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            @if($user->can(['admin.ledger_book.set_account_head_balance']))
                            <li><a href="{{ route('admin.ledger_book.set_account_head_balance') }}"><i class="fa fa-bar-chart-o"></i> {{__('common.account_head_wise')}} </a></li>
                            @endif

                            @if($user->can(['admin.ledger_book.update_all_account_head_balance']))
                            <li><a href="{{ route('admin.ledger_book.update_all_account_head_balance') }}" class="process-confirm"><i class="fa fa-bar-chart"></i> {{__('common.all_account_head_at_a_time')}} </a></li>
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

