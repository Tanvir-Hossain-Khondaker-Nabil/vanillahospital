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
        'name' => 'sales',
        'href' => route('member.sales.index'),
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Profit Invoice',
    'title'=> 'Profit Invoice',
    'heading' => trans('common.profit_invoice'),
];

?>
@extends('layouts.back-end.master', $data)


@section('contents')
    <div class="row text-right">
        <div class="col-md-12">
            <div class="box">
                @include('common._alert')
                <div class="box-body">

                    <a href="{{ route('member.sales.print_sale', $sales->id) }}?based=profit_invoice" class="btn btn-xs btn-dropbox" id="btn-print"><i class="fa fa-print"></i> {{__('common.print_profit_invoice')}} </a>

                    <a href="{{ route('member.sales.sales_return', $sales->id) }}" class="btn btn-xs btn-info">
                        <i class="fa fa-reply"></i> {{__('common.sale_return')}}
                    </a>

                    <a href="{{ route('member.sales.edit', $sales->id) }}" class="btn btn-xs btn-success">
                        <i class="fa fa-pencil"></i> {{__('common.Edit')}}
                    </a>
                    <a href="{{ route('member.sales.create') }}" class="btn btn-xs btn-danger"><i class="fa fa-plus"></i> {{__('sale.add_sale')}}</a>
                    <a href="{{ route('member.sales.whole_sale_create') }}" class="btn btn-xs btn-warning"><i class="fa fa-plus"></i> {{__('sale.whole_sale')}}</a>
                    <a href="javascript:void(0);"  class="btn btn-xs btn-danger delete-confirm" data-target="{{route('member.transaction.destroy', $sales->transaction->id)}}">
                        <i class="fa fa-trash"></i> {{__('common.Delete')}}</a>
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
                            <img src="{{ $company_logo }}" width="100px;"/>
                        @endif
                    </div>
                    <div class="col-xs-3 text-center py-5">
                        @php print_r($sale_barcode) @endphp<br>
                        <b style="margin-left: 13px;">{{ $sales->sale_code }}</b><br>
                    </div>
                    <div class="col-xs-5 text-right company-info">
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

                    <div class="col-md-4 p-5">
                        <table class="bill-info w-100">
                            <tr >
                                <th >{{__('common.date')}}: </th>
                                <td >{{ $sales->date_format }}</td>
                            </tr>
                            @if( $sales->quotation_id )
                                <tr >
                                    <th >{{__('common.quotation')}} REF:</th>
                                    <td >{{ $sales->quotation->ref }}</td>
                                </tr>
                            @endif
                            <tr >
                                <th >{{__('common.invoice_no')}}:</th>
                                <td >{{ "INV-".$sales->id }}</td>
                            </tr>
                            <tr >
                                <th >{{__('common.memo_no')}}:</th>
                                <td >{{ $sales->memo_no }}</td>
                            </tr>
                            @if( $sales->chalan_no )
                                <tr >
                                    <th >{{__('common.chalan_no')}}:</th>
                                    <td >{{ $sales->chalan_no }}</td>
                                </tr>
                            @endif
                            <tr >
                                <th >{{__('common.account')}}:</th>
                                <td >{{ $sales->cash_or_bank->title }}</td>
                            </tr>
                            <tr >
                                <th >{{__('common.payment_method')}}:</th>
                                <td >{{ $sales->payment_method->name }}</td>
                            </tr>
                            @if( $sales->vehicle_number )
                                <tr >
                                    <th >{{__('common.vehicle_number')}}:</th>
                                    <td >{{ $sales->vehicle_number }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    @if($sales->customer)
                        <div style="border--left: 1px solid #d2d1d1;" class="col-md-push-4 col-md-4 p-5 ">
                            <h4>{{__('common.customer_info')}}:</h4>
                            <table class="w-100 customer-table-info">
                                <tr >
                                    <th class="w-25">{{__('common.name')}}: </th>
                                    <td class="w-75"> {{ $sales->customer->name }}</td>
                                </tr>
                                <tr >
                                    <th class="w-25">{{__('common.address')}}:</th>
                                    <td class="w-75">{{ $sales->customer->address }}</td>
                                </tr>
                                <tr >
                                    <th class="w-25">{{__('common.phone')}}:</th>
                                    <td class="w-75">{{ $sales->customer->phone }}</td>
                                </tr>
                            </table>
                        </div>
                    @endif
                </div>
                <!-- /.row -->

            <!-- Table row -->
            <div class="row">
                <div class="col-md-12 ">
                    <table style="width: 100%" class="sales_table">
                        <thead>
                        <tr class=" bg-gray">
                            <th>SL. No</th>
                            <th>{{__('common.item_name')}} </th>
                            <th>{{__('common.unit')}}</th>
                            <th>{{__('common.quantity')}}</th>
                            <th>{{__('common.price_per_qty')}}</th>
                            <th class="text-center">{{__('common.discount')}}</th>
                            <th class="text-right">{{__('common.total_price')}}</th>
                            <th class="text-right">{{__('common.purchase_price')}}</th>
                            <th class="text-right">{{__('common.profit')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $total = $profit = 0;
                        @endphp
                        @foreach( $sales->sale_details as $key=>$sale)

                            @php
                if(config('settings.sale_profit_by_quotation'))
                {
                    $purchase_price = $sale->quote_purchase_price;
                }else{
                    $purchase_price = $sale->purchase_price;
                }
                                    $profit_per = ($sale->qty*$sale->price)-($sale->qty*$purchase_price)-$sale->discount;
                                    $profit += $profit_per;

                                    $total += $sale->total_price;
                            @endphp

                        <tr>
                            <td>{{ $key+1 }}</td>
                            {{--<td>{{ $sale->item }}</td>--}}
                            <td>{{ $sale->item->item_name }}</td>
                            <td>{{ $sale->unit }}</td>
                            <td>{{ $sale->qty }}</td>
                            <td>{{ $sale->total_price>0 ? create_float_format($sale->total_price/$sale->qty, 3) : create_money_format($sale->price) }}</td>
                            <td class="text-center">{{ create_money_format($sale->discount) }}</td>
                            <td class="text-right">{{ create_money_format($sale->total_price-$sale->discount) }}</td>

                            <td class="text-right">{{ create_money_format($purchase_price*$sale->qty) }}</td>
                            <td class="text-right">{{ create_money_format($profit_per) }}</td>
                        </tr>

                        @endforeach

                        <tr>
                            <td colspan="4" rowspan="8"><b>{{__('common.notation')}}:</b> {{ $sales->notation }}</td>
                            <td colspan="3" rowspan="8" >
                                <h1 class="text-uppercase text-center"> {{ $sales->due> 0 ? trans('common.unpaid') : trans('common.paid') }}</h1>
                            </td>
                            <td class="text-right" >{{__('common.sub_total')}}:</td>
                            <td class="text-right" >{{ create_money_format($total) }}</td>
                        </tr>

                        @if($sales->shipping_charge>0)
                            <tr>
                                <td class="text-right" >{{__('common.shipping_charge')}}:</td>
                                <td class="text-right">{{ create_money_format($sales->shipping_charge) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="text-right" >{{__('common.total_amount')}}:</td>
                            <td class="text-right">{{ create_money_format($sales->grand_total) }}</td>
                        </tr>

                        <tr>
                            <td class="text-right" >{{__('common.discount')}} {{ $sales->discount_type=="fixed" ? "(Fixed)" : "(".$sales->discount."%)" }} :</td>
                            <td class="text-right">(-) {{ create_money_format($sales->total_discount) }}</td>
                        </tr>

                        <tr>
                            <td class="text-right" > {{__('common.amount_to_pay')}}:</td>
                            <td class="text-right">{{ create_money_format($sales->amount_to_pay) }}</td>
                        </tr>

                        <tr>
                            <td class="text-right" >{{__('common.profit_amount')}}:</td>
                            <td class="text-right">{{ create_money_format($profit) }}</td>
                        </tr>
                        <tr>
                            <td colspan="9" class="text-left">
                              <span class="text-bold"> {{__('common.in_words')}}: </span> {{ AmountInWords($profit) }}
                            </td>
                        </tr>
                        </tbody>
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

