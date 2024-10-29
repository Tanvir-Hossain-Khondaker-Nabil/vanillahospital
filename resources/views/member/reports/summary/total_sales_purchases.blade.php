<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/4/2021
 * Time: 4:43 PM
 */

 $route = \Auth::user()->can(['member.report.total-sales-purchases']) ? route('member.report.total-sales-purchases') : '#';
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
        'name' => $report_title,
    ],
];

$data['data'] = [
    'name' => $report_title,
    'title'=> $report_title,
    'heading' => $report_title,
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

            {!! Form::open(['route' => 'member.report.total-sales-purchases','method' => 'GET', 'role'=>'form' ]) !!}
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
                    <h3 class="box-title">{{ $report_title }}</h3>

                    @include('member.reports.print_title_btn')
                </div>

                <div class="box-body">

                    <div class="col-lg-12">
                        <h4> {{__('common.product_sales_report')}}</h4>
                        <table class="table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th>#SL</th>
                                <th>{{__('common.product_name')}}</th>
                                <th class="text-right" style="padding-right: 30px;">{{__('common.qty')}}</th>
                                <th class="text-right">{{__('common.total_price')}}</th>
                            </tr>
                            @php
                                $total_price = 0;
                            @endphp
                            @foreach($sale_details as $key => $value)
                                <tr>
                                    <td> {{ $key+1 }}</td>
                                    <td>{{ $value->item->item_name }}</td>
                                    <td class="text-right">{{ $value->total_qty." ".$value->unit }}</td>
                                    <td class="text-right">{{ create_money_format($value->sum_total_price) }}</td>
                                </tr>
                                @php
                                    $total_price += $value->sum_total_price
                                @endphp

                                @if( $loop->last)
                                    <tr>
                                        <th colspan="3" class="text-right"> {{__('common.total')}} </th>
                                        <th class="text-right"> {{ create_money_format($total_price) }}</th>
                                    </tr>
                                @endif

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>



                <div class="box-body">

                    <div class="col-lg-12">
                        <h4> {{__('common.product_purchases_report')}}</h4>
                        <table class="table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th>#SL</th>
                                <th>{{__('common.product_name')}}</th>
                                <th class="text-right" style="padding-right: 30px;">{{__('common.qty')}}</th>
                                <th class="text-right">{{__('common.total_price')}}</th>
                            </tr>

                            @php
                                $total_price = 0;
                            @endphp
                            @foreach($purchase_details as $key => $value)
                                <tr>
                                    <td> {{ $key+1 }}</td>
                                    <td>{{ $value->item->item_name }}</td>
                                    <td class="text-right">{{ $value->total_qty." ".$value->unit }}</td>
                                    <td class="text-right">{{ create_money_format($value->sum_total_price) }}</td>
                                </tr>
                                @php
                                    $total_price += $value->sum_total_price
                                @endphp

                                @if( $loop->last)
                                    <tr>
                                        <th colspan="3" class="text-right"> {{__('common.total')}} </th>
                                        <th  class="text-right"> {{ create_money_format($total_price) }}</th>
                                    </tr>
                                @endif
                            @endforeach
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
        });
    </script>
@endpush
