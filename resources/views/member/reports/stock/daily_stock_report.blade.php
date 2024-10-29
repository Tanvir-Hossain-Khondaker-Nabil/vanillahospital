<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/29/2019
 * Time: 12:24 PM
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
        'name' => 'Daily Stock Report',
    ],
];

$data['data'] = [
    'name' => 'Daily Stock Report',
    'title'=> 'Daily Stock Report',
    'heading' => trans('common.daily_stock_report'),
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

            {!! Form::open(['route' => 'member.report.daily_stocks','method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <label>  {{__('common.brand_name')}} </label>
                            {!! Form::select('brand_id', $brands, null,['id'=>'brand_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                        </div>
                        <div class="col-md-3">
                            <label>  {{__('common.product_name')}} </label>
                            {!! Form::select('item_id', $products, null,['id'=>'item_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                        </div>
                        <div class="col-md-3">
                            <label> {{__("common.from_date")}} </label>
                            <input class="form-control date" name="from_date"  autocomplete="off" value="{{ $from_date }}" />
                        </div>
                        <div class="col-md-3">
                            <label> {{__('common.to_date')}}</label>
                            <input class="form-control date" name="to_date" value="{{ $to_date }}" autocomplete="off"/>
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
                        <table class="table table-striped stock_table" id="dataTable">

                            <tbody>
                            <tr>
                                <th>#{{__('common.serial')}}</th>
                                <th>{{__('common.product_code')}}</th>
                                <th>{{__('common.product_name')}}</th>
                                <th>{{__('common.opening_stock')}}</th>
                                <th>{{__('common.purchase_qty')}}</th>
                                <th>{{__('common.purchase_return_qty')}}</th>
                                <th>{{__('common.sale_qty')}}</th>
                                <th>{{__('common.sale_return_qty')}}</th>
                                <th>{{__('common.damage_qty')}}</th>
                                <th>{{__('common.overflow_qty')}}</th>
                                <th>{{__('common.closing_stock')}}</th>
                                <th>{{__('common.unit')}}</th>
                                <th> {{__('common.date')}} </th>
                            </tr>
                            @foreach($stocks as $key=>$value)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value->product_code }}</td>
                                    <td>{{ $value->product_name }}</td>
                                    <td>{{ create_float_format($value->opening_stock) }}</td>
                                    <td>{{ create_float_format($value->purchase_qty)  }}</td>
                                    <td>{{ create_float_format($value->purchase_return_qty)  }}</td>
                                    <td>{{ create_float_format($value->sale_qty)  }}</td>
                                    <td>{{ create_float_format($value->sale_return_qty)  }}</td>
                                    <td>{{ create_float_format($value->loss_qty)  }}</td>
                                    <td>{{ create_float_format($value->stock_overflow_qty)  }}</td>
                                    <td>{{ create_float_format($value->opening_stock+$value->purchase_qty-$value->purchase_return_qty-$value->sale_qty+$value->sale_return_qty-$value->loss_qty+$value->stock_overflow_qty) }}</td>
                                    <td>{{ $value->item->unit }}</td>
                                    <td>{{ db_date_month_year_format($value->date) }}</td>
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
                            var html = "<option value=''>{{__('common.select_product')}} </option>";

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
