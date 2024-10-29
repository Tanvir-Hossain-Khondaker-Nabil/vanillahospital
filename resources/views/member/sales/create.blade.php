<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/22/2019
 * Time: 11:29 AM
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
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Create sale',
    'title'=> 'Create sale',
    'heading' => trans('sale.create_sale'),
];

?>
@extends('layouts.back-end.master', $data)


@push('styles')

    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">

@endpush

@section('contents')

<div class="sale-create-desktop box box-default">

    @include('common._alert')

    <div class="box-header with-border">
        <h3 class="box-title">{{__('common.new_sale')}} </h3>
    </div>

    {!! Form::open(['route' => 'member.sales.store','method' => 'POST', 'files'=>true, 'id'=>'sale_form', 'role'=>'form' ]) !!}

    <div class="box-body">
        <div class="row">
            <div class="col-md-2 grid-width-20">
                <div class="form-group">
                    <label for="inputPassword" >{{__('common.order_date')}}</label>
                    {{--<div class="col-sm-8">--}}
                        <input type="text" id="date" autocomplete="off" class="form-control" name="date">
                    {{--</div>--}}
                </div>
            </div>

            <div class="col-md-2 grid-width-20">
                <div class="form-group ">
                    <label for="inputPassword" >{{__('common.customer_name')}}</label>
                    {{--<div class="col-sm-12">--}}
                        {!! Form::select('customer_id', $customers, null,['id'=>"customer_id", 'class'=>'form-control select2', 'placeholder'=>trans('common.select_customer')]); !!} </br>
                        <button class="btn btn-primary btn-xs" type="button" data-toggle="modal" data-target="#addCustomer" id="create_cus"> <i class="fa fa-plus-circle"></i> {{__('common.add_customer')}} </button>
                    {{--</div>--}}
                </div>
            </div>

            <div class="col-md-2 grid-width-20">
                <div class="form-group ">
                    <label for="inputPassword" >{{__('sale.last_credit_amount')}}</label>
                    {{--<div class="col-sm-8">--}}
                        <input type="text" class="form-control" id="last_credit" value="" readonly />
                    {{--</div>--}}
                </div>
                <!-- /.form-group -->
            </div>

            <div class="col-md-2 grid-width-20">
                <div class="form-group ">
                    <label for="inputPassword" >{{__('sale.membership_card_no')}}</label>
                    {{--<div class="col-sm-8">--}}
                        <input type="text" class="form-control" id="inputPassword" name="membership_card" placeholder="">
                </div>
            </div>


            <div class="col-md-2 grid-width-20">
                <div class="form-group ">
                    <label for="inputPassword" >{{__('sale.product_barcode_search')}}</label>
                    {{--<div class="col-sm-9">--}}
                    <input type="text" id="barcode_search" class="form-control" name="barcode_search">
                    {{--</div>--}}
                </div>
            </div>
        </div>

            <div class="col-md-12">
                <div class="form-group" id="customer_details">

                </div>
            </div>

            {{--<div class="col-md-5 col-form-label">--}}
                {{--<button type="button" class="btn btn-primary">Product Search</button>--}}
            {{--</div>--}}
        </div>
        <!-- /.row -->
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12  new-table-responsive text-center">
                <h4>{{__('sale.sales_order_item')}}</h4>

                <table class="sales_table" id="items">


                    <thead>
                    <tr>
                        <th>{{__('common.product')}}</th>
                        <th>{{__('common.serial')}}</th>
                        <th>{{__('common.unit')}}</th>
                        <th>{{__('common.available_stock')}}</th>
                        <th>{{__('common.qty')}}</th>
                        <th>{{__('common.price_per_qty')}}</th>
                        <th> {{__('common.price')}}</th>
                        <th>{{__('common.discount')}}</th>
                        <th> {{__('common.total_price')}}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="item-row">
                        <td class="item-name">
                            {!! Form::select('product_id[]', $products, null,['id'=>'product_id_0', 'data-option'=>'0', 'class'=>'form-control select2 item-name','required', 'placeholder'=>trans('common.select_item_name')]); !!}
                        </td>
                        <td class="description">
                            {!! Form::text('product_info[]', null,[ 'class'=>'form-control check_serial']); !!}

                        </td>
                        <td>
                            {!! Form::text('unit[]',null,['id'=>'unit_0','class'=>'form-control', 'disabled']); !!}
                        </td>
                        <td>{!! Form::text('available_stock[]',null,['id'=>'stock_0','class'=>'form-control', 'readonly']); !!}</td>
                        <td>{!! Form::number('qty[]',null,['id'=>'qty_0','class'=>'form-control input-number qty',  'step'=>"any", 'required']); !!}</td>
                        <td>{!! Form::number('price[]',null,['id'=>'price_0','class'=>'form-control input-number price',  'step'=>"any", 'required']); !!}</td>
                        <td>{!! Form::number('total_price[]',null,['id'=>'total_price_0','class'=>'form-control total_price', 'step'=>"any", 'readonly' ]); !!}</td>
                        <td>{!! Form::number('per_discount[]',null,['id'=>'discount_0','class'=>'form-control input-number discount']); !!}</td>
                        <td>{!! Form::number('total_price_discount[]',null,['id'=>'total_price_discount_0','class'=>'form-control total_price_discount', 'step'=>"any", 'readonly' ]); !!}</td>
                        <td  class="text-center"> <a class="btn btn-success add-row pull-right margin-top-3" href="#" ><i class="fa fa-plus-circle"></i> </a></td>
                    </tr>
                </tbody>
            </table>

        </div>

    </div>

        <div style="margin-top: 20px; " class="row">

            <div  class="col-lg-6 col-md-6 col-sm-12 col-sx-12 payment-info">
                <table style="width: 100%" class="sales_table_2">

                    <tr>
                        <td  class="total-line ">{{__('common.payment_option')}} </td>
                        <td  class="total-value">
                            <input type="checkbox" id="payment_option"  name="payment_option" value="1">   {{__('common.due_credit')}}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line ">{{__('common.account_name')}} </td>
                        <td  class="total-value">
                            {!! Form::select('cash_or_bank_id', $banks, config('settings.cash_bank_id'),['class'=>'form-control select2 ','required']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td   class="total-line ">{{__('common.memo_no')}} / P.O. </td>
                        <td  class="total-value">
                            {!! Form::text('memo_no',null,['id'=>'memo_no','class'=>'form-control']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line ">{{__('common.chalan_no')}} </td>
                        <td  class="total-value">
                            {!! Form::text('chalan_no',null,['id'=>'chalan_no','class'=>'form-control']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line ">{{__('common.vehicle_number')}} </td>
                        <td  class="total-value">
                            {!! Form::text('vehicle_number',null,['id'=>'vehicle_number','class'=>'form-control']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td   class="total-line ">{{__('common.payment_method')}} </td>
                        <td  class="total-value">
                            {!! Form::select('payment_method_id', $payment_methods, null,['class'=>'form-control select2 ','required']); !!}
                        </td>
                    </tr>
                    <tr class="hidden">

                        <td class="total-line ">{{__('common.delivery_system')}} </td>
                        <td  class="total-value">
                            {!! Form::select('delivery_type_id', $delivery_types, null,['class'=>'form-control select2 ','required']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line " colspan="2">
                            <label> {{__('common.shopping_bags')}}</label><br/>
                            @foreach($bags as $value)
                                <div class="col-md-4" style="padding-left: 5px; padding-right: 5px;"><label id="bags_{{$value->id}}"> {{ $value->item_name }}</label>
                                    {!! Form::number("shopping_bags_".$value->id,null,['class'=>'form-control bags_qty','data-option'=>$value->id]); !!}</div>
                                @endforeach

                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line">{{__('common.notation')}} </td>
                        <td  class="total-value">
                            {!! Form::text('notation',null,['id'=>'notation','class'=>'form-control']); !!}
                        </td>
                    </tr>
                </table>

            </div>
            <div  class="col-lg-6 col-md-6 col-sm-12 col-sx-12 float-right amount-info">
                <table style="width: 100%" class="sales_table_2">
                    <tr>
                        <td  class="total-line">{{__('common.sub_total')}}</td>
                        <td  class="total-value text-right">
                            {!! Form::number('sub_total',0,['id'=>'sub_total','class'=>'form-control input-number',  'step'=>"any" , 'readonly']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line">{{__('common.transport_cost')}}</td>
                        <td  class="total-value text-right">
                            {!! Form::number('transport_cost',0,['id'=>'transport_cost','class'=>'form-control input-number',  'step'=>"any"]); !!}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line">{{__('common.labor_cost')}}</td>
                        <td  class="total-value text-right">
                            {!! Form::number('labor_cost',0,['id'=>'labor_cost','class'=>'form-control input-number',  'step'=>"any"]); !!}
                        </td>
                    </tr>
                    {{--<tr>--}}
                    {{--<td class="total-line">Discount Type</td>--}}
                    {{--<td  class="total-value text-right">--}}
                    {{--{!! Form::select('discount_type', ['fixed'=>'Fixed', 'percentage'=>'Percentage'], null,['id'=>'discount_type','class'=>'form-control',  'step'=>"any"]); !!}--}}
                    {{--</td>--}}
                    {{--</tr>--}}
                    <tr>
                        <td class="total-line">{{__('common.discount')}}</td>
                        <td  class="total-value text-right">
                            {!! Form::number('discount',0,['id'=>'discount', 'step'=>"any", 'class'=>'form-control input-number']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line">{{__('common.total_amount')}}</td>
                        <td  class="total-value text-right">
                            {!! Form::number('total_amount',0,['id'=>'total_amount','class'=>'form-control input-number', 'step'=>"any", 'readonly']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line">{{__('common.amount_to_pay')}}</td>
                        <td  class="total-value text-right">
                            {!! Form::number('amount_to_pay',0,['id'=>'amount_to_pay','class'=>'form-control input-number', 'step'=>"any", 'readonly']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line">{{__('common.paid_amount')}}</td>
                        <td  class="total-value text-right">
                            {!! Form::number('paid_amount',null,['id'=>'paid_amount','class'=>'form-control input-number',  'step'=>"any",'required']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line">{{__('common.due_amount')}}</td>
                        <td  class="total-value text-right">
                            {!! Form::number('due',null,['id'=>'due_amount','class'=>'form-control input-number',  'step'=>"any", 'readonly']); !!}
                        </td>
                    </tr>

                </table>

            </div>


            <div class="col-lg-12 col-md-12 "  style="max-width: 100%; flex: 100%;">
<table class="new-table-3 pull-right" style="margin-top: 20px; margin-bottom: 20px" >
    <tr>
        <td>
            <button style="width: 100px" type="reset" class="btn btn-block btn-warning">{{__('common.cancel')}}</button>
        </td>
        <td>
            <button style="width: 100px" type="submit" class="btn btn-block btn-primary">{{__('common.save')}}</button>
        </td>
    </tr>
</table>
            </div>

        </div>



    {!! Form::close() !!}


    </div>



@include('member.sales._model_add_customer')

@push('scripts')
    <script type="text/javascript">

        var editItem = '';
    </script>
    @include('member.sales.scripts')
@endpush


@endsection

