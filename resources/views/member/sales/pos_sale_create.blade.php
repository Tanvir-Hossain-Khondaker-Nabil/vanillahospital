<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 8/11/2020
 * Time: 12:50 PM
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
        'name' => 'POS Sale',
    ],
];

$data['data'] = [
    'name' => 'POS sales',
    'title'=> 'POS sales',
    'heading' => trans('sale.pos_sale'),
];

?>
@extends('layouts.back-end.master', $data)


@push('styles')

    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/pos_assets/css/salesstyle.css')}}">

    <style>
        input:focus-visible, select:focus-visible{
            border: 1.7px solid #2aa3d7;
            outline: 1.7px solid #2aa3d7;
        }
    </style>
@endpush

@section('contents')

    @include('common._alert')


    {!! Form::open(['route' => 'member.sales.pos_store','method' => 'POST', 'files'=>true, 'id'=>'sale_form', 'role'=>'form' ]) !!}

    <div class="row">
        <div class="col-md-12 border-right-1">
{{--            <div class="row">--}}
{{--                <div class="col-md-12 mt-4">--}}
{{--                    <div class="input-group mb-5">--}}
{{--                        <span class="input-group-addon"><i class="fa fa-search"></i></span>--}}
{{--                        <input type="text" class="form-control" placeholder="Search">--}}
{{--                        <span class="input-group-addon"><a href="" ><span class=" btn-sm">Search</span></a></span>--}}
{{--                    </div>--}}

{{--                </div>--}}
{{--            </div>--}}
            <div class="row mb-5"  style=" min-height: 415px;">


                <div class="col-md-5 "   style=" min-height: 415px;">
                    <div class="row ml-1 mt-4">
                        <div class="col-md-12 pl-1">
                            <input type="text" style="width: 100%; border-radius: 15px; padding: 9px 15px 6px;border: 1px solid lightgray" id="barcode_search" placeholder="{{__('sale.enter_product_code_or_scan_barcode')}}" name="barcode_search">
                        </div>
                        <hr class="ml-4 my-2">

                        <div class="col-md-12 px-0 ml-3" id="search_product">
                            <ul class="search_product_list px-0">

                            </ul>
                        </div>
                    </div>


                    <div class="row  ml-2 mt-3">
                        <div class="col-md-12" style="max-height: 415px; overflow-y: scroll;">
                            <div class="row" id="showlist">

                                @foreach($products as $key => $value)
                                    <a  onfocus="this.blur()"  tabindex="-1" href="javascript:void(0)" class="add-row" data-target="{{  $value->id  }}" data-value="{{ $value->item_name }}" data-unit="{{ $value->unit }}" data-pcode="{{ $value->productCode }}" data-skucode="{{ $value->skuCode }}" data-stock="{{ $value->stock_details->stock }}" >
                                        <div class="col-md-3 px-0 py-3  mb-3 card-body text-center" style="border:1px solid lightblue; height: 130px;">
                                            <div class="w-100">
                                                <img src="{{ $value->product_image ? asset($value->product_image_path) : asset('public/pos_assets/images/item.png') }}" style="width: 50px;height: 40px;" class="mb-2">
                                            </div>
                                            <div class="w-100 text-black px-1">
                                                {{ limit_words($value->item_name,5) }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-7"  style=" min-height: 415px;">
                    <table  style="width: 100%;" class="table product-table">
                        <thead>
                        <tr>
                            <th style="width: 10px;">#</th>
                            <th>{{__('common.product')}}</th>
                            <th >{{__('common.available_stock')}}</th>
                            <th >{{__('common.qty')}}</th>
                            <th >{{__('common.price')}}</th>
                            <th  style="width: 100px; text-align: right;" >{{__('common.total')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="text-left" id="sales_point" >

                        </tbody>

                    </table>
                </div>


            </div>

            <div class="row mx-0  bg-white ">
                <div class="col-md-12 text-center px-0  mb-4">
                    <h4 class="modal-title text-bold py-3 w-100 border-bottom-1" >{{__('common.make_payment')}}</h4>

                </div>
                <div class="col-md-6">
                    <input type="hidden" id="sub_total" name="sub_total" value="0" />
                    <input type="hidden" id="amount_to_pay" name="amount_to_pay" value="0" />
                    <input type="hidden" id="total_amount" name="total_amount" value="0" />

                    <table class="table tfooter" style="width: 100%;">
                        <tr>
                            <td style="width: 50%">
                                <label class="h6 my-2"> {{__('common.invoice_no')}}</label>
                                {!! Form::text('memo_no',strtoupper($memo_no),[ 'class'=>'form-control text-right', 'disabled']); !!}
                            </td>
                            <td style="width: 50%;">
                                <label class="h6 my-2"> {{__('common.invoice_date')}}</label>
                                <input name="date" type="text" id="date"  class="form-control" >
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%;">
                                <label class="h6 my-2"> {{__('common.discount_type')}}</label>
                                {!! Form::select('discount_type', ['fixed'=>'Fixed', 'percentage'=>'Percentage'], null,['id'=>'discount_type','class'=>'form-control',  'step'=>"any"]); !!}
                            </td>
                            <td style="width: 50%">
                                <label class="h6 my-2"> {{__('common.discount')}}</label>
                                {!! Form::number('discount',null,['id'=>'discount', 'step'=>"any", 'class'=>'form-control input-number text-right']); !!}
                            </td>
                        </tr>


                        <tr>
                            <td style="width: 50%;">

                                <label class="h6 my-2"> {{__('common.shipping')}}</label>
                                {!! Form::number('shipping_charge',null,['id'=>'shipping_charge', 'step'=>"any", 'class'=>'form-control input-number text-right']); !!}
                            </td>
                            <td style="width: 50%">
                                <label class="h6 my-2"> {{__('sale.membership_card_no')}} <span id="member_point" class="text-bold text-blue"></span></label>
                                {!! Form::text('membership', null,['id'=>'membership', 'class'=>'form-control  ', 'placeholder'=>' Membership Card']); !!}

                            </td>
                        </tr>

                        <tr>
                            <td style="width: 50%;">

                                <label class="h6 my-2">  {{__('common.customer_phone')}}</label>
                                {!! Form::text('customer_phone', null,['id'=>'customer_phone', 'class'=>'form-control  ', 'placeholder'=>'Customer Phone']); !!}
                            </td>
                            <td style="width: 50%; font-size: 13px;" id="member_info">
                                <label class="h6 my-2">  {{__('common.customer_name')}}</label>
                                {!! Form::text('customer_name', null,['id'=>'customer_name', 'class'=>'form-control  ', 'placeholder'=>'Customer Name']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%;">
                                <label class="h6 my-2">  {{__('common.payment_method')}}</label>
                                {!! Form::select('payment_method_id', $payment_methods, null,['class'=>'form-control select2','required']); !!}
                            </td>
                            <td style="width: 50%">
                                <label class="h6 my-2">  {{__('common.cash_bank_account')}}</label>
                                {!! Form::select('cash_or_bank_id', $banks, config('settings.cash_bank_id'),['class'=>'form-control select2','required']); !!}
                            </td>
                        </tr>

                        <tr>
                            <td style="width: 50%;">
                                <label class="h6 my-2">  {{__('common.visa_card_mobile_banking_number')}} </label>
                                {!! Form::text('payment_number', null,['class'=>'form-control  ', 'placeholder'=>'']); !!}
                            </td>

                            <td style="width: 50%">
                                <label class="h6 my-2">  {{__('common.notes')}}</label>
                                <input class="form-control" name="notation" />
                            </td>
                        </tr>

                    </table>

                </div>
                <div class="col-md-6">
                    <table class="table tfooter" style="width: 100%;">


                        <tr>
                            <td style="width: 50%;">
                                <label class="h6 my-2"> {{__('common.use_point')}}</label>
                                {!! Form::number('use_point',null,[ 'class'=>'form-control text-right input-number']); !!}
                            </td>
                            <td style="width: 50%">
                                <label class="h6 my-2">   {{__('common.paid_amount')}}</label>
                                {!! Form::hidden('paid_amount',null,['id'=>'paid_amt', 'step'=>"any", 'class'=>'form-control input-number text-right', 'required']); !!}
                                {!! Form::number('given_amount',null,['id'=>'paid_amount', 'step'=>"any", 'class'=>'form-control input-number text-right', 'required']); !!}
                            </td>
                        </tr>
                        {{--<tr>--}}
                            {{--<td   style="font-weight: 500;width: 50%;">--}}
                                {{--Shopping Bags--}}
                            {{--</td>--}}
                            {{--<td style="width: 50%">--}}
                                {{--@foreach($bags as $value)--}}
                                        {{--{!! Form::number("shopping_bags_".$value->id,null,['class'=>'form-control bags_qty','data-option'=>$value->id]); !!}--}}
                                {{--@endforeach--}}

                            {{--</td>--}}
                        {{--</tr>--}}
                        <tr>
                            <td style="font-weight: 500;width: 50%;">{{__('common.sub_total')}}</td>
                            <td style="width: 50%">
                                <h4 class="text-secondary text-right my-2" id="total_sub_price">
                                    0.00
                                </h4>

                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: 500;width: 50%;"> {{__('common.total_shipping')}} </td>
                            <td style="width: 50%">
                                <h4 class="text-secondary text-right my-2" id="total_ship">
                                    0.00
                                </h4>

                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: 500;width: 50%;">{{__('common.total_discount')}} (-)</td>
                            <td style="width: 50%">
                                <h4 class="text-secondary text-right my-2" id="discount_amount">
                                    0.00
                                </h4>

                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: 500;width: 50%;">{{__('common.amount_to_pay')}}</td>
                            <td style="width: 50%">
                                <h3 class="text-success text-right my-2" id="amt_to_pay">
                                    0.00
                                </h3>

                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: 500;width: 50%;">{{__('common.balance_due')}}</td>
                            <td style="width: 50%">
                                <h4 class="text-danger text-right my-2" id="due_amount">
                                    0.00
                                </h4>

                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: 500;width: 50%;">{{__('common.change')}}</td>
                            <td style="width: 50%">
                                <h4 class="text-primary text-right  my-2" id="change_amount">
                                    0.00
                                </h4>
                            </td>
                        </tr>



                        <tr>
                            <td colspan="2" style="font-weight: 500; text-align: right">

                                <button type="submit" class=" btn btn-info py-3" style="width: 49%;" ><i class="fa fa-print mr-1"></i>{{__('common.pay_now')}} + {{__('common.print')}}</button>

                            </td>
                        </tr>


                    </table>

                </div>

            </div>

        </div>



    </div>
    <input type="hidden" value="" name="customer_id" id="customer_id"/>
    <input type="hidden" value="1" name="submit_type" id="submit_type"/>

    {!! Form::close() !!}

{{--    <audio id="audiotag1" src="{{ asset('public/pos_assets/click.mp3') }}"></audio>--}}

    @push('scripts')
        <script type="text/javascript">

            var editItem = '';
        </script>

        @if(config('settings.price_system') == "total_price_price_qty")

            @include('member.sales.pos_total_price_to_qty_scripts')

        @elseif(config('settings.price_system') == "total_price_qty_price")

            @include('member.sales.pos_total_price_to_price_scripts')
        @else

            @include('member.sales.pos_scripts')
        @endif

    @endpush



@endsection
