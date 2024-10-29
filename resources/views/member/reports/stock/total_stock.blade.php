<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/30/2019
 * Time: 2:54 PM
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
        'name' => 'Stock Price Report',
    ],
];

$data['data'] = [
    'name' => 'Stock Price Report',
    'title'=> 'Stock Price Report',
    'heading' => trans('common.stock_price_report'),
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

            {!! Form::open(['route' => 'member.report.total_stocks','method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        @if(Auth::user()->hasRole(['super-admin', 'developer']))
                            <div class="col-md-3">
                                <label> {{__("common.select_company")}} </label>
                                {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>trans('common.select_company')]); !!}
                            </div>
                        @endif

                        <div class="col-md-3">
                            <label>  {{__('common.brand_name')}} </label>
                            {!! Form::select('brand_id', $brands, null,['id'=>'brand_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
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

                <div class="box-body">

                    <div class="col-lg-12">
                        <table class="table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th>#SL</th>
                                <th>{{__('common.product_code')}}</th>
                                <th>{{__(('common.product_name'))}}</th>
                                <th> {{__('common.stock')}}</th>
                                <th class="text-right">  {{__('common.price')}}</th>
                                <th class="text-right"> {{__("common.total_price")}}</th>
                            </tr>
                            @foreach($stocks as $key=>$value)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value->productCode }}</td>
                                    <td>{{ $value->item_name }}</td>
                                    <td>{{ $value->stock." ".$value->unit }}</td>
                                    <td class="text-right">{{ create_money_format(create_float_format($value->purchase_qty_price)) }}</td>
                                    <td class="text-right">{{ create_money_format(create_float_format($value->stock*$value->purchase_qty_price)) }}</td>

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



            var url = "{{ route('search.brand_items') }}";

            $(document).on('change', '#brand_id', function(e){
                e.preventDefault();

                var itemId = $(this).val();

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'item_id' : itemId
                };

                $.ajax({
                    type        : 'POST',
                    url         : url, // the url where we want to POST
                    data        : form_data,
                    dataType    : 'json',
                    encode      : true
                })

                // using the done promise callback
                    .done(function(data) {

                        if(data.status == "success")
                        {
                            var html = "<option value=''>{{__('common.select_procuct')}} </option>";

                            $.each( data.items, function( key, value ) {
                                html += "<option value='"+key+"'>"+value+"</option>";
                            });

                            $('#item_id').html(html);
                            $('#item_id').select2();
                        }else{
                            bootbox.alert("{{__('common.no_product_found')}}");
                        }
                    });
            });
        });
    </script>
@endpush
