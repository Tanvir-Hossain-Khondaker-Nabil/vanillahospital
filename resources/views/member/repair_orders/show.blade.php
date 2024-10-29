<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/25/2019
 * Time: 10:24 AM
 */

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => route('member.dashboard'),
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'repair_orders',
        'href' => route('member.repair_orders.index'),
    ],
    [
        'name' => 'Show',
    ],
];

$data['data'] = [
    'name' => 'Repair Invoice',
    'title'=> 'Repair Invoice',
    'heading' => trans('common.invoice'),
];

?>
@extends('layouts.back-end.master', $data)


@section('contents')
    <div class="row text-right">
        <div class="col-md-12">

            <div class="box">
                @include('common._alert')

                <div class="box-body">


                    {{--<a href="{{ route('member.repair_orders.print_calan', $orders->id) }}" class="btn btn-xs btn-info" id="btn-print"><i class="fa fa-print"></i> Calan Print </a>--}}
                    {{--<a href="{{ route('member.repair_orders.print_calan', $orders->id) }}?based=download&pad_type=with" class="btn btn-xs btn-flickr" ><i class="fa fa-download"></i> Calan Download </a>--}}
                    {{--<a href="{{ route('member.repair_orders.print_calan', $orders->id) }}?based=download&pad_type=without" class="btn btn-xs bg-purple" ><i class="fa fa-download"></i> Calan Download without Pad</a>--}}
                    <a href="{{ route('member.repair_orders.show', $orders->id) }}?based=print" class="btn btn-xs btn-primary" id="btn-print"><i class="fa fa-print"></i> Print </a>

                    {{--<a href="{{ route('member.repair_orders.print_sale', $orders->id) }}?based=download&pad_type=without" class="btn btn-xs bg-olive"><i class="fa fa-download"></i> Download Repair Invoice Without Pad </a>--}}
                    {{--<a href="{{ route('member.repair_orders.print_sale', $orders->id) }}?based=download&pad_type=with" class="btn btn-xs btn-github"><i class="fa fa-download"></i> Download Repair Invoice  </a>--}}
                    {{--<a href="{{ route('member.repair_orders.print_sale', $orders->id) }}?based=profit_invoice" class="btn btn-xs btn-dropbox" id="btn-print"><i class="fa fa-print"></i> Print Profit Repair Invoice </a>--}}
{{--                    <a href="{{ route('member.repair_orders.show', $orders->id) }}?based=profit_invoice" class="btn btn-xs btn-dropbox"><i class="fa fa-print"></i> Show Profit Repair Invoice </a>--}}

                    <a href="{{ route('member.repair_orders.edit', $orders->id) }}" class="btn btn-xs btn-success">
                        <i class="fa fa-pencil"></i> {{__('common.Edit')}}
                    </a>
{{--                    <a href="{{ route('member.repair_orders.create') }}" class="btn btn-xs btn-danger"><i class="fa fa-plus"></i> {{__('sale.add_sale')}}</a>--}}
{{--                    <a href="javascript:void(0);"  class="btn btn-xs btn-danger delete-confirm" data-target="{{route('member.transaction.destroy', $orders->transaction->id)}}">--}}
{{--                        <i class="fa fa-trash"></i> {{__('common.Delete')}}</a>--}}
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="custom-print">
        <div class="col-md-12">
            <div class="box">

                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-4 text-left">
                            @if( isset($company_logo))
                                <img src="{{ $company_logo }}" width="60px;"/>
                            @endif
                        </div>
                        <div class="col-xs-4 text-center py-5">
                            @php print_r($sale_barcode) @endphp<br>
                            <b style="margin-left: 13px;">{{ $orders->token }}</b><br>
                        </div>
                        <div class="col-xs-4 text-right company-info">
                            <h2 style="margin: 10px auto!important">{{ $company_name }}</h2>
                            <div class="info">
                                <p >{{ $company_address }} </p>
                                <p >{{ $company_city.($company_country ? ", ".$company_country : "") }}</p>
                                <p >{{__('common.phone')}} : {{ $company_phone }}</p>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- title row -->

                    <!-- info row -->
                    <div style="margin-bottom: 10px; " class="row invoice-info">

                        <div class="col-md-5 p-5">
                            <table class="bill-info w-100">
                                <tr >
                                    <th >{{__('common.date')}}: </th>
                                    <td >{{ $orders->date_format }}</td>
                                </tr>

                                <tr >
                                    <th >{{__('common.token')}}:</th>
                                    <td >{{ $orders->token }}</td>
                                </tr>
                                <tr >
                                    <th >{{__('common.invoice_no')}}:</th>
                                    <td >{{ "INV-".$orders->id }}</td>
                                </tr>

                                <tr >
                                    <th >{{__('common.account')}}:</th>
                                    <td >{{ $orders->cash_or_bank->title }}</td>
                                </tr>


                                <tr style="border-top: 1px solid #eee; margin-top: 10px;">
                                    <th width="25%" style="text-align: left;">Product:</th>
                                    <th  style="text-align: left;">{{ $orders->product_name }}</th>
                                </tr>
                                <tr>
                                    <td width="25%" style="text-align: left;">Defects:</td>
                                    <th style="text-align: left;">
                                        @php
                                            foreach ($orders->defects() as $value)
                                                {
                                                    echo $value->name.", ";
                                                }
                                        @endphp
                                    </th>
                                </tr>
                                <tr >
                                    <td width="25%" style="text-align: left;">Defect Description:</td>
                                    <td  style="text-align: left;">{{ $orders->defect_description }}</td>
                                </tr>
                                <tr >
                                    <td width="25%" style="text-align: left;">Delivery:</td>
                                    <td  style="text-align: left;"> {{ $orders->estimate_delivery_date }}</td>
                                </tr>
                            </table>
                        </div>
                        @if($orders->customer)
                            <div style="border--left: 1px solid #d2d1d1;" class="col-md-push-3 col-md-4 p-5 ">
                                <h4>{{__('common.customer_info')}}:</h4>
                                <table class="w-100 customer-table-info">
                                    <tr >
                                        <th class="w-25">{{__('common.name')}}: </th>
                                        <td class="w-75"> {{ $orders->customer->name }}</td>
                                    </tr>
                                    <tr >
                                        <th class="w-25">{{__('common.address')}}:</th>
                                        <td class="w-75">{{ $orders->customer->address }}</td>
                                    </tr>
                                    <tr >
                                        <th class="w-25">{{__('common.phone')}}:</th>
                                        <td class="w-75">{{ $orders->customer->phone }}</td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                    </div>
                    <!-- /.row -->

                    <!-- Table row -->
                    <div class="row">
                        <div class="col-md-12 ">

                            @php
                                $total = 0;
                            @endphp
                            @if($orders->repair_items()->where('item_type', 0)->count()>0)
                            <table style="width: 100%" class="sales_table">
                                <thead>
                                <tr class=" bg-gray">
                                    <th width="50px">SL</th>
                                    <th>{{__('common.Parts')}} </th>
                                    <th class="text-center"> {{ __('common.cover_by_warranty_gurrantee') }}</th>
                                    <th class="text-center">{{__('common.quantity')}}</th>
                                    <th class="text-center">{{__('common.price_per_qty')}}</th>
                                    <th class="text-right">{{__('common.total_price')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $orders->repair_items()->where('item_type', 0)->get() as $key=>$order)
                                    <tr>
                                        <td width="5%">{{ $key+1 }}</td>
                                        {{--<td>{{ $order->item }}</td>--}}

                                        <td width="35%">{{ $order->item->item_name }}</td>
                                        <td class="text-center">{{ $order->cover_by_warranty ? "YES" : "" }}</td>
                                        <td class="text-center">{{ $order->qty }}</td>
                                        <td class="text-center">{{ create_money_format($order->price) }}</td>
                                        <td class="text-right">{{ create_money_format($order->price*$order->qty) }}</td>
                                    </tr>

                                    @php
                                        $total += $order->total_price;
                                    @endphp
                                @endforeach
                                <tr>
                                    <th colspan="5" class="text-right"> {{ __('common.total') }}</th>
                                    <th class="text-right">{{ create_money_format($orders->total_item_price) }}</th>
                                </tr>
                                </tbody>
                            </table>
                            @endif

                            @if($orders->repair_items()->where('item_type', 1)->count()>0)
                            <table style="width: 100%" class="sales_table mt-3">
                                <thead>
                                <tr class=" bg-gray">
                                    <th width="5%">SL</th>
                                    <th  width="35%">{{__('common.services')}} </th>
                                    <th class="text-center"> {{ __('common.cover_by_warranty_gurrantee') }}</th>
                                    <th class="text-center">{{__('common.quantity')}}</th>
                                    <th class="text-center">{{__('common.price_per_qty')}}</th>
                                    <th class="text-right">{{__('common.total_price')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $orders->repair_items()->where('item_type', 1)->get() as $key=>$order)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        {{--<td>{{ $order->item }}</td>--}}

                                        <td>{{ $order->service->title }}</td>
                                        <td class="text-center">{{ $order->cover_by_warranty ? "YES" : "" }}</td>
                                        <td class="text-center">{{ $order->qty }}</td>
                                        <td class="text-center">{{ create_money_format($order->price) }}</td>
                                        <td class="text-right">{{ create_money_format($order->price*$order->qty) }}</td>
                                    </tr>

                                    <tr>
                                        <th colspan="5" class="text-right"> {{ __('common.total') }}</th>
                                        <th class="text-right">{{ create_money_format($orders->total_service_price) }}</th>
                                    </tr>
                                    @php
                                        $total += $order->total_price;
                                    @endphp
                                @endforeach

                                </tbody>
                            </table>
                            @endif


                            <table style="width: 100%" class="sales_table ">
                                <tr>
                                    <td colspan="2"  width="40%" rowspan="10"><b>{{__('common.notation')}}:</b> {{ $orders->notation }}</td>
                                    <td colspan="1" rowspan="10" >
                                        <h1 class="text-uppercase text-center"> {{ $orders->due> 0 ? trans('common.unpaid') : trans('common.paid') }}</h1>
                                    </td>
                                    <td class="text-right" >{{__('common.sub_total')}}:</td>
                                    <td class="text-right" >{{ create_money_format($orders->sub_total) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right" >{{__('common.discount')}} :</td>
                                    <td class="text-right">(-) {{ create_money_format($orders->discount) }}</td>
                                </tr>

                                <tr>
                                    <td class="text-right" > {{__('common.amount_to_pay')}}:</td>
                                    <td class="text-right">{{ create_money_format($orders->amount_to_pay) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">{{__('common.paid_amount')}}:</th>
                                    <th class="text-right">{{ create_money_format($orders->paid) }} </th>
                                </tr>
                                <tr>
                                    <td class="text-right" >{{__('common.due_amount')}}:</td>
                                    <td class="text-right"> {{ create_money_format($orders->due) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="10" class="text-left">
                                        <span class="text-bold"> {{__('common.in_words')}}: </span> {{ AmountInWords($orders->amount_to_pay) }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->

@endsection

