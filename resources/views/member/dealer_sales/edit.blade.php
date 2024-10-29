<?php
/**
 * Editd by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/26/2019
 * Time: 2:35 PM
 */

$sub_total = 0;
$route =  \Auth::user()->can(['member.dealer_sales.index']) ? route('member.dealer_sales.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'sales',
        'href' => $route,
    ],
    [
        'name' => 'Edit',
    ],
];

$data['data'] = [
    'name' => 'Edit sales',
    'title'=> 'Edit sales',
    'heading' => 'Edit Sale',
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
            <h3 class="box-title">New Sales Order Entry</h3>
        </div>

        {!! Form::model($model, ['route' => ['member.dealer_sales.update', $model],  'method' => 'put', 'role'=>'form', 'files'=>true]) !!}

        <div class="box-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-4 col-form-label">Order Date</label>
                        <div class="col-sm-8">
                            {!! Form::text('date',create_date_format($model->date, '/'),['id'=>'date','class'=>'form-control']); !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-4 col-form-label">Customer Name</label>
                        <div class="col-sm-8">
                            {!! Form::select('customer_id', $customers, null,['id'=>"customer_id", 'class'=>'form-control select2', 'placeholder'=>'Select Customer']); !!} <br/>
                            {{--                            <button class="btn btn-primary btn-xs" type="button" data-toggle="modal" data-target="#addCustomer"> <i class="fa fa-plus-circle"></i> Add Customer </button>--}}
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-4 col-form-label">Last Credit Amount</label>
                        <div class="col-sm-8">
                            {!! Form::text('last_credit',null,['id'=> 'last_credit','class'=>'form-control','readonly']); !!}
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>

                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-4 col-form-label">Membership Card No</label>
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
                    <h4>Sales Order Item</h4>

                    <table class="sales_table" id="items">


                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Description</th>
                            <th>Available Stock</th>
                            <th>Unit</th>
                            <th>Qty</th>
                            <th>Carton</th>
                            <th>Free</th>
                            <th> Per Qty Price</th>
                            <th>Total Price</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($model->sale_details as $key=>$sale)
                            <tr class="item-row">
                                <td class="item-name">
                                    <input type="hidden" value="{{ $sale->id }}" name="sale_details_id[]">
                                    {!! Form::select('product_id[]', $products, $sale->item_id,['id'=>'product_id_0', 'data-option'=>$key, 'class'=>'form-control select2 item-name','required', 'placeholder'=>'Select Item Name', !$loop->last ? "readonly":'']); !!}
                                </td>
                                <td class="description">
                                    {!! Form::text('description[]', $sale->description,[ 'class'=>'form-control ']); !!}

                                </td>
                                <td>{!! Form::text('available_stock[]',$sale->available_stock,['id'=>'stock_'.$key,'class'=>'form-control', 'readonly']); !!}</td>
                                <td>
                                    {!! Form::text('unit[]',$sale->unit,['id'=>'unit_'.$key,'class'=>'form-control', 'disabled']); !!}
                                </td>
                                <td>{!! Form::number('qty[]',$sale->qty,['id'=>'qty_'.$key,'class'=>'form-control qty', 'data-target'=> $key,  'step'=>"any", 'required']); !!}</td>
                                <td>
                                    {!! Form::hidden('pack_qty[]',$sale->pack_qty,['id'=>'pack_qty_'.$key,'class'=>'form-control', 'readonly']); !!}
                                    {!! Form::number('carton[]',$sale->carton,['id'=>'carton_'.$key,'class'=>'form-control', 'readonly']); !!}
                                </td>
                                <td>
                                    {!! Form::number('free_qty[]',$sale->free,['id'=>'free_qty_'.$key,'class'=>'form-control', 'readonly']); !!}
                                    {!! Form::hidden('free[]',$sale->free_qty,['id'=>'free_'.$key,'class'=>'form-control', 'readonly']); !!}
                                </td>
                                <td>{!! Form::number('price[]',$sale->price,['id'=>'price_'.$key,'class'=>'form-control price',  'step'=>"any", 'required']); !!}</td>
                                <td>{!! Form::number('total_price[]',$sale->total_price,['id'=>'total_price_'.$key,'class'=>'form-control total_price', 'step'=>"any", 'readonly']); !!}</td>
                                <td>
                                    @if($loop->last)
                                        <a class="btn btn-success add-row pull-right margin-top-3" href="#" ><i class="fa fa-plus-circle"></i> </a>
                                    @else
                                        <a href="#" class="btn-text-info margin-top-3 edit-button"><i class="fa fa-pencil"></i></a><a href="#" class="btn-text-danger margin-top-3 delete-field"><i class="fa fa-close"></i></a>
                                    @endif
                                </td>
                            </tr>

                            @php
                                $sub_total += $sale->total_price;
                            @endphp
                        @endforeach
                        </tbody>
                    </table>

                </div>

            </div>

            <div style="margin-top: 20px; " class="row">
                <div  style="margin-bottom: 10px" class="col-lg-6 col-md-6 col-sm-12 col-sx-12 float-right amount-info">
                    <table style="width: 100%" class="sales_table_2">
                        <tr>
                            <td  class="total-line">Sub Total</td>
                            <td  class="total-value text-right">
                                {!! Form::number('sub_total',$sub_total,['id'=>'sub_total','class'=>'form-control input-number', 'readonly']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">Transport Cost</td>
                            <td  class="total-value text-right">
                                {!! Form::number('transport_cost',0,['id'=>'transport_cost','class'=>'form-control input-number',  'step'=>"any"]); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">Labor Cost</td>
                            <td  class="total-value text-right">
                                {!! Form::number('labor_cost',0,['id'=>'labor_cost','class'=>'form-control input-number',  'step'=>"any"]); !!}
                            </td>
                        </tr>
                        <tr>
                            <td class="total-line">Discount Type</td>
                            <td  class="total-value text-right">
                                {!! Form::select('discount_type', ['fixed'=>'Fixed', 'percentage'=>'Percentage'], null,['id'=>'discount_type','class'=>'form-control']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td class="total-line">Discount</td>
                            <td  class="total-value text-right">
                                {!! Form::number('discount',null,['id'=>'discount', 'step'=>"any", 'class'=>'form-control input-number']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">Total Amount</td>
                            <td  class="total-value text-right">
                                {!! Form::number('grand_total',null,['id'=>'total_amount','class'=>'form-control input-number', 'readonly']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">Amount to Pay</td>
                            <td  class="total-value text-right">
                                {!! Form::number('amount_to_pay',null,['id'=>'amount_to_pay','class'=>'form-control input-number', 'readonly']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">Paid Amount</td>
                            <td  class="total-value text-right">
                                {!! Form::number('paid_amount',null,['id'=>'paid_amount','class'=>'form-control input-number', 'step'=>"any", 'required']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">Due Amount</td>
                            <td  class="total-value text-right">

                                {!! Form::number('due',null,['id'=>'due_amount','class'=>'form-control input-number',  'step'=>"any", 'readonly' ]); !!}
                            </td>
                        </tr>

                    </table>

                </div>
                <div  class="col-lg-6 col-md-6 col-sm-12 col-sx-12 float-right payment-info">
                    <table style="width: 100%" class="sales_table_2">

                        <tr>
                            <td  class="total-line ">Account Name </td>
                            <td  class="total-value">
                                {!! Form::select('cash_or_bank_id', $banks, null,['class'=>'form-control select2 ','required']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td   class="total-line ">Memo No </td>
                            <td  class="total-value">
                                {!! Form::text('memo_no',null,['id'=>'memo_no','class'=>'form-control']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line ">Chalan  No </td>
                            <td  class="total-value">
                                {!! Form::text('chalan_no',null,['id'=>'chalan_no','class'=>'form-control']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line ">Vehicle Number </td>
                            <td  class="total-value">
                                {!! Form::text('vehicle_number',null,['id'=>'vehicle_number','class'=>'form-control']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td   class="total-line ">Payment Method </td>
                            <td  class="total-value">
                                {!! Form::select('payment_method_id', $payment_methods, null,['class'=>'form-control select2 ','required']); !!}
                            </td>
                        </tr>
                        <tr>

                            <td class="total-line ">Delivery System </td>
                            <td  class="total-value">
                                {!! Form::select('delivery_type_id', $delivery_types, null,['class'=>'form-control select2 ','required']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line " colspan="2">
                                <label> Shopping Bags</label><br/>
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
                            <td  class="total-line">Notation </td>
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
                                <button style="width: 100px" type="submit" class="btn btn-block btn-primary">Update</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


            {!! Form::close() !!}


        </div>
    </div>


    @include('member.sales._model_add_customer')

    @push('scripts')

        <script type="text/javascript">

            var editItem = '<input type="hidden" name="sale_details_id[]" value="new">';
        </script>
        @include('member.sales.edit-scripts')
    @endpush


@endsection


