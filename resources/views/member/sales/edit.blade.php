<?php
/**
 * Editd by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/26/2019
 * Time: 2:35 PM
 */

$sub_total = $sub_discount = 0;
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
        'name' => 'Edit',
    ],
];

$data['data'] = [
    'name' => 'Edit sales',
    'title'=> 'Edit sales',
    'heading' => trans('sale.edit_sale'),
];

?>
@extends('layouts.back-end.master', $data)


@push('styles')

    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">

@endpush

@section('contents')

    <div class="sale-edit-desktop box box-default">

        @include('common._alert')

        <div class="box-header with-border">
            <h3 class="box-title">{{__('common.invoice_no')}}: {{'INV-'.$model->id}}</h3>
        </div>

        {!! Form::model($model, ['route' => ['member.sales.update', $model],  'method' => 'put', 'role'=>'form', 'files'=>true]) !!}

        <div class="box-body pb-0">
            <div class="row">

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="inputPassword" >{{__('common.order_date')}}</label>
                        {{--<div class="col-sm-8">--}}
                        {!! Form::text('date',create_date_format($model->date, '/'),['id'=>'date','class'=>'form-control']); !!}                    {{--</div>--}}
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group ">
                        <label for="inputPassword" >{{__('common.customer_name')}}</label>
                        {{--<div class="col-sm-8">--}}
                        {!! Form::select('customer_id', $customers, null,['id'=>"customer_id", 'class'=>'form-control select2', 'placeholder'=>'Select Customer']); !!} </br>
                        {{--                        <button class="btn btn-primary btn-xs" type="button" data-toggle="modal" data-target="#addCustomer"> <i class="fa fa-plus-circle"></i> Add Customer </button>--}}
                        {{--</div>--}}
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group ">
                        <label for="inputPassword" >{{__('sale.last_credit_amount')}}</label>
                        {{--<div class="col-sm-8">--}}
                        <input type="text" class="form-control" id="last_credit" value="" readonly />
                        {{--</div>--}}
                    </div>
                    <!-- /.form-group -->
                </div>

                <div class="col-md-2">
                    <div class="form-group ">
                        <label for="inputPassword" >{{__('sale.membership_card_no')}}</label>
                        {{--<div class="col-sm-8">--}}
                        <input type="text" class="form-control" id="inputPassword" name="membership_card" placeholder="">
                    </div>
                </div>


                {{--<div class="col-md-2 ">--}}
                    {{--<div class="form-group ">--}}
                        {{--<label for="inputPassword" >Product Barcode Search</label>--}}
                        {{--<div class="col-sm-9">--}}
                        {{--<input type="text" id="barcode_search" class="form-control" name="barcode_search">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                <div class="col-md-2 ">
                    <div class="form-group ">
                        <label for="inputPassword" > {{__('common.purchase_price')}}</label>
                        <input type="text" id="purchase_price" class="form-control" >
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
                        @foreach($model->sale_details as $sale)
                            <tr class="item-row">
                                <td class="item-name" width="200px">
                                    <input type="hidden" value="{{ $sale->id }}" name="sale_details_id[]">
                                    {!! Form::select('product_id[]', $products, $sale->item_id,['id'=>'product_id_0', 'data-option'=>'0', 'class'=>'form-control select2 item-name','required', 'placeholder'=>trans('common.select_item_name'), !$loop->last ? "readonly":'']); !!}
                                </td>
                                <td class="description">
                                    {!! Form::text('product_info[]', $sale->product_info,[ 'class'=>'form-control check_serial']); !!}

                                </td>
                                <td>
                                    {!! Form::text('unit[]',$sale->unit,['id'=>'unit_0','class'=>'form-control', 'disabled']); !!}
                                </td>
                                <td>{!! Form::text('available_stock[]',$sale->item->stock_details->stock,['id'=>'stock_0','class'=>'form-control', 'readonly']); !!}</td>
                                <td>{!! Form::number('qty[]',$sale->qty,['id'=>'qty_0','class'=>'form-control input-number qty',  'step'=>"any", 'required']); !!}</td>
                                <td>{!! Form::number('price[]',$sale->price,['id'=>'price_0','class'=>'form-control input-number price',  'step'=>"any", 'required']); !!}</td>
                                <td>{!! Form::number('total_price[]',$sale->total_price,['id'=>'total_price_0','class'=>'form-control total_price', 'step'=>"any", 'readonly']); !!}</td>
                                <td>{!! Form::number('per_discount[]',$sale->discount,['id'=>'discount_0','class'=>'form-control input-number discount']); !!}</td>
                                <td>{!! Form::number('total_price_discount[]',($sale->total_price-$sale->discount),['id'=>'total_price_discount_0','class'=>'form-control total_price_discount', 'step'=>"any", 'readonly' ]); !!}</td>
                                <td class="text-center">
                                    @if($loop->last)
                                        <a class="btn btn-success add-row pull-right margin-top-3" href="#" ><i class="fa fa-plus-circle"></i> </a>
                                    @else
                                        <a href="#" class="btn-text-info margin-top-3 edit-button"><i class="fa fa-pencil"></i></a><a href="#" class="btn-text-danger margin-top-3 delete-field"><i class="fa fa-close"></i></a>
                                    @endif
                                </td>
                            </tr>

                            @php
                                $sub_total += $sale->total_price-$sale->discount;
                                $sub_discount += $sale->discount;
                            @endphp
                        @endforeach
                        </tbody>
                    </table>

                </div>

            </div>

            <div style="margin-top: 20px; " class="row">

                <div  class="col-lg-6 col-md-6 col-sm-12 col-sx-12 float-right payment-info">
                    <table style="width: 100%" class="sales_table_2">

                        <tr>
                            <td  class="total-line ">{{__('common.account_name')}} </td>
                            <td  class="total-value">
                                {!! Form::select('cash_or_bank_id', $banks, null,['class'=>'form-control select2 ','required']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td   class="total-line ">{{__('common.memo_no')}}/ P.O. </td>
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
                        <tr>

                            <td class="total-line ">{{__('common.delivery_system')}} </td>
                            <td  class="total-value">
                                {!! Form::select('delivery_type_id', $delivery_types, null,['class'=>'form-control select2 ','required']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line " colspan="2">
                                <label> {{__('common.shopping_bags')}}</label><br/>
                                @foreach($bags as $value)
                                    <div class="col-md-3" style="padding-left: 5px; padding-right: 5px;"><label> {{ $value->item_name }}</label>
                                        @php
                                            $sale_track = $model->shopping_bags->pluck('bag_id')->toArray();
                                            $sale_bag = $model->shopping_bags->pluck('qty','bag_id')->toArray();
//                                       dd($sale_track);
                                        @endphp


                                        {{--@foreach($model->shopping_bags as $bag)--}}
                                        {{--@if($bag->bag_id == $value->item_id)--}}
                                        @if(in_array($value->item_id, $sale_track))
                                            {!! Form::number("shopping_bags_".$value->item_id, $sale_bag[$value->item_id],['class'=>'form-control']); !!}
                                        @else
                                            {!! Form::number("shopping_bags_".$value->item_id, null,['class'=>'form-control']); !!}
                                        @endif
                                        {{--@endforeach--}}
                                    </div>
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
                                {!! Form::number('sub_total',$sub_total,['id'=>'sub_total','class'=>'form-control input-number', 'readonly']); !!}
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
                        {{--{!! Form::select('discount_type', ['fixed'=>'Fixed', 'percentage'=>'Percentage'], null,['id'=>'discount_type','class'=>'form-control']); !!}--}}
                        {{--</td>--}}
                        {{--</tr>--}}
                        @php
                            $discount =  $model->discount > $sub_discount ?  $model->discount -$sub_discount : $sub_discount;
                        @endphp
                        <tr>
                            <td class="total-line">{{__('common.discount')}}</td>
                            <td  class="total-value text-right">
                                {!! Form::number('discount',$discount,['id'=>'discount', 'step'=>"any", 'class'=>'form-control input-number']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">{{__('common.total_amount')}}</td>
                            <td  class="total-value text-right">
                                {!! Form::number('grand_total',null,['id'=>'total_amount','class'=>'form-control input-number', 'readonly']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">{{__('common.amount_to_pay')}}</td>
                            <td  class="total-value text-right">
                                {!! Form::number('amount_to_pay',null,['id'=>'amount_to_pay','class'=>'form-control input-number', 'readonly']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">{{__('common.paid_amount')}}</td>
                            <td  class="total-value text-right">
                                {!! Form::number('paid_amount',null,['id'=>'paid_amount','class'=>'form-control input-number', 'step'=>"any", 'required']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">{{__('common.due_amount')}}</td>
                            <td  class="total-value text-right">

                                {!! Form::number('due',null,['id'=>'due_amount','class'=>'form-control input-number',  'step'=>"any", 'readonly' ]); !!}
                            </td>
                        </tr>

                    </table>

                </div>

                <div class="col-lg-12 col-md-12 "   style="max-width: 100%; flex: 100%;">
                    <table class="new-table-3 pull-right" style="margin-top: 20px; margin-bottom: 20px"  >
                        <tr>
                            <td>
                                <button style="width: 100px" type="submit" class="btn btn-block btn-primary">{{__('common.update')}}</button>
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

            var editItem = '<input type="hidden" name="sale_details_id[]" value="new">';
        </script>
        @include('member.sales.edit-scripts')
    @endpush


@endsection


