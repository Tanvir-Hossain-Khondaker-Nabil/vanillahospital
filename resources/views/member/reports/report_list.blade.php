<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/18/2019
 * Time: 12:48 PM
 */
$title = trans('common.report');
$route = \Auth::user()->can(Route::current()->getName()) ? route(Route::current()->getName()): '#';
 $home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => $title,
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => $title,
    'title' => 'List Of ' . $title,
    'heading' => trans('common.list_of') .' '. $title,
];


$user = \Illuminate\Support\Facades\Auth::user();


?>
@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row small-text">
        <div class="col-xs-12">

            <div class="box">
                <div class="box-body report_list">
                    <div class="col-md-6">
                        <h3> {{__('report.purchase_reports')}}</h3>
                        <ul>

                            @if($user->can(['member.report.purchase']))
                                <li><a href="{{ route('member.report.purchase') }}"><i class="fa fa-line-chart"></i>
                                    {{__('report.purchase_reports')}}</a></li>
                            @endif

                            @if($user->can(['member.report.sharer_due']))
                                <li><a href="{{ route('member.report.sharer_due', 'supplier') }}"><i
                                            class="fa fa-line-chart"></i> {{__('report.supplier_due')}}</a></li>
                            @endif

                            @if($user->can(['member.report.sharer_due_collection']))
                                <li><a href="{{ route('member.report.sharer_due_collection', 'supplier') }}"><i
                                            class="fa fa-line-chart"></i> {{__('report.supplier_due_collection')}}</a></li>
                            @endif

                            @if($user->can(['member.report.inventory_due']))
                                <li><a href="{{ route('member.report.inventory_due', 'purchase') }}"><i
                                            class="fa fa-line-chart"></i> {{__('report.purchase_due')}} </a></li>
                            @endif

                            @if($user->can(['member.report.cost']))
                                <li><a href="{{ route('member.report.cost', 'transport') }}"><i
                                            class="fa fa-line-chart"></i> {{__('report.transport_cost')}} </a></li>
                            @endif

                            @if($user->can(['member.report.cost']))
                                <li><a href="{{ route('member.report.cost', 'unload')}}"><i
                                            class="fa fa-line-chart"></i> {{__('report.unload_cost')}} </a></li>
                            @endif

                            @if($user->can(['member.report.product_purchase_report']))
                                <li><a href="{{ route('member.report.product_purchase_report', 'product')}}"><i
                                            class="fa fa-line-chart"></i> {{__('report.purchase_report_by_product')}} </a></li>
                            @endif

                            @if($user->can(['member.report.product_purchase_report']))
                                <li><a href="{{ route('member.report.product_purchase_report', 'manager')}}"><i
                                            class="fa fa-line-chart"></i> {{__('report.purchase_report_by_manager')}} </a></li>
                            @endif

                            @if($user->can(['member.report.supplier_purchase']))
                                <li><a href="{{ route('member.report.supplier_purchase')}}"><i
                                            class="fa fa-line-chart"></i> {{__('report.purchase_report_by_supplier')}} </a></li>
                            @endif


                        </ul>
                    </div>


                    <div class="col-md-6">
                        <h3>{{__('report.sale_reports')}}</h3>
                        <ul>

                            @if($user->can(['member.report.sale']))
                                <li><a href="{{ route('member.report.sale') }}"><i class="fa fa-line-chart"></i> {{__('report.sale_reports')}}</a></li>
                            @endif

                            @if($user->can(['member.report.sharer_due']))
                                <li><a href="{{ route('member.report.sharer_due', 'customer') }}"><i
                                            class="fa fa-line-chart"></i> {{__('report.customer_due')}}</a></li>
                            @endif

                            @if($user->can(['member.report.sharer_due_collection']))
                                <li><a href="{{ route('member.report.sharer_due_collection', 'customer') }}"><i
                                            class="fa fa-line-chart"></i> {{__('report.customer_due_collection')}}</a></li>
                            @endif

                            @if($user->can(['member.report.inventory_due']))
                                <li><a href="{{ route('member.report.inventory_due', 'sale') }}"><i
                                            class="fa fa-line-chart"></i> {{__('report.sale_due')}} </a></li>
                            @endif

                            @if($user->can(['member.report.sale_report_by_product']))
                                <li><a href="{{ route('member.report.sale_report_by_product','product')}}"><i
                                            class="fa fa-line-chart"></i> {{__('report.sale_report_by_product')}}</a></li>
                            @endif

                            @if($user->can(['member.report.sale_report_by_product']))
                                <li><a href="{{ route('member.report.sale_report_by_product', 'manager')}}"><i
                                            class="fa fa-line-chart"></i> {{__('report.sale_report_by_manager')}} </a></li>
                            @endif

                            @if($user->can(['member.report.customer_sale']))
                                <li><a href="{{ route('member.report.customer_sale')}}"><i
                                            class="fa fa-line-chart"> </i> {{__('report.sale_report_by_customer')}} </a></li>
                            @endif

                        </ul>
                    </div>


                    <div class="col-md-6">
                        <h3>{{__('report.transaction_reports')}}</h3>
                        <ul>

                            @if($user->can(['member.report.all_transaction']))
                                <li><a href="{{ route('member.report.all_transaction') }}"><i
                                            class="fa fa-bar-chart"></i> {{__('report.all_transactions')}} </a></li>
                            @endif

                            @if($user->can(['member.report.all_transfer']))
                                <li><a href="{{ route('member.report.all_transfer') }}"><i class="fa fa-bar-chart"></i>
                                        {{__('report.all_transfers')}} </a></li>
                            @endif

                            @if($user->can(['member.report.all_expense']))
                                <li><a href="{{ route('member.report.all_expense') }}"><i class="fa fa-bar-chart"></i>
                                        {{__('report.all_expenses')}} </a></li>
                            @endif

                            @if($user->can(['member.report.all_income']))
                                <li><a href="{{ route('member.report.all_income') }}"><i class="fa fa-bar-chart"></i>
                                        {{__('report.all_income')}} </a></li>
                            @endif

                            @if($user->can(['member.report.all_journal_entry']))
                                <li><a href="{{ route('member.report.all_journal_entry') }}"><i
                                            class="fa fa-bar-chart"></i> {{__('report.all_journal_entry')}}</a></li>
                            @endif
                        </ul>
                    </div>


                    <div class="col-md-6">
                        <h3>{{__('report.summary_reports')}}</h3>
                        <ul>
                            {{--                            <li><a href="{{ route('member.report.cost_profit') }}"><i class="fa fa-bar-chart"></i> Cost/Profit</a></li>--}}


                            @if($user->can(['member.report.daily_stocks']))
                                <li><a href="{{ route('member.report.daily_stocks') }}"><i class="fa fa-bar-chart"></i>
                                        {{__('report.daily_stocks')}}</a></li>
                            @endif

                            @if($user->can(['member.report.stocks']))
                                <li><a href="{{ route('member.report.stocks') }}"><i class="fa fa-bar-chart"></i> {{__('report.stocks')}}</a>
                                </li>
                            @endif

                            @if($user->can(['member.report.trail_balance']))
                                <li><a href="{{ route('member.report.trail_balance') }}"><i
                                            class="fa fa-line-chart"></i> {{__('report.trial_balance')}} </a></li>
                            @endif

                            @if($user->can(['member.report.ledger_book']))
                                <li><a href="{{ route('member.report.ledger_book') }}"><i class="fa fa-line-chart"></i>
                                        {{__('report.ledger_book')}} </a></li>
                            @endif

                            @if($user->can(['member.report.sharer_balance_report']))
                                {{--                            <li><a href="{{ route('member.report.ledger_book_by_manager') }}"><i class="fa fa-line-chart"></i> Ledger Book By Manager</a></li>--}}
                                <li><a href="{{ route('member.report.sharer_balance_report') }}"><i
                                            class="fa fa-line-chart"></i> {{__('report.debtor_balance')}}</a></li>
                            @endif

                            @if($user->can(['member.report.daily_sheet']))
                                <li><a href="{{ route('member.report.daily_sheet') }}"><i class="fa fa-line-chart"></i>
                                        {{__('report.daily_sheet')}}</a></li>
                            @endif

                            @if($user->can(['member.report.balance_sheet']))
                                <li><a href="{{ route('member.report.balance_sheet')}}"><i class="fa fa-line-chart"></i>
                                        {{__('report.balance_sheet')}} </a></li>
                            @endif

                            @if($user->can(['member.report.lost_profit']))
                                <li><a href="{{ route('member.report.lost_profit')}}"><i class="fa fa-pie-chart"></i>
                                        {{__('report.profit_loss')}} </a></li>
                            @endif
                            {{--                            <li><a href="{{ route('member.report.cash_book')}}"><i class="fa fa-line-chart"></i> Cash Book </a></li>--}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

