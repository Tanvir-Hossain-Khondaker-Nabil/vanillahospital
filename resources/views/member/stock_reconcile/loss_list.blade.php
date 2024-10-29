<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/30/2019
 * Time: 2:54 PM
 */


 $route =  \Auth::user()->can(['member.report.daily_stocks']) ? route('member.report.daily_stocks') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

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


$types = [
    'damage' => trans('common.damage'),
    'loss' => trans('common.loss'),
    'expired' => trans('common.expired'),
]
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

            {!! Form::open(['route' => 'member.report.loss_reconcile_stocks','method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <label>  {{__('common.loss_type')}} </label>
                            {!! Form::select('loss_type', $types, null,['id'=>'fiscal_year_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                        </div>

                        <div class="col-md-3">
                            <label>  {{__("common.fiscal_year")}} </label>
                            {!! Form::select('fiscal_year_id', $fiscal_years, null,['id'=>'fiscal_year_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                        </div>

                        <div class="col-md-3">
                            <label>  {{__('common.product_name')}} </label>
                            {!! Form::select('item_id', $products, null,['id'=>'item_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
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

                @include('member.reports.print_title_btn')

                <div class="box-header with-border">
                    <h3 class="box-title">{{__('common.stock_loss_report')}}</h3>
                </div>

                <div class="box-body">

                    <div class="col-lg-12">
                        <table class="table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th>ID</th>
                                <th>{{__('common.product_code')}}</th>
                                <th>{{__('common.product_name')}}</th>
                                <th class="text-center">{{__("common.lost_qty")}}</th>
                                <th>{{__('common.date')}}</th>
                                <th class="text-center">{{__('common.loss_type')}} </th>
                                {{--<th>Fiscal Year</th>--}}
                            </tr>
                            @foreach($stocks as $key=>$value)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value->item->productCode }}</td>
                                    <td>{{ $value->item->item_name }}</td>
                                    <td class="text-center">{{ $value->qty }} {{ $value->item->unit }} </td>
                                    <td >{{ db_date_month_year_format($value->closing_date) }}</td>
                                    <td class="text-center">{{ ucfirst($value->loss_type) }}</td>
                                    {{--<td>{{ $value->fiscal_year->fiscal_year_details }}</td>--}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-12 text-right">
                        {{ $stocks->links() }}
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
