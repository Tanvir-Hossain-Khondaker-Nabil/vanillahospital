<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/11/2019
 * Time: 2:01 PM
 */

 $route = \Auth::user()->can(['member.report.balance_sheet']) ? route('member.report.balance_sheet') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'List',
        'href' => $route,
    ],
    [
        'name' => 'Profit And Loss Report',
    ],
];

$data['data'] = [
    'name' => "Profit And Loss",
    'title'=> 'Profit And Loss',
    'heading' =>trans('common.profit_and_loss'),
];

$balance_sheet_key = 1;
$sale_no = $balance_sheet_key++;
$purchase_no = $balance_sheet_key++;
$purchase_return_no = $balance_sheet_key++;
$inventory_no = $balance_sheet_key++;
$sale_return_no = $balance_sheet_key++;

$parameter =  explode(Request::url(), Request::fullUrl());
$parameter = isset($parameter[1]) ? $parameter[1] : '';

if(config('settings.previous_balance_on_profit_balance_sheet'))
    $colspan = 4;
else
    $colspan = 3;

?>
@extends('layouts.back-end.master', $data)

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush

@section('contents')

    <div class="row">
        <div class="col-xs-12">
            @include('common._alert')

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('common.search')}} </h3>
                </div>

                {!! Form::open(['route' => ['member.report.lost_profit'],'method' => 'GET', 'role'=>'form' ]) !!}

                <div class="box-body">
                    <div class="row">
                        @if(Auth::user()->hasRole(['super-admin', 'developer']))
                            <div class="col-md-3">
                                <label>{{__('common.select_company')}}    </label>
                                {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>trans('common.select_company')]); !!}
                            </div>
                        @endif
                        <div class="col-md-3">
                            <label>{{__('common.fiscal_year')}}   </label>
                            {!! Form::select('fiscal_year', $fiscal_year, null ,['class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                        </div>
                        <div class="col-md-2">
                            <label>{{__('common.year')}}   </label>
                            <input class="form-control year" name="year" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-2">
                            <label>{{__('common.from_date')}}  </label>
                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-2">
                            <label>{{__('common.to_date')}}</label>
                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                        </div>
                        {{--<div class="col-md-12">--}}
                        {{--<div class="col-md-3 margin-top-23">--}}
                        {{--<input type="checkbox" name="t_based_view" value="1" {{ $t_based_view ? "checked" : "" }}/>--}}
                        {{--<label> T Based View </label>--}}
                        {{--</div>--}}

                        {{--</div>--}}
                        <div class="col-md-2 margin-top-23">
                            <label></label>
                            <input class="btn btn-sm btn-info" value="{{__('common.search')}}" type="submit"/>
                            <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i>{{__('common.reload')}} </a>

                        </div>
                    </div>
                    <!-- /.row -->
                </div>

                {!! Form::close() !!}
            </div>

            <div class="box">

                @include('member.reports.print_title_btn')

                <div class="box-header with-border">
                    <a href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=full_pl" class="btn btn-info  btn-sm  pull-right" id="btn-print"> <i class="fa fa-print"></i> {{__('common.print_profit_loss_details')}} </a>
                    <a href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=download_full_pl" class="btn btn-default  btn-sm  pull-right  mr-3"> <i class="fa fa-download"></i>{{__('common.download_profit_loss_details')}} </a>
                </div>

                <div class="box-body ">



                    {{--@include('member.reports.common._modal_profit')--}}


                    <div class="col-lg-12 profit_lost">
                        <table class="table table-striped" id="dataTable">
                            <thead>

                            <tr>
                                <th  colspan="{{$colspan}}"  style="border: none !important; padding-bottom: 20px;" class="text-center">
                                    <h3>{!!  $report_title !!}  </h3>
                                </th>
                            </tr>
                            </thead>

                            <tbody>

                            <tr>
                                <td class="text-uppercase report-head-tag border-1 "> {{__('common.particulars')}}</td>
                                <td class="text-uppercase report-head-tag width-100 border-1 ">{{__('common.notes')}} </td>

                                @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                    <td class="text-uppercase report-head-tag text-right  border-1 ">{{__('common.previous_taka')}}  <br/>
                                        {{ formatted_date_string($pre_toDate) }}</td>

                                @endif

                                    <td class="text-uppercase report-head-tag text-right border-1 ">{{__('common.taka')}} <br/> {{ formatted_date_string($toDate) }} </td>
                                    <td></td>
                            </tr>
                            <tr>
                                <th class="text-uppercase"  colspan="{{$colspan}}" >1.{{__('common.sale')}}({{__('common.net')}})</th>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#sales">A1. {{__('common.sale')}}</a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#sales"> {{ $sale_no }}</a> </td>

                                @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                    <td class="text-right">{{ create_money_format($pre_total_sales) }}</td>
                                @endif
                                    <td class="text-right">{{ create_money_format($total_sales) }}</td>
                                    <td class="text-right">
                                        <a
                                            href="{{ route('member.report.head_sales_report').$parameter}}"
                                            id="btn-print"><i class="fa fa-print"></i>  </a>
                                    </td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#sales_return">B1. {{__('common.sale_return')}}</a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#sales_return"> {{ $sale_return_no }}</a> </td>

                                @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                    <td class="text-right">{{ create_money_format($pre_total_sales_return) }}</td>
                                @endif
                                    <td class="text-right">{{ create_money_format($total_sales_return) }}</td>
                                    <td class="text-right">
                                        <a
                                            href="{{ route('member.report.head_sales_return_report').$parameter}}"
                                            id="btn-print"><i class="fa fa-print"></i>  </a>
                                    </td>
                            </tr>
                            <tr>
                                <th class="padding-left-40 text-uppercase " colspan="2">{{__('common.total')}}(A1-B1)</th>

                                @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                    <th class=" single-line text-right">{{ create_money_format($pre_net_sale) }}</th>
                                @endif
                                    <th class=" single-line text-right">{{ create_money_format($net_sale) }}</th>
                            </tr>
                            <tr>
                                <th class="text-uppercase"  colspan="{{$colspan}}" >2. {{__('common.less')}}: {{__('common.cost_of_goods_sold')}}</th>
                            </tr>
                            <tr>
                                <td class="padding-left-40" colspan="2">A. {{__('common.opening_stock')}}</td>

                                @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                    <td class="text-right">{{ create_money_format($pre_openingStock) }}</td>
                                @endif
                                    <td class="text-right">{{ create_money_format($openingStock) }}</td>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#purchases">B2. {{__('common.purchase')}} </a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#purchases">{{ $purchase_no }}</a></td>

                                @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                    <td class="text-right">{{ create_money_format($pre_total_purchases) }}</td>
                                @endif
                                    <td class="text-right">{{ create_money_format($total_purchases) }}</td>
                                    <th class="text-right">
                                        <a
                                            href="{{ route('member.report.head_purchases_report').$parameter }}"
                                            id="btn-print"><i class="fa fa-print"></i>  </a>
                                    </th>
                            </tr>
                            <tr>
                                <td class="padding-left-40" ><a href="javascript:void(0)" data-toggle="modal" data-target="#purchases_return">B3. {{__('common.purchase_return')}} </a></td>
                                <td class="text-center width-100" ><a href="javascript:void(0)" data-toggle="modal" data-target="#purchases_return">{{ $purchase_return_no }}</a></td>

                                @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                    <td class="text-right">{{ create_money_format($pre_total_purchases_return) }}</td>
                                @endif
                                    <td class="text-right">{{ create_money_format($total_purchases_return) }}</td>
                                    <th class="text-right">
                                        <a
                                            href="{{ route('member.report.head_purchases_return_report').$parameter }}"
                                            id="btn-print"><i class="fa fa-print"></i>  </a>
                                    </th>
                            </tr>
                            <tr>
                                <th class="padding-left-40 text-uppercase " colspan="2">B. {{__('common.total')}}(B2-B3)</th>

                                @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                    <th class=" single-line text-right">{{ create_money_format($pre_net_purchase) }}</th>
                                @endif
                                    <th class=" single-line text-right">{{ create_money_format($net_purchase) }}</th>
                            </tr>
                            <tr>
                                <th class="padding-left-40 text-uppercase " colspan="2">{{__('common.total')}} (A+B)</th>

                                @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                    <th class=" single-line text-right">{{ create_money_format($pre_total_AB) }}</th>
                                @endif
                                    <th class=" single-line text-right">{{ create_money_format($total_AB) }}</th>
                            </tr>
                            <tr>
                                <td class="padding-left-40" colspan="2"><a href="javascript:void(0)" data-toggle="modal" data-target="#inventories">C. {{__('common.closing_stock')}}</a></td>

                                @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                    <td class="text-right">({{ create_money_format($pre_total_inventory) }})</td>
                                @endif
                                    <td class="text-right">({{ create_money_format($total_inventory) }})</td>
                                    <th class="text-right">
                                        <a
                                            href="{{ route('member.report.head_inventory').$parameter }}"
                                            id="btn-print"><i class="fa fa-print"></i>  </a>
                                    </th>
                            </tr>
                            <tr>
                                <th class="padding-left-40 text-uppercase " colspan="2">{{__('common.total')}}(A+B-C)</th>

                                @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                    <th class=" single-line text-right">{{ create_money_format($pre_total_ABC) }}</th>
                                @endif
                                    <th class=" single-line text-right">{{ create_money_format($total_ABC) }}</th>
                            </tr>
                            <tr>
                                <td class="padding-left-40"  colspan="{{$colspan}}" > {{__('common.cost_of_good_sold')}} </td>
                                <th class="text-right">
                                    <a
                                        href="{{ route('member.report.cost_of_sold_report').$parameter }}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </th>
                            </tr>
                            @foreach($cost_of_sold_items as $key => $value)
                                <tr>
                                    <td class="padding-left-40" colspan="2">{{ $value['account_type_name'] }}</td>

                                    @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                        <td class="text-right">{{ $value['pre_balance']<0 ? "(".create_money_format((-1)*$value['pre_balance']).")" : create_money_format($value['pre_balance'])   }}</td>
                                    @endif
                                        @php
                                            $balance = $value['balance']-$value['pre_balance'];
                                        @endphp

                                        <td class="text-right">{{  $balance<0 ? "(".create_money_format((-1)*$balance).")" : create_money_format($balance) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th class="padding-left-40 text-uppercase" colspan="2">{{__('common.total_cost_of_good_sold')}}</th>

                                @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                    <th class="single-line text-right">{{ $pre_total_cost_of_sold >= 0 ? create_money_format($pre_total_cost_of_sold) :
"(".create_money_format((-1)*$pre_total_cost_of_sold).")" }}</th>
                                @endif
                                    <th class="single-line text-right">
                                        {{ $total_cost_of_sold >= 0 ? create_money_format($total_cost_of_sold) :
                "(".create_money_format((-1)*$total_cost_of_sold).")" }}
                                    </th>

                            </tr>
                            <tr>
                                <th class="text-uppercase  border-top-1" colspan="2">3. {{__('common.gross_profit')}} (1-2)</th>

                                @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                    <th class=" border-top-1 text-right">
                                        {{ $pre_gross_profit >= 0 ? create_money_format($pre_gross_profit) :  "(".create_money_format((-1)*$pre_gross_profit).")" }}
                                    </th>
                                @endif
                                    <th class=" border-top-1 text-right">
                                        {{ $gross_profit >= 0 ? create_money_format($gross_profit) :  "(".create_money_format((-1)*$gross_profit).")" }}
                                    </th>
                            </tr>
                            <tr>
                                <th class="text-uppercase"  colspan="{{$colspan}}" >4. {{__('common.less')}}: {{__('common.administrative_and_general_expenses')}}</th>
                                <th class="text-right">
                                    <a
                                        href="{{ route('member.report.expense_report').$parameter }}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </th>
                            </tr>

                            @foreach($expenses as $key => $value)
                                <tr>
                                    <td colspan="2" class=" {{ isset($value['child']) && $value['child'] == 'yes' ? 'padding-left-70 ' : 'padding-left-40'  }} {{ isset($value['parent']) && $value['parent'] == 'yes' ? ' text-bold ' : '' }}" >{{ $value['account_type_name'] }}</td>

                                    @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                        <td class="text-right">{{ $value['pre_balance']<0 ? "(".create_money_format((-1)*$value['pre_balance']).")" : create_money_format($value['pre_balance'])   }}</td>
                                    @endif
                                        <td class="text-right">{{  $value['balance']<0 ? "(".create_money_format((-1)*$value['balance']).")" : create_money_format($value['balance']) }}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <th class="padding-left-40 text-uppercase " colspan="2">{{__('common.total')}}</th>

                                @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                    <th class=" single-line text-right">
                                        {{ $pre_total_expenses >= 0 ? create_money_format($pre_total_expenses) :  "(".create_money_format((-1)*$pre_total_expenses).")" }}
                                    </th>
                                @endif
                                    <th class=" single-line text-right">
                                        {{ $total_expenses >= 0 ? create_money_format($total_expenses) :  "(".create_money_format((-1)*$total_expenses).")" }}
                                    </th>
                            </tr>

                            <tr>
                                <th class="text-uppercase"  colspan="{{$colspan}}" >5. {{__('common.income')}} </th>
                                <th class="text-right">
                                    <a
                                        href="{{ route('member.report.income_report').$parameter }}"
                                        id="btn-print"><i class="fa fa-print"></i>  </a>
                                </th>
                            </tr>
                            @foreach($incomes as $key => $value)
                                <tr>
                                    <td colspan="2" class=" {{ isset($value['child']) && $value['child'] == 'yes' ? 'padding-left-70 ' : 'padding-left-40'  }} {{ isset($value['parent']) && $value['parent'] == 'yes' ? ' text-bold ' : '' }}" > {{ $value['account_type_name'] }}</td>

                                    @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                        <td class="text-right">{{ $value['pre_balance']<0 ? "(".create_money_format((-1)*$value['pre_balance']).")" : create_money_format($value['pre_balance'])   }}</td>
                                    @endif
                                        <td class="text-right">{{  $value['balance']<0 ? "(".create_money_format((-1)*$value['balance']).")" : create_money_format($value['balance']) }}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <th class="padding-left-40 text-uppercase " colspan="2">{{__('common.total')}} </th>

                                @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                    <th class=" single-line text-right">
                                        {{ $pre_total_incomes >= 0 ? create_money_format($pre_total_incomes) : "(".create_money_format((-1)*$pre_total_incomes).")" }}
                                    </th>
                                @endif
                                    <th class=" single-line text-right">
                                        {{ $total_incomes >= 0 ? create_money_format($total_incomes) :  "(".create_money_format((-1)*$total_incomes).")" }}
                                    </th>
                            </tr>
                            <tr>
                                <th class="text-uppercase  border-top-1" colspan="2"> {{__('common.net')}} {{ $net_profit<0? trans('common.loss'): trans('common.profit') }} (3-4+5)</th>

                                @if(config('settings.previous_balance_on_profit_balance_sheet'))
                                    <th class=" border-top-1 text-right">{{ $pre_net_profit<0 ? "(".create_money_format((-1)*$pre_net_profit).")" : create_money_format($pre_net_profit ) }}</th>
                                @endif
                                    <th class=" border-top-1 text-right">{{  $net_profit<0 ? "(".create_money_format((-1)*$net_profit).")" : create_money_format($net_profit ) }}</th>
                            </tr>

                            </tbody>
                        </table>



                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection



@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        $(document).ready( function(){
            $('.select2').select2();
            $('.date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });
                @if($set_company_fiscal_year)
            var $setDate = new Date( '{{ str_replace("-", "/", $set_company_fiscal_year->start_date) }}' );
            var today = new Date($setDate.getFullYear(), $setDate.getMonth(), $setDate.getDate(), 0, 0, 0, 0);
            @endif

            $('.year').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                endDate: '+0d',
                setDate: today
            });

            $('.date').change( function (e) {
                $('.date').attr('required', true);
            });


            $(".account_type_view").click( function (e) {
                e.preventDefault();

                var $view = $(this).data('id');
                // alert($view);
                $("#"+$view).css('display', 'block');
            });
        });
    </script>
@endpush

