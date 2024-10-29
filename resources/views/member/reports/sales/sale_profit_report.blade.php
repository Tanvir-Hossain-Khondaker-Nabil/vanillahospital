<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 15-Dec-19
 * Time: 6:21 PM
 */
 $route = \Auth::user()->can(['member.report.daily_stocks']) ? route('member.report.daily_stocks') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Stocks',
        'href' => $route,
    ],

    [
        'name' => 'Sale Report By '.ucfirst(human_words($search_type)),
    ],
];

$data['data'] = [
    'name' => 'Sale Report By '.ucfirst(human_words($search_type)),
    'title'=> 'Sale Report By '.ucfirst(human_words($search_type)),
    // 'heading' => 'Sale Report By '.ucfirst(human_words($search_type)),
    'heading' => trans('common.sale_report_by')." ".trans('common.'.strtolower(human_words($search_type))),
];

?>
@extends('layouts.back-end.master', $data)

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush

@section('contents')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('common.search')}}</h3>
                </div>

            {!! Form::open(['route' => ['member.report.sale_profit_report'],'method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        @if(Auth::user()->hasRole(['super-admin', 'developer']))
                            <div class="col-md-3">
                                <label>  {{__('common.select_company')}} </label>
                                {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>trans('common.select_company')]); !!}
                            </div>
                        @endif
                        @if($search_type == "manager")

                        <div class="col-md-3">
                            <label>  {{__('common.select_manager')}} </label>
                            {!! Form::select('manager_id', $managers, null,['id'=>'manager_id','class'=>'form-control select2','placeholder'=>trans('common.select_manager')]); !!}
                        </div>

                        @elseif($search_type == "area_product")

                        {{-- <div class="col-md-3">
                            <label>  Select Manager </label>
                            {!! Form::select('manager_id', $managers, null,['id'=>'manager_id','class'=>'form-control select2','placeholder'=>'Select Manager']); !!}
                        </div> --}}

                        <div class="col-md-3">
                            <label>  {{__("common.product_name")}} </label>
                            {!! Form::select('item_id', $products, null,['id'=>'item_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                        </div>

                        <div class="col-md-3">
                            <label>  {{__('common.select_state')}} </label>
                            {!! Form::select('division_id', $divisions, null,['id'=>'division_id','class'=>'form-control select2','placeholder'=>trans('common.select_state')]); !!}
                        </div>

                        <div class="col-md-3">
                            <label>  {{_('common.select_city')}} </label>
                            {!! Form::select('district_id', $districts, null,['id'=>'district_id','class'=>'form-control select2','placeholder'=>trans('common.select_city')]); !!}
                        </div>

                        {{-- <div class="col-md-3">
                            <label>  Select Upazilla </label>
                            {!! Form::select('upazilla_id', $upazillas, null,['id'=>'upazilla_id','class'=>'form-control select2','placeholder'=>'Select Upazilla']); !!}
                        </div>

                        <div class="col-md-3">
                            <label>  Select Union </label>
                            {!! Form::select('union_id', $unions, null,['id'=>'union_id','class'=>'form-control select2','placeholder'=>'Select Union']); !!}
                        </div> --}}

                        <div class="col-md-3">
                            <label>  {{__("common.select_area")}} </label>
                            {!! Form::select('area_id', $areas, null,['id'=>'area_id','class'=>'form-control select2','placeholder'=>trans('common.select_area')]); !!}
                        </div>

                            @else
                        {{-- <div class="col-md-3">
                            <label>  Select Manager </label>
                            {!! Form::select('manager_id', $managers, null,['id'=>'manager_id','class'=>'form-control select2','placeholder'=>'Select Manager']); !!}
                        </div> --}}
                        <div class="col-md-3">
                            <label>  {{__('common.product_name')}} </label>
                            {!! Form::select('item_id', $products, null,['id'=>'item_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                        </div>
                            @endif
                            <div class="col-md-3">
                                <label>  {{__('common.customer_name')}} </label>
                                {!! Form::select('customer_id', $customers, null,['id'=>'customer_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                            </div>

                        <div class="col-md-3">
                            <label> {{__('common.from_date')}} </label>
                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-3">
                            <label> {{__('common.to_date')}}</label>
                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                        </div>

                        <div class="col-md-3 margin-top-23">
                            <label></label>
                            <input class="btn btn-info" value="{{__('common.search')}}" type="submit"/>
                            <a href="{{ route(Route::current()->getName(), $search_type) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> {{__('common.reload')}}</a>

                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                {{--<div class="box-body">--}}

                {{----}}
                {{--</div>--}}

                {!! Form::close() !!}
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{!! $report_title !!}  </h3>

                    <a href="{{  $full_url }}type=print" class="btn btn-sm btn-primary pull-right" id="btn-print"> <i class="fa fa-print"></i> {{__("common.print")}} </a>
                    {{-- <a href="{{  $full_url }}type=download" class="btn btn-sm btn-success pull-right mx-3"> <i class="fa fa-download"></i> {{__('common.download')}} </a> --}}
                </div>

                <div class="box-body">

                    <div class="col-lg-12">
                        <table class="report-table table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th>#SL</th>
                                <th>{{__('common.date')}}</th>
                                <th>{{__('common.customer')}}</th>
                                <th class="text-left">{{__('common.sale_code')}}</th>
                                <th>{{__('common.sale_id')}}</th>
                                <th class="text-left">{{__('common.product_name')}}</th>
                                <th>{{__('common.unit')}}</th>
                                <th class="text-center">{{__('common.qty')}}</th>
                                {{-- <th class="text-center">Carton</th>
                                <th class="text-center">Free</th> --}}
                                <th class="text-right"> {{__("common.price")}}</th>
                                {{-- <th class="text-right">Trade Price</th> --}}
                                <th class="text-right">{{__('common.total_price')}}</th>
                                <th class="text-right">{{__("common.profit_price")}}</th>
                            </tr>
                            @php
                                $last_date = $profit =  0;
                                $sale_total_price = $total = $total_qty = 0;
                            @endphp
                            @foreach($sales as $key => $value)

                            @php

                if(config('settings.sale_profit_by_quotation'))
               {
                   $purchase_price = $value->quote_purchase_price;
               }else{
                   $purchase_price = $value->purchase_price;
               }

                                   $profit_per = ($value->qty*$value->price)-($value->qty*$purchase_price)-$value->discount;
                                   $total += $value->total_price;
                                   $profit += $profit_per;
                            @endphp

                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ db_date_month_year_format($value->sale->date) }}</td>
                                    <td class="text-left">{{ $value->sale->due > 0 ? $value->sale->customer ? $value->sale->customer->name : "" : "Cash" }}</td>
                                    <td class="text-left">{{ $value->sale->sale_code }}</td>
                                    <td>{{ $value->sale->id }}</td>
                                    <td class="text-left">{{ $value->item->item_name }}</td>
                                    <td>{{ $value->unit }}</td>
                                    <td class="text-center">{{ $value->qty }}</td>
                                    {{-- <td class="text-center">{{ $value->carton }}</td>
                                    <td class="text-center">{{ $value->free }}</td> --}}
                                    <td class="text-right">{{ create_money_format($value->price) }}</td>
                                     {{-- <td class="text-right">{{ create_money_format($value->trade_price) }}</td> --}}
                                    <td class="text-right">{{ create_money_format($value->total_price) }}</td>
                                    <td class="text-right">{{ create_money_format($profit_per) }}</td>
                                </tr>
                                @php
                                    $last_date = db_date_month_year_format($value->sale->date);
                                    $sale_total_price += $value->total_price;
                                    $total_qty += $value->qty;
                                @endphp

                                @if( $loop->last)
                                    <tr class=" margin-bottom-20">
                                        <th colspan="{{ $item ? 6 : 9 }}" class="text-right">{{__('common.total')}}</th>
                                        @if($item)<th colspan="3" class="text-right">{{ $total_qty." ".$value->unit }}</th>@endif
                                        <th class="text-right">{{ create_money_format($sale_total_price) }}</th>
                                        <th  class="text-right">{{ create_money_format($profit) }}</th>
                                    </tr>
                                    @php
                                        $sale_total_price = 0;
                                    @endphp
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-12 text-right">
                        {{ $sales->links() }}
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
        });
    </script>
@endpush
