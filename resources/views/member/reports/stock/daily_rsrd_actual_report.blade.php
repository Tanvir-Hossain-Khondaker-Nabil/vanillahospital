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
        'name' => 'Daily RSRD Report',
    ],
];

$data['data'] = [
    'name' => 'Daily RSRD Report',
    'title'=> 'Daily RSRD Report',
    'heading' => 'Daily RSRD(Actual Sale) Report: '.$from_date." to ".$to_date,
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

            {!! Form::open(['route' => 'member.report.daily_stock_by_rsrd','method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <label>  Brand Name </label>
                            {!! Form::select('brand_id', $brands, null,['id'=>'brand_id','class'=>'form-control select2','placeholder'=>'Select All']); !!}
                        </div>
                        <div class="col-md-3">
                            <label>  Product Name </label>
                            {!! Form::select('item_id', $products, null,['id'=>'item_id','class'=>'form-control select2','placeholder'=>'Select All']); !!}
                        </div>
                        <div class="col-md-2">
                            <label> From Date </label>
                            <input class="form-control date" name="from_date"  autocomplete="off" value="{{ $from_date }}" />
                        </div>
                        <div class="col-md-2">
                            <label> To Date</label>
                            <input class="form-control date" name="to_date" value="{{ $to_date }}" autocomplete="off"/>
                        </div>
                        <div class="col-md-2 margin-top-23">
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
                        <table class="table table-striped " id="dataTable">

                            <tbody>
                            <tr>
                                <th>#SL</th>
                                <th width="350px;">Product Name</th>
                                <th class="text-center">Unit</th>
                                <th class="text-center">Requisition Qty</th>
                                <th class="text-center">Sale</th>
                                <th class="text-center">Req. Return </th>
                                <th class="text-center">Return </th>
                                <th class="text-center">Damage</th>
                                {{--<th class="text-center">Closing Stock</th>--}}
                                <th class="text-right">Qty/Price</th>
                                <th class="text-right">Price</th>
                                <th class="text-center">Date </th>
                            </tr>

                            @php
                                $total_damage = 0;
                                $total_damage_price = 0;
                                $total = 0;
                                $i = 1;
                            @endphp


                            @foreach($stocks as $key=>$value)
                                @php

                    $req_return_qty = $value['sales_requisition_qty']-$value['sale_qty'];                     $req_return_qty = $req_return_qty < 0 ? 0 : $req_return_qty;
                    $return_qty = $value['sale_return_qty'];
                    $close_qty = $return_qty>0? $return_qty-$value['damage_qty']: 0;

                    $total_damage += $value['damage_qty'];
                    $total_damage_price += $value['damage_qty']*$value['price'];
                    $sale_total_price = ($value['sale_qty']*$value['price']);
                    $total += ($value['sale_qty']*$value['price']);
                    $date = $value['date'];


                @endphp
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>{{ $value['item_name'] }}</td>
                    <td class="text-center">{{ $value['unit'] }}</td>
                    <td class="text-center">{{ create_float_format($value['sales_requisition_qty'])  }}</td>
                    <td class="text-center">{{ create_float_format($value['sale_qty'])  }}</td>
                    <td class="text-center">{{ create_float_format($req_return_qty)  }}</td>
                    <td class="text-center">{{ create_float_format($return_qty)  }}</td>

                    <td class="text-center">{{ create_float_format($value['damage_qty'])  }}</td>
                    {{--<td class="text-center">{{ create_float_format($close_qty) }}</td>--}}
                    <td class="text-right">{{ create_money_format($value['price']) }}</td>
                    <td class="text-right">{{ create_money_format($sale_total_price) }}</td>
                    <td class="text-center">{{ db_date_month_year_format($date) }}</td>
                </tr>
            @endforeach

                            <?php
                            if(count($stocks)==0){
                            ?>

                            <tr>
                                <td class="text-left" colspan="11">No Report Data Available</td>
                            </tr>

                            <?php }else{
                            ?>
                            <tr>
                                <th class="border-top-1 text-right padding-right-50" colspan="9"> Total</th>
                                <th class="border-top-1 text-right">{{ create_money_format($total) }}</th>
                                <td class="border-top-1 text-center"></td>
                            </tr>
                            <tr>
                                <th class="border-top-1 text-right padding-right-50" colspan="9"> Commission</th>
                                <td class="border-top-1 text-right">{{ create_money_format($sales_commission) }}</td>
                                <td class="border-top-1 text-center"></td>
                            </tr>
                            <tr>
                                <th class="border-top-1 text-right padding-right-50" colspan="9"> Damage</th>
                                <th class="border-top-1 text-right">{{ create_money_format($total_damage_price) }}</th>
                                <td class="border-top-1 text-center"></td>
                            </tr>
                            <tr>
                                <th class="border-top-1 text-right padding-right-50" colspan="9"> Grand Total</th>
                                <td class="border-top-1 text-right">{{ create_money_format($total-$sales_commission-$total_damage_price) }}</td>
                                <td class="border-top-1 text-center"></td>
                            </tr>
                            <?php
                            } ?>

                            </tbody>
                        </table>
                    </div>

                    {{--<div class="col-lg-12 col-sm-12 col-md-12 text-right">--}}
                        {{--{{ $stocks->links() }}--}}
                    {{--</div>--}}
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
