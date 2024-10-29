<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/30/2019
 * Time: 2:54 PM
 */


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => route('member.dashboard'),
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Warehouse Stocks',
        'href' => route('member.report.daily_stocks'),
    ],
    [
        'name' => 'Warehouse Stock Price Report',
    ],
];

$data['data'] = [
    'name' => 'Warehouse Stock Price Report',
    'title'=> 'Warehouse Stock Price Report',
    'heading' => 'Warehouse Stock Price Report',
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
                    <h3 class="box-title">Search</h3>
                </div>

            {!! Form::open(['route' => 'member.report.warehouse_total_stocks','method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        @if(Auth::user()->hasRole(['super-admin', 'developer']))
                            <div class="col-md-3">
                                <label>  Select Company </label>
                                {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>'Select Company']); !!}
                            </div>
                        @endif

                        <div class="col-md-3">
                            <label>  Warehouse Name </label>
                            {!! Form::select('warehouse_id', $warehouses, null,['id'=>'warehouse_id','class'=>'form-control select2','placeholder'=>'Select All']); !!}
                        </div>

                        <div class="col-md-3">
                            <label>  Brand Name </label>
                            {!! Form::select('brand_id', $brands, null,['id'=>'brand_id','class'=>'form-control select2','placeholder'=>'Select All']); !!}
                        </div>
                        <div class="col-md-3">
                            <label>  Product Name </label>
                            {!! Form::select('item_id', $products, null,['id'=>'item_id','class'=>'form-control select2','placeholder'=>'Select All']); !!}
                        </div>

                        <div class="col-md-3">
                            <label>  Warehouse Group Report </label>
                            {!! Form::select('group_by', [1=>"Yes", 0=>"No"], 1,['id'=>'group_by','class'=>'form-control','placeholder'=>'Select All']); !!}
                        </div>


                        <div class="col-md-3 margin-top-23">
                            <label></label>
                            <input class="btn btn-info" value="Search" type="submit"/>
                            <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Reload</a>

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
                                <th> Warehouse </th>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th class="text-center">  Quantity</th>
                                <th class="text-right">  Price</th>
                                <th class="text-right"> Total Price</th>
                            </tr>
                            @foreach($stocks as $key=>$value)


                                @php
                                    $purchase_price = $value->purchase_qty_price ?? $value->purchase_price;
                                @endphp

                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value->title }}</td>
                                    <td>{{ $value->productCode }}</td>
                                    <td>{{ $value->item_name }}</td>
                                    <td class="text-center">{{ $value->stock." ".$value->unit }}</td>
                                    <td class="text-right">{{ create_money_format(create_float_format($purchase_price)) }}</td>
                                    <td class="text-right">{{ create_money_format(create_float_format($value->stock*$purchase_price)) }}</td>

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
                            var html = "<option value=''>Select Product </option>";

                            $.each( data.items, function( key, value ) {
                                html += "<option value='"+key+"'>"+value+"</option>";
                            });

                            $('#item_id').html(html);
                            $('#item_id').select2();
                        }else{
                            bootbox.alert("No Product Found!! ");
                        }
                    });
            });
        });
    </script>
@endpush
