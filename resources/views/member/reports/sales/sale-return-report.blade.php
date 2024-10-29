<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/22/2019
 * Time: 11:55 AM
 */


//  $route = \Auth::user()->can(['member.report.list']) ? route('member.report.list') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Reports',
        'href' => "#",
    ],
    [
        'name' => 'Sale Return Report',
    ],
];

$data['data'] = [
    'name' => 'Sale Return Report',
    'title'=> 'Sale Return Report',
    'heading' => trans('common.sale_return_report'),
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

            {!! Form::open(['route' => 'member.report.sale_return_report','method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        @if(Auth::user()->hasRole(['super-admin', 'developer']))
                            <div class="col-md-3">
                                <label>  {{__('common.select_company')}} </label>
                                {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>trans('common.select_company')]); !!}
                            </div>
                        @endif
                        <div class="col-md-3">
                            <label>  {{__('common.customer_name')}} </label>
                            {!! Form::select('customer_id', $customers, null,['id'=>'customer_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                        </div>

                        <div class="col-md-3">
                            <label>  {{__('common.product')}} </label>
                            {!! Form::select('item_id', $products, null,['id'=>'item_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
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
                            <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> {{__('common.reload')}}</a>

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
                    <h3 class="box-title"> {{__('common.sale_return_report')}}</h3>
                    <a class="btn btn-sm  btn-primary pull-right" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=print" id="btn-print"> <i class="fa fa-print"></i> {{__('common.print')}} </a>
                    {{-- <a class="btn btn-sm btn-success pull-right" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=download" > <i class="fa fa-download"></i> {{__('common.download')}} </a> --}}
                </div>

                <div class="box-body">

                    <div class="col-lg-12">
                        <table class="report-table table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th>#SL</th>
                                <th class="width-100">{{__("common.return_date")}}</th>
                                <th class="width-100">{{__('common.sale_date')}}</th>
                                <th class="text-left">{{__('common.return_code')}}</th>
                                <th>{{__('common.sale_id')}}</th>
                                <th class="text-left">{{__('common.customer_name')}}</th>
                                <th class="text-left">{{__('common.product_name')}}</th>
                                <th>{{__('common.unit')}}</th>
                                <th class="text-center">{{__('common.quantity')}}</th>
                                <th class="text-right">{{__('common.price')}}</th>
                                <th class="text-right">{{__('common.total_price')}}</th>
                            </tr>
                            @php
                                $last_date = 0;
                                $sale_total_price = $total_qty = 0;
                                $product_name = $last_unit = '';
                            @endphp
                            @foreach($sales as $key => $value)
                                @php
                                    $saledate = db_date_month_year_format($value->sales->date);
                                @endphp
                                @if( !$loop->first && (($last_date!=0 &&  $last_date != $saledate ) ||  $product_name!=$value->item->item_name) && $total_qty>0)
                                    <tr class=" margin-bottom-20">
                                        <th colspan="8" class="text-right">Total</th>
                                        <th colspan="2" class="text-right">{{ $total_qty." ".$last_unit }}</th>
                                        <th colspan="2" class="text-right">{{ create_money_format($sale_total_price) }}</th>
                                    </tr>
                                    @php
                                        $sale_total_price = $total_qty = 0;
                                    @endphp
                                @elseif( !$loop->first && ($last_date!=0 && $last_date != $saledate &&  $product_name==$value->item->item_name))
                                    <tr class=" margin-bottom-20">
                                        <th colspan="8" class="text-right">{{__('common.total')}}</th>
                                        <th colspan="2" class="text-right">{{ $total_qty." ".$last_unit }}</th>
                                        <th colspan="2" class="text-right">{{ create_money_format($sale_total_price) }}</th>
                                    </tr>
                                    @php
                                        $sale_total_price = $total_qty = 0;
                                    @endphp
                                @endif
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ db_date_month_year_format($value->return_date) }}</td>
                                    <td>{{ db_date_month_year_format($value->sales->date) }}</td>
                                    <td class="text-left">{{  $value->return_code}}</td>
                                    <td>{{  $value->sale_id }}</td>
                                    <td class="text-left">{{ $value->sales->due > 0 ? $value->sales->customer ? $value->sales->customer->name : "" : "Cash" }}</td>

                                    <td class="text-left">{{ $value->item->item_name }}</td>
                                    <td>{{ $value->unit }}</td>
                                    <td class="text-right padding-right-20">{{ $value->return_qty }}</td>
                                    <td class="text-right">{{ create_money_format($value->return_price) }}</td>
                                    <td class="text-right">{{ create_money_format($value->return_qty*$value->return_price) }}</td>
                                </tr>
                                @php
                                    $last_date = db_date_month_year_format($value->sales->date);
                                    $sale_total_price += $value->return_qty*$value->return_price;
                                    $product_name = $value->item->item_name;
                                    $total_qty += $value->return_qty;
                                    $last_unit = $value->unit;
                                @endphp

                                @if( $loop->last)
                                    <tr class=" margin-bottom-20">
                                        <th colspan="8" class="text-right">{{__('common.total')}}</th>
                                        <th colspan="2" class="text-right">{{ $total_qty." ".$last_unit }}</th>
                                        <th colspan="2" class="text-right">{{ create_money_format($sale_total_price) }}</th>
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

