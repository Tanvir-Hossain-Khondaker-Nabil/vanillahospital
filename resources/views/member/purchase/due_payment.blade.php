<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/8/2019
 * Time: 11:50 AM
 */

 $route =  \Auth::user()->can(['member.purchase.index']) ? route('member.purchase.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Purchases',
        'href' => $route,
    ],
    [
        'name' => $purchase['memo_no'],
    ],
];

$data['data'] = [
    'name' => 'Purchase Due Payment Memo no: '.$purchase['memo_no'],
    'title'=>'Due Payment Memo no: '.$purchase['memo_no'],
    'heading' => trans('purchase.purchase_due_payment_memo_no').': '.$purchase['memo_no'],
];

?>


@push('styles')


    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
@endpush
@extends('layouts.back-end.master', $data)

@section('contents')

    <div class="row text-right">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <a href="{{ route('member.purchase_return.edit', $purchase->id) }}" class="btn btn-xs btn-info">
                        <i class="fa fa-reply"></i> {{__('purchase.purchase_return')}}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">
                <div class="box-body">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive" id="custom-print">
                            <table class="table table-responsive table-striped table-bordered ">
                                <thead class="text-center">
                                <tr>
                                    <th colspan="6">{{__('purchase.purchase_details')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>{{__('common.memo_no')}}</th>
                                        <td>{{ $purchase['memo_no'] }}</td>
                                        <th>{{__('common.chalan')}}</th>
                                        <td>{{ $purchase['chalan'] }}</td>
                                        <th>{{__('common.date')}}</th>
                                        <td>{{ $purchase['date_format'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('common.supplier')}}</th>
                                        <td>{{ $purchase->supplier ? $purchase->supplier->name : '' }}</td>
                                        <th>{{__('common.account_name')}}</th>
                                        <td>{{ $purchase->cash_or_bank->title }}</td>
                                        <th>{{__('common.payment_method')}}</th>
                                        <td>{{ $purchase->payment_method->name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-responsive table-bordered margin-top-30 float-right" >
                                <tbody>
                                <tr>
                                    <th>{{__('common.item_name')}}</th>
                                    <th>{{__('common.description')}}</th>
                                    <th>{{__('common.unit')}}</th>
                                    <th>{{__('common.qty')}}</th>
                                    <th>{{__('common.price')}}</th>
                                    <th class="text-center">{{__('common.total_price')}}</th>
                                </tr>
                                @php $total = 0; @endphp
                                @foreach($purchase->purchase_details as $value)
                                    <tr>
                                        <td>{{ $value->item->item_name }}</td>
                                        <td>{{ $value->description }}</td>
                                        <td>{{ $value->unit }}</td>
                                        <td> {{ $value->qty }}</td>
                                        <td> {{ create_money_format($value->price) }}</td>
                                        <td class="text-right" >{{ create_money_format($value->qty*$value->price) }}</td>
                                    </tr>
                                    @php
                                        $total += ($value->qty*$value->price);
                                    @endphp
                                @endforeach
                                </tbody>

                            </table>
                            <table class="margin-top-30" style="width: 700px; float: left;">
                                <tr>
                                    <th> {{__('common.notes')}}: </th>
                                </tr>
                                <tr>
                                    <td>@php print_r($purchase->notation) @endphp</td>
                                </tr>
                            </table>
                            <table class=" margin-top-30 pull-right" width="400px">

                                <tr>
                                    <th class="text-right" colspan="5"> {{__('common.sub_total')}}</th>
                                    <th class="text-right" > {{ create_money_format($total) }} </th>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">{{__('common.transport_cost')}}</td>
                                    <td class="text-right" > {{ create_money_format($purchase->transport_cost) }} </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">{{__('common.unload_cost')}}</td>
                                    <td class="text-right" > {{ create_money_format($purchase->unload_cost) }} </td>
                                </tr>
                                <tr>
                                    <th class="text-right" colspan="5">{{__('common.grand_total')}}</th>
                                    <th class="text-right" > {{ create_money_format($purchase->total_amount) }} </th>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">{{__('common.discount')}} ({{ $purchase->discount_type == "Percentage" ? $purchase->discount."%" : $purchase->discount_type }})</td>
                                    <td class="text-right" > {{ $purchase->total_discount > 0 ? "- (".create_money_format($purchase->total_discount).")" : create_money_format(0.00) }} </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">{{__('common.amount_to_pay')}}</td>
                                    <td class="text-right" > {{ create_money_format($purchase->amt_to_pay) }} </td>
                                </tr>
                                <tr>
                                    <th class="text-right" colspan="5">{{__('common.paid_amount')}}</th>
                                    <th class="text-right" > {{ create_money_format($purchase->paid_amount) }} </th>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">{{__('common.due_amount')}}</td>
                                    <td class="text-right" > {{ create_money_format($purchase->due_amount) }} </td>
                                </tr>
                            </table>
                        </div>
                        <div class="table-responsive">
                            {!! Form::model($purchase, ['route' => ['member.purchase.receive_due_payment', $purchase],  'method' => 'post']) !!}

                            <div class="col-md-3 form-group">
                                <label>  {{__('common.payment_date')}} </label>
                                {!! Form::text('date', null,['id'=>'date', 'class'=>'form-control','required', 'autocomplete'=>"off"]); !!}
                            </div>

                            <div class="col-md-3 form-group">
                                <label> {{__('common.due_payment')}} </label>
                                {!! Form::text('text_due', $purchase->due_amount,['class'=>'form-control','required  input-number', 'readonly']); !!}
                            </div>
                            <div class="col-md-3 form-group">
                                <label> {{__('common.payment_amount')}} </label>
                                {!! Form::number('due', null, ['class'=>'form-control input-number', 'required','max'=>$purchase->due_amount]); !!}
                            </div>
                            <div class="col-md-12 form-group">
                                <button type="submit" id="submit" class="btn btn-primary">{{__('common.submit')}}</button>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
{{--                    <div class="text-left margin-top-30">--}}
{{--                        <a href="javascript:void(0)" class="btn btn-primary btn-sm" id="print"><i class="fa fa-print"></i> Print</a>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script type="text/javascript">

        // var date = new Date();
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
            $('.select2').select2();

        });

    </script>
@endpush

