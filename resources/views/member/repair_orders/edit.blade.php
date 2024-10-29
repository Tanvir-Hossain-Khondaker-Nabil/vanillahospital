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
        'name' => __('common.repairing_orders'),
        'href' => route('member.repair_orders.index'),
    ],
    [
        'name' => __('common.edit_order'),
    ],
];

$data['data'] = [
    'name' => trans('common.edit_order'),
    'title' => trans('common.edit_order'),
    'heading' => trans('common.repair_order'),
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
        @include('common._error')

        <div class="box-header with-border">
            <h3 class="box-title">{{__('common.token')}}: {{ $model->token}}</h3>
        </div>

        {!! Form::model($model, ['route' => ['member.repair_orders.update', $model],  'method' => 'put', 'role'=>'form', 'files'=>true]) !!}

        <div class="box-body">
            <div class="row">
                <div class="my-3 pl-5">

                    <input type="radio" class="repair_type" name="repair_type" disabled required value="1"  {{ $model->repair_type == 1 ? "checked" :'' }}> <b class="mr-3">
                        Pre-Sale </b>
                    <input type="radio" class="repair_type" name="repair_type" disabled value="2" {{ $model->repair_type == 2 ? "checked" :'' }}> <b> Others </b>
                </div>
            </div>
            <div class="row" style="margin-right: -10px;">
                <div class="py-0 col-lg-5 col-md-5 col-sm-12 col-sx-12 payment-info">
                    <table style="width: 100%" class="sales_table_2">
                        <tr class="order_toggle">
                            <td class="total-line ">
                                {{ __('common.order') }} {{ __('common.number') }}
                                <a href="javascript:void(0)" data-toggle="modal" data-target="#orderDetailsModal" id="orderDetails" class=" pull-right  {{ !empty($pos_html) ? :'hidden' }}"><i style="font-size: 18px;" class="fa fa-info-circle"></i></a>
                            </td>
                            <td class="total-value">
                                <div class="input-group">
                                    <input type="text" name='order_number' value="{{ $model->order_id }}" id='order_number' class="form-control">
                                    <span class="input-group-addon search"><i class="fa fa-search"></i></span>
                                </div>

                                {{--                                {!! Form::text('order_number',null,['id'=>'order_number','class'=>'form-control grid-width-90']); !!}--}}
                            </td>
                        </tr>
                        <tr>
                            <td class="total-line ">{{__('common.products')}} </td>
                            <td class="total-value" id="product_td">
                                {!! Form::text('product_name',null,['id'=>'product_name','class'=>'form-control', 'required']); !!}
                            </td>
                        </tr>
                        {{--                        <tr>--}}
                        {{--                            <td class="total-line ">{{ __('common.products') }} {{ __('common.categories') }} </td>--}}
                        {{--                            <td class="total-value">--}}
                        {{--                                {!! Form::select('product_category_id', $categories, null, ['class'=>'form-control select2 ','required']); !!}--}}
                        {{--                            </td>--}}
                        {{--                        </tr>--}}

                        @php
                            $defectIDs = json_decode($model->defect_type_ids);
                        @endphp
                        <tr>
                            <td class="total-line ">{{ __('common.defeats') }} </td>
                            <td class="total-value">
                                {!! Form::select('defect_id[]', $defects, $defectIDs, ['class'=>'form-control select2 ','required', 'multiple']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td class="total-line " colspan="2">
                                {{ __('common.defeats') }} {{ __('common.description') }} </td>

                        </tr>
                        <tr>
                            <td class="total-line " colspan="2">
                                <textarea class="form-control" name="defect_description">{{ $model->defect_description }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="total-line ">{{ __('common.take_screenshot') }} </td>
                            <td class="total-value">
                                {!! Form::file('take_screenshot', null,['id'=>'take_screenshot', 'accept'=>'image/*' , 'class'=>'form-control' ]); !!}
                            </td>
                        </tr>
                        <tr>
                            <td class="total-line " colspan="2">
                                {{ __('common.select') }} {{ __('common.service') }} </td>

                        </tr>
                        <tr>
                            <td class="total-value" colspan="2">
                                <div class="row mx-0">
                                    <div class="col-md-10 pl-1">
                                        <select  id="new_service" class='form-control select2'>
                                            <option value=""> {{ __('common.Please_select') }} </option>
                                            @foreach($services as $value)
                                                <option value="{{ $value->id }}"  data-price="{{ $value->price }}"> {{ $value->title }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 pl-1">
                                        <a class="btn btn-primary mt-0 " id="add-service" href="javascript:void(0)"><i
                                                class="fa fa-plus"></i>
                                        </a>

                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="total-line " colspan="2">
                                {{ __('common.repair') }}  {{ __('common.Parts') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="total-value" colspan="2">
                                <div class="row mx-0">
                                    <div class="col-md-10 pl-1">
                                        <select name="product" id="new_product" class='form-control select2'>
                                            <option value=""> {{ __('common.Please_select') }} </option>
                                            @foreach($products as $value)
                                                <option value="{{ $value->id }}" data-price="{{ $value->price }}"> {{ $value->item_name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 pl-1">
                                        <a class="btn btn-primary mt-0 " id="add-parts" href="javascript:void(0)"><i
                                                class="fa fa-plus"></i>
                                        </a>

                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>

                </div>

                <div class="col-lg-7  col-md-7 col-sm-12 col-sx-12 bg-gray px-0" style="min-height: 450px;">

                    <h5 class="text-bold  text-center"> {{ __('common.additional_service_parts') }}</h5>

                    <table style="width: 100%" class="table table-responsive table-striped table-bordered add-service-parts">
                        <thead>
                        <tr class="bg-white" >
                            <th style="width: 27px;" class="text-center"> #{{ __('common.SL') }}</th>
                            <th style="width: 200px;" class="text-left"> {{ __('common.title') }}</th>
                            <th style="width: 50px;" class="text-center"> {{ __('common.qty') }}</th>
                            <th style="width: 50px;" class="text-center"> {{ __('common.price') }}</th>
                            <th style="width: 100px;" class="text-center"> {{ __('common.cover_by_warranty_gurrantee') }}</th>
                            <th style="width: 20px;" class="text-center"> </th>
                        </tr>
                        </thead>
                        <tbody>

                            @foreach( $model->repair_items as $key=>$order)
                            <tr>
                                <td style="width: 27px; text-align: center;">{{ $key+1 }}</td>

                                <td style="width: 200px;">{{ $order->item_type == 1 ? $order->service->title : $order->item->item_name }}
                                    <input name="services[id][]" class="form-control" value="{{$order->item_id}}" type="hidden"/>
                                    <input name="services[type][]" class="form-control" value="{{$order->item_type == 1 ? 'service' : 'product'}}" type="hidden"/>
                                </td>

                                <td style="width: 50px; ">
                                    <input name="services[qty][]" class="form-control qty" value="{{$order->qty}}" type="number"/>
                                </td>
                                <td style="width: 100px;">  <input name="services[price][]" class="form-control price"  value="{{$order->price}}"  data-price="{{$order->price}}" step="any" type="number"/></td>
                                <td style="width: 100px; text-align: center;"> <input name="services[cover][]"  class="cover_by" value="1" type="checkbox" {{ $order->cover_by_warranty== 1 ? 'checked':'' }}/></td>
                                <td style="width: 20px;"> <a href="javascript:void(0)" class="btn-text-danger delete-field"><i class="fa fa-close"></i> </a></td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
            </div>

        </div>

        <div style="margin-top: 20px; " class="row">

            <div class="col-lg-6 col-md-6 col-sm-12 col-sx-12 payment-info">
                <table style="width: 100%" class="sales_table_2">
                    <tr>
                        <td class="total-line "> {{ __('common.entry_date') }} </td>
                        <td class="total-value">
                            <input type="text" id="date" class="form-control" name="entry_date" value="{{ db_date_month_year_format($model->entry_date) }}" />
                        </td>
                    </tr>
                    <tr>
                        <td class="total-line "> {{ __('common.customer_phone') }} </td>
                        <td class="total-value">
                            <input type="text" id="customer_phone" class="form-control"  value="{{ $model->customer->phone }}" name="customer_phone">
                            <input type="hidden" name="customer_id" value="{{ $model->customer_id }}" id="customer_id"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="total-line "> {{ __('common.customer_name') }} </td>
                        <td class="total-value">
                            <input type="text" id="customer_name" class="form-control" value="{{ $model->customer->name }}" name="customer_name">
                        </td>
                    </tr>
                    <tr>
                        <td class="total-line "> {{ __('common.estimate_delivery_date') }} </td>
                        <td class="total-value">
                            <input type="text" id="estimate_delivery_date" class="form-control"
                                   name="estimate_delivery_date"  value="{{ db_date_month_year_format($model->estimate_delivery_date) }}" />
                        </td>
                    </tr>

                    <tr>
                        <td class="total-line ">{{__('common.collect_parts')}} </td>
                        <td class="total-value">
                            {!! Form::file('collect_parts', null,['id'=>'collect_parts', 'accept'=>'image/*' , 'class'=>'form-control' ]); !!}
                        </td>
                    </tr>
                    <tr>
                        <td class="total-line ">{{__('common.payment_option')}} </td>
                        <td class="total-value">
                            <input type="checkbox" id="payment_option" name="payment_option"
                                   value="1"> {{__('common.due_credit')}}
                        </td>
                    </tr>
                </table>

            </div>


            <div class="col-lg-6 col-md-6 col-sm-12 col-sx-12 float-right amount-info">
                <table style="width: 100%" class="sales_table_2">

                    <tr>
                        <td class="total-line ">{{__('common.account_name')}} </td>
                        <td class="total-value">
                            {!! Form::select('cash_or_bank_id', $banks, $model->account_type_id,['class'=>'form-control select2 ','required']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td class="total-line">{{__('common.sub_total')}}</td>
                        <td class="total-value text-right">
                            {!! Form::number('sub_total',null,['id'=>'sub_total','class'=>'form-control input-number',  'step'=>"any" , 'readonly']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td class="total-line">{{__('common.discount')}}</td>
                        <td class="total-value text-right">
                            {!! Form::number('discount',null, ['id'=>'discount', 'step'=>"any", 'class'=>'form-control input-number']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td class="total-line">{{__('common.amount_to_pay')}}</td>
                        <td class="total-value text-right">
                            {!! Form::number('amount_to_pay',null,['id'=>'amount_to_pay','class'=>'form-control input-number', 'step'=>"any", 'readonly']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td class="total-line">{{__('common.paid_amount')}}</td>
                        <td class="total-value text-right">
                            {!! Form::number('paid_amount',null,['id'=>'paid_amount','class'=>'form-control input-number',  'step'=>"any",'required']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td class="total-line">{{__('common.due_amount')}}</td>
                        <td class="total-value text-right">
                            {!! Form::number('due',null,['id'=>'due_amount','class'=>'form-control input-number',  'step'=>"any", 'readonly']); !!}
                        </td>
                    </tr>

                </table>

            </div>


            <div class="col-lg-12 col-md-12 " style="max-width: 100%; flex: 100%;">
                <table class="new-table-3 pull-right" style="margin-top: 20px; margin-bottom: 20px">
                    <tr>
                        <td>
                        </td>
                        <td>
                            <button style="width: 100px" type="submit"
                                    class="btn btn-block btn-success">{{__('common.update')}}</button>
                        </td>
                    </tr>
                </table>
            </div>

        </div>


        {!! Form::close() !!}


        </div>

    <div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        {!! $pos_html ?? '' !!}
    </div>

    @push('scripts')

        <script type="text/javascript">

            var editItem = '<input type="hidden" name="sale_details_id[]" value="new">';
        </script>
        @include('member.repair_orders.scripts')
    @endpush


@endsection


