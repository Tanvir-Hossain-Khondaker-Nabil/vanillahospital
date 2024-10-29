<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/25/2019
 * Time: 10:24 AM
 */

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
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Invoice',
    'title'=> 'Invoice',
    'heading' => 'Invoice',
];

?>
@extends('layouts.back-end.master', $data)


@section('contents')

    <div class="row text-right">
        <div class="col-md-12">
            <div class="box">
                @include('common._alert')
                <div class="box-body">
                    <a href="{{ route('member.dealer_sales.print_sale', $sales->id) }}" class="btn btn-xs btn-primary" id="btn-print"><i class="fa fa-print"></i> Print</a>

                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['admin','dealer']))

                    <a href="{{ route('member.dealer_sales.edit', $sales->id) }}" class="btn btn-xs btn-success">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                @endif
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
                    <div class="col-xs-4 text-center py-5">
                        @php print_r($sale_barcode) @endphp<br>
                        <b style="margin-left: 13px;">{{ $sales->sale_code }}</b><br>
                    </div>
                    <div class="col-xs-4 text-right company-info">
                        <h2 style="margin: 10px auto!important">{{ $company_name }}</h2>
                        <div class="info">
                            <p >{{ $company_address }} </p>
                            <p >{{ $company_city.($company_country ? ", ".$company_country : "") }}</p>
                            <p >Phone : {{ $company_phone }}</p>
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
                                <th >Date: </th>
                                <td >{{ $sales->date_format }}</td>
                            </tr>
                            <tr >
                                <th >Memo No:</th>
                                <td >{{ $sales->memo_no }}</td>
                            </tr>
                            @if( $sales->chalan_no )
                                <tr >
                                    <th >Chalan No:</th>
                                    <td >{{ $sales->chalan_no }}</td>
                                </tr>
                            @endif
                            <tr >
                                <th >Account:</th>
                                <td >{{ $sales->cash_or_bank->title }}</td>
                            </tr>
                            <tr >
                                <th >Payment Method:</th>
                                <td >{{ $sales->payment_method->name }}</td>
                            </tr>
                            @if( $sales->vehicle_number )
                                <tr >
                                    <th >Vehicle Number:</th>
                                    <td >{{ $sales->vehicle_number }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    @if($sales->customer)
                        <div style="border--left: 1px solid #d2d1d1;" class="col-md-push-4 col-md-4 p-5 ">
                            <h4>Customer Info:</h4>
                            <table class="w-100 customer-table-info">
                                <tr >
                                    <th class="w-25">Name: </th>
                                    <td class="w-75"> {{ $sales->customer->name }}</td>
                                </tr>
                                <tr >
                                    <th class="w-25">Address:</th>
                                    <td class="w-75">{{ $sales->customer->address }}</td>
                                </tr>
                                <tr >
                                    <th class="w-25">Phone:</th>
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
                            {{--<th>Item Code</th>--}}
                            <th>Item Name </th>
                            {{--<th>Area</th>--}}
                            <th class="text-center">Qty</th>
                            <th class="text-center">Unit</th>
                            {{--<th class="text-center">Carton</th>--}}
                            {{--<th class="text-center">Free</th>--}}
                            <th class="text-center">Total Qty</th>
                            <th class="text-center"> Price</th>
                            {{--<th class="text-center">Trade Price</th>--}}
                            <th class="text-center">Total Price</th>
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
{{--                            <td>{{ $sale->area_id ? $sale->area->display_name_bd : "" }}</td>--}}
                            <td class="text-center">{{ $sale->qty }}</td>
                            <td class="text-center">{{ $sale->unit }}</td>
                            {{--<td class="text-center">{{ $sale->carton > 0 ?  $sale->carton : ''}}</td>--}}
                            {{--<td class="text-center">{{ $sale->free > 0 ?  $sale->free : '' }}</td>--}}
                            <td class="text-center">{{ $sale->qty+$sale->free }}</td>
                            <td class="text-center">{{ $sale->total_price>0 ? create_float_format($sale->total_price/$sale->qty, 3) : create_money_format($sale->price) }}</td>
{{--                            <td class="text-center">{{ $sale->trade_price }}</td>--}}
                            <td class="text-right">{{ $sale->total_price>0 ? create_money_format($sale->total_price) : create_money_format($sale->qty*$sale->price) }}</td>
                        </tr>

                            @php
                            $total += $sale->total_price;
                            @endphp
                        @endforeach

                        <tr>
                            <td colspan="3" rowspan="9"><b>Notation:</b> {{ $sales->notation }}</td>
                            <td colspan="2" rowspan="9" >
                                <h1 class="text-uppercase text-center"> {{ $sales->due> 0 ? "Unpaid" : "Paid" }}</h1>
                            </td>
                            <td class="text-right" >Sub Total:</td>
                            <td class="text-right" >{{ create_money_format($total) }}</td>
                        </tr>
                        <tr>
                            <td class="text-right" >Discount {{ $sales->discount_type=="fixed" ? "(Fixed)" : "(".$sales->discount."%)" }} :</td>
                            <td class="text-right">(-) {{ create_money_format($sales->total_discount) }}</td>
                        </tr>
{{--                        <tr>--}}
{{--                            <td class="text-right" >Shipping Charge:</td>--}}
{{--                            <td class="text-right">{{ create_money_format($sales->shipping_charge) }}</td>--}}
{{--                        </tr>--}}
                        <tr>
                            <td class="text-right" >Transport Cost</td>
                            <td class="text-right" > {{ create_money_format($sales->transport_cost) }} </td>
                        </tr>
                        <tr>
                            <td class="text-right" >Unload Cost</td>
                            <td class="text-right" > {{ create_money_format($sales->unload_cost) }} </td>
                        </tr>
                        <tr>
                            <td class="text-right" >Total Amount:</td>
                            <td class="text-right">{{ create_money_format($sales->grand_total) }}</td>
                        </tr>
                        <tr>
                            <td class="text-right" > Amount to Pay:</td>
                            <td class="text-right">{{ create_money_format($sales->amount_to_pay) }}</td>
                        </tr>
                        <tr>
                            <th class="text-right">Paid Amount:</th>
                            <th class="text-right">{{ create_money_format($sales->paid_amount) }} </th>
                        </tr>
                        <tr>
                            <td class="text-right" >Due:</td>
                            <td class="text-right"> {{ create_money_format($sales->due) }}</td>
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

