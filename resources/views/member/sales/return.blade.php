<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/27/2019
 * Time: 2:52 PM
 */


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => route('member.dashboard'),
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'sales',
        'href' => route('member.sales.index'),
    ],
    [
        'name' => 'Return',
    ],
];

$data['data'] = [
    'name' => 'Return sales',
    'title'=> 'Return sales',
    'heading' => trans('sale.return_sale'),
];

?>
@extends('layouts.back-end.master', $data)


@push('styles')

    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">

@endpush

@section('contents')

    <div class="box box-default">

        @include('common._alert')

        <div class="box-header with-border">
            <h3 class="box-title">{{__('sale.return_sales_order')}}</h3>
        </div>

        {!! Form::model($model, ['route' => ['member.sales.sales_return_update', $model],  'method' => 'put', 'role'=>'form', 'files'=>true]) !!}

        <div class="box-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-4 col-form-label">{{__('common.order_date')}}</label>
                        <div class="col-sm-8">
                            {!! Form::text('date',month_date_year_format($model->date),['id'=>'date','class'=>'form-control']); !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-4 col-form-label">{{__("common.customer_name")}}</label>
                        <div class="col-sm-8">
                            {!! Form::select('customer_id', $customers, null,['id'=>"customer_id", 'class'=>'form-control select2', 'placeholder'=>trans('common.select_customer')]); !!} </br>
                            {{--<button class="btn btn-primary btn-xs" type="button" data-toggle="modal" data-target="#addCustomer"> <i class="fa fa-plus-circle"></i> Add Customer </button>--}}
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-4 col-form-label">{{__('sale.last_credit_amount')}}</label>
                        <div class="col-sm-8">
                            {!! Form::text('last_credit',null,['id'=> 'last_credit','class'=>'form-control','readonly']); !!}
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>

                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-4 col-form-label">{{__('sale.membership_card_no')}}</label>
                        <div class="col-sm-8">
                            {!! Form::text('membership_card',null,['class'=>'form-control']); !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group" id="customer_details">

                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12  new-table-responsive text-center">
                    <h4>{{__('sale.sales_order_item')}}</h4>

                    <table class="sales_table" id="items">


                        <thead>
                        <tr>
                            <th>{{__('common.item_name')}}</th>
                            <th>{{__('common.description')}}</th>
                            <th>{{__('common.available_stock')}}</th>
                            <th>{{__('sale.last_sales_qty')}}</th>
                            <th>{{__('common.unit')}}</th>
                            <th>{{__('common.sale_qty')}}</th>
                            <th>{{__('common.sale_price')}}</th>
                            <th>{{__('common.sale_total_price')}}</th>
                            <th>{{__('common.return_qty')}}</th>
                            <th>{{__('common.return_price')}}</th>
                            <th>{{__('common.return_total_price')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($model->sale_details as $sale)
                            <tr class="item-row">
                                <td class="item-name">
                                    <input type="hidden" value="{{ $sale->id }}" name="sale_details_id[]">
                                    {!! Form::select('product_id[]', $products, $sale->item_id,['id'=>'product_id_0', 'data-option'=>'0', 'class'=>'form-control select2 item-name','required', 'placeholder'=>trans('common.select_item_name'), "readonly"]); !!}
                                </td>
                                <td class="description" width="100px">
                                    {!! Form::text('description[]',$sale->description,['class'=>'form-control']); !!}
                                </td>
                                <td>{!! Form::text('available_stock[]',$sale->available_stock,['id'=>'stock_0','class'=>'form-control', 'readonly']); !!}</td>
                                <td>{!! Form::number('last_sale_qty[]',$sale->qty,['id'=>'last_sale_qty_0','class'=>'form-control', 'readonly']); !!}</td>
                                <td>
                                    {!! Form::text('unit[]',$sale->unit,['id'=>'unit_0','class'=>'form-control', 'disabled']); !!}
                                </td>
                                <td>{!! Form::number('qty[]',$sale->qty,['id'=>'qty_0','class'=>'form-control input-number qty', 'required', "readonly"]); !!}</td>
                                <td>{!! Form::number('price[]',$sale->price,['id'=>'price_0','class'=>'form-control input-number price', 'readonly', 'required']); !!}</td>
                                <td>{!! Form::number('total_price[]',$sale->price*$sale->qty,['id'=>'total_price_0','class'=>'form-control total_price', 'readonly']); !!}</td>
                                <td>{!! Form::number('return_qty[]',null,['id'=>'return_qty_0','class'=>'form-control input-number qty', 'step'=>"any"]); !!}</td>
                                <td>{!! Form::number('return_price[]',null,['id'=>'return_price_0','class'=>'form-control input-number price', 'step'=>"any"]); !!}</td>
                                <td>{!! Form::number('return_total_price[]', null,['id'=>'return_total_price_0','class'=>'form-control total_price', 'readonly']); !!}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>

            </div>

            <div style="margin-top: 20px; " class="row">
                <div  style="margin-bottom: 10px" class="col-lg-6 col-md-6 col-sm-12 col-sx-12 float-right amount-info">
                    <table style="width: 100%" class="sales_table_2">
                        <tr>
                            <td  class="total-line">{{__('common.amount_to_pay')}}</td>
                            <td  class="total-value text-right">
                                {!! Form::number('amount_to_pay',null,['id'=>'amount_to_pay','class'=>'form-control input-number', 'readonly']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">{{__('common.paid_amount')}}</td>
                            <td  class="total-value text-right">
                                {!! Form::number('paid_amount',null,['id'=>'paid_amount','class'=>'form-control input-number', 'required', 'readonly']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">{{__('common.due_amount')}}</td>
                            <td  class="total-value text-right">
                                {!! Form::number('due',null,['id'=>'due_amount','class'=>'form-control input-number', 'readonly']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">{{__('common.return_amount')}}</td>
                            <td  class="total-value text-right">
                                {!! Form::number('return_amount',null,['id'=>'return_amount','class'=>'form-control input-number text-bold', 'required', 'readonly']); !!}
                            </td>
                        </tr>

                    </table>

                </div>
                <div  class="col-lg-6 col-md-6 col-sm-12 col-sx-12 float-right payment-info">
                    <table style="width: 100%" class="sales_table_2">

                        <tr>
                            <td  class="total-line ">{{__('common.account_name')}} </td>
                            <td  class="total-value">
                                {!! Form::select('account_type_id', $banks, null,['class'=>'form-control select2 ','required']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">{{__('common.comment')}} </td>
                            <td  class="total-value">
                                {!! Form::text('notation',null,['id'=>'notation','class'=>'form-control']); !!}
                            </td>
                        </tr>
                    </table>

                </div>

            </div>


            <div style="margin-top: 20px; margin-bottom: 20px" class="row pull-right">

                <div class="col-lg-12 col-md-12 ">
                    <table class="new-table-3">
                        <tr>
                            <td>
                                <button style="width: 100px" type="submit" class="btn btn-block btn-primary">{{__('common.save_return')}}</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


            {!! Form::close() !!}


        </div>
    </div>

    @push('scripts')

        <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
        <script type="text/javascript">

            $(function () {
                $('#date').datepicker({
                    "setDate": new Date(),
                    "format": 'mm/dd/yyyy',
                    "endDate": "+0d",
                    "todayHighlight": true,
                    "autoclose": true
                });
                var today = moment().format('MM\DD\YYYY');
                $('#date').datepicker('setDate', today);
                $('#date').datepicker('update');
                $('.date').datepicker('setDate', today);

                $('.select2').select2();

            });
            $(document).on('keyup','.qty, .price', function(e) {
                e.preventDefault();
                price_calculate();
            });

            $(document).on('keyup','.qty', function(e) {
                e.preventDefault();

                var $div = $(this).parent().parent();
                var stock = $div.find('td:nth-child(6)').find('input').val();
                if(parseFloat(stock)<parseFloat($(this).val()))
                {
                    $(this).val('');
                    bootbox.alert("{{__('common.sales_return_quantity_cant_cross_sale_qty')}}");
                    return false;
                }
            });

            $(document).on('keyup','.price', function(e) {
                e.preventDefault();

                var $div = $(this).parent().parent();
                var price = $div.find('td:nth-child(7)').find('input').val();
                if(parseFloat(price)<parseFloat($(this).val()))
                {
                    $(this).val('');
                    $div.find('td:nth-child(11)').find('input').val('');
                    bootbox.alert("{{__('common.sales_return_price_cant_cross_sale_price')}}");
                    return false;
                }
            });

            function price_calculate(){

                var $tr = $('.sales_table tbody');
                var total_bill = 0;
                for(var i = 1; i<=$tr.find('tr').length; i++) {
                    var qty = $tr.find('tr:nth-child('+i+') td:nth-child(9) input').val();
                    var price = $tr.find('tr:nth-child('+i+') td:nth-child(10) input').val();
                    qty =  qty == undefined || qty == "" ? 0 : parseInt(qty);
                    price =  price == undefined || price == "" ? 0 : parseFloat(price);
                    var total_price = parseFloat(qty*price);
                    total_bill = total_bill+total_price;
                    $tr.find('tr:nth-child('+i+') td:nth-child(11) input').val(total_price);
                }
                $('#return_amount').val(total_bill);
            }

        </script>
    @endpush


@endsection
