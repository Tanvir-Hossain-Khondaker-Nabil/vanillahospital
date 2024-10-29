<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/8/2019
 * Time: 3:06 PM
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
        'name' => 'Sales Due payment',
    ],
];

$data['data'] = [
    'name' => 'Sales Due payment',
    'title'=> 'Sales Due payment',
    'heading' => trans('common.sales_due_payment'),
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
                @include('common._alert')
                <div class="box-body">
                    <a href="{{ route('member.sales.sales_return', $sales->id) }}" class="btn btn-xs btn-info">
                        <i class="fa fa-reply"></i> {{__('common.sale_return')}}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="custom-print">
        <div class="col-md-12">
            <div class="box">

                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
                                <i class="fa fa-globe"></i> {{ $company_name }}
                                <small class="pull-right">{{__('common.date')}}: {{ $sales->date_format }}</small>
                            </h2>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- title row -->

                    <!-- info row -->
                    <div style="margin-bottom: 10px; " class="row invoice-info">

                        <div class="col-md-6 ">
                            <div style="border: 1px solid #d2d1d1; padding: 10px;" class="invoice-col">
                                <b style="margin-left: 13px;">{{ $sales->sale_code }}</b><br>
                                <br>
                                <b>{{__('common.account')}}:</b> {{ $sales->cash_or_bank->title }}<br>
                                <b>{{__('common.payment_method')}}:</b> {{ $sales->payment_method->name }}<br>
                                <b>{{__('common.delivery_system')}}:</b> {{ $sales->delivery_type->display_name }}
                            </div>
                        </div>
                        @if($sales->customer)
                            <div class="col-md-6 ">
                                <div style="border: 1px solid #d2d1d1; padding: 10px;" class="invoice-col">
                                    <h4>{{__('common.customer_info')}}:</h4>
                                    <b>{{__('common.name')}}: {{ $sales->customer->name }}</b><br>
                                    <b>{{__('common.address')}}:</b> {{ $sales->customer->address }}<br>
                                    <b>{{__('common.phone')}}:</b> {{ $sales->customer->phone }}
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- /.row -->

                    <!-- Table row -->
                    <div class="row">
                        <div class="col-md-12 ">
                            <table style="width: 100%" class="sales_table">
                                <thead>
                                <tr>
                                    <th>SL. No</th>
                                    {{--<th>Item Code</th>--}}
                                    <th>{{__('common.item_name')}} </th>
                                    <th>{{__('common.description')}}</th>
                                    <th>{{__('common.unit')}}</th>
                                    <th>{{__('common.quantity')}}</th>
                                    <th>{{__('common.price')}}</th>
                                    <th class="text-center">{{__('common.amount')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach( $sales->sale_details as $key=>$sale)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        {{--<td>{{ $sale->item }}</td>--}}
                                        <td>{{ $sale->item->item_name }}</td>
                                        <td>{{ $sale->description }}</td>
                                        <td>{{ $sale->unit }}</td>
                                        <td>{{ $sale->qty }}</td>
                                        <td>{{ $sale->price }}</td>
                                        <td class="text-right">{{ $sale->qty*$sale->price }}</td>
                                    </tr>

                                    @php
                                        $total += $sale->qty*$sale->price;
                                    @endphp
                                @endforeach

                                <tr>
                                    <td colspan="4" rowspan="8">{{__('common.notes')}}: </td>
                                    <td class="text-right" colspan="2">{{__('common.sub_total')}}:</td>
                                    <td class="text-right" >{{ create_money_format($total) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="2">{{__('common.discount')}} {{ $sales->discount_type=="fixed" ? "(Fixed)" : "(".$sales->discount."%)" }} :</td>
                                    <td class="text-right">(-) {{ create_money_format($sales->total_discount) }}</td>
                                </tr>

                                @if($sales->shipping_charge>0)
                                    <tr>
                                        <td class="text-right" colspan="2" >{{__('common.shipping_charge')}}:</td>
                                        <td class="text-right">{{ create_money_format($sales->shipping_charge) }}</td>
                                    </tr>
                                @endif

                                @if($sales->transport_cost>0)
                                    <tr>
                                        <td class="text-right"  colspan="2">{{__('common.transport_cost')}}</td>
                                        <td class="text-right" > {{ create_money_format($sales->transport_cost) }} </td>
                                    </tr>
                                @endif

                                @if($sales->unload_cost>0)
                                    <tr>
                                        <td class="text-right"  colspan="2">{{__('common.unload_cost')}}</td>
                                        <td class="text-right" > {{ create_money_format($sales->unload_cost) }} </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="text-right" colspan="2">{{__('common.total_amount')}}:</td>
                                    <td class="text-right">{{ create_money_format($sales->grand_total) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="2"> {{__('common.amount_to_pay')}}:</td>
                                    <td class="text-right">{{ create_money_format($sales->amount_to_pay) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right"colspan="2">{{__('common.paid_amount')}}:</th>
                                    <th class="text-right">{{ create_money_format($sales->paid_amount) }} </th>
                                </tr>
                                <tr>
                                    <th class="text-right" colspan="2">{{__('common.due')}}:</th>
                                    <th class="text-right"> {{ create_money_format($sales->due) }}</th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>


                <div class="table-responsive">
                    {!! Form::model($sales, ['route' => ['member.sales.receive_due_payment', $sales],  'method' => 'post']) !!}

                    <div class="col-md-3 form-group">
                        <label>  {{__('common.payment_date')}} </label>
                        {!! Form::text('date', null,['id'=>'date', 'class'=>'form-control','required', 'autocomplete'=>"off"]); !!}
                    </div>
                    <div class="col-md-3 form-group">
                        <label> {{__('common.due_payment')}} </label>
                        {!! Form::text('text_due', $sales->due,['class'=>'form-control input-number','required', 'readonly']); !!}
                    </div>
                    <div class="col-md-3 form-group">
                        <label> {{__('common.payment_amount')}} </label>
                        {!! Form::number('due_amount', null, ['class'=>'form-control input-number', 'required', "max"=>$sales->due]); !!}
                    </div>
                    <div class="col-md-12 form-group">
                        <button type="submit" id="submit" class="btn btn-primary">{{__('common.submit')}}</button>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <!-- /.row -->

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
