<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/13/2019
 * Time: 4:16 PM
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
    'name' => 'Purchase Memo no: '.$purchase['memo_no'],
    'title'=>'Memo no: '.$purchase['memo_no'],
    'heading' => 'Purchase Memo No: '.$purchase['memo_no'],
];

?>


@extends('layouts.back-end.master', $data)

@section('contents')

    <div class="row text-right">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <a class="btn btn-xs btn-primary" href="{{ route('member.purchase.print_purchases', $purchase->id) }}" id="btn-print"><i class="fa fa-print"></i> Print</a>

                    <a href="{{ route('member.purchase_return.edit', $purchase->id) }}" class="btn btn-xs btn-info">
                        <i class="fa fa-reply"></i> Purchase Return
                    </a>

                    <a href="{{ route('member.purchase.edit',  $purchase->id) }}" class="btn btn-xs btn-success">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                    <a href="{{ route('member.purchase.create') }}" class="btn btn-xs btn-warning"><i class="fa fa-plus"></i> Add Purchase</a>
                    <a href="javascript:void(0);"  class="btn btn-xs btn-danger delete-confirm" data-target="{{route('member.transaction.destroy', $purchase->transaction->id)}}">
                        <i class="fa fa-trash"></i> Delete
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

                    <div class="row">
                        <div class="col-xs-4 text-left">
                            @if( isset($company_logo))
                                <img src="{{ $company_logo }}" width="100px;"/>
                            @endif
                        </div>
                        <div class="col-xs-4 text-center py-5">
                            @php print_r($purchase_barcode) @endphp<br>
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
                                    <td >{{ $purchase->date_format }}</td>
                                </tr>
                                <tr >
                                    <th >Invoice No:</th>
                                    <td >{{ $purchase->memo_no }}</td>
                                </tr>
                                <tr >
                                    <th >Memo No:</th>
                                    <td >{{ $purchase->invoice_no }}</td>
                                </tr>
                                @if( $purchase->chalan_no )
                                    <tr >
                                        <th >Chalan No:</th>
                                        <td >{{ $purchase->chalan_no }}</td>
                                    </tr>
                                @endif
                                <tr >
                                    <th >Account:</th>
                                    <td >{{ $purchase->cash_or_bank->title }}</td>
                                </tr>
                                <tr >
                                    <th >Payment Method:</th>
                                    <td >{{ $purchase->payment_method->name }}</td>
                                </tr>
{{--                                @if( $purchase->vehicle_number )--}}
                                    <tr >
                                        <th >Vehicle Number:</th>
                                        <td >{{ $purchase->vehicle_number }}</td>
                                    </tr>
{{--                                @endif--}}
                            </table>
                        </div>
                        @if($purchase->supplier)
                            <div style="border--left: 1px solid #d2d1d1;" class="col-md-push-4 col-md-4 p-5 ">
                                <h4>Supplier Info:</h4>
                                <table class="w-100 customer-table-info">
                                    <tr >
                                        <th class="w-25">Name: </th>
                                        <td class="w-75"> {{ $purchase->supplier->name }}</td>
                                    </tr>
                                    <tr >
                                        <th class="w-25">Address:</th>
                                        <td class="w-75">{{ $purchase->supplier->address }}</td>
                                    </tr>
                                    <tr >
                                        <th class="w-25">Phone:</th>
                                        <td class="w-75">{{ $purchase->supplier->phone }}</td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                    </div>
                    <!-- /.row -->


                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive" id="custom-print">

                            <table class="table table-responsive table-bordered margin-top-30 float-right" >
                                <tbody>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Description</th>
                                        <th>Unit</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th class="text-center">Total Price</th>
                                        <th class="text-center">Add Serial</th>
                                    </tr>
                                    @php $total = 0; @endphp
                                    @foreach($purchase->purchase_details as $value)
                                    <tr>
                                        <td>{{ $value->item->item_name }}</td>
                                        <td>{{ $value->description }}</td>
                                        <td>{{ $value->unit }}</td>
                                        <td> {{ $value->qty }}</td>
                                        <td> {{ create_money_format($value->price) }}</td>
                                        <td class="text-right" >{{ create_money_format($value->total_price) }}</td>
                                        <td class="text-center"> <a href="{{ route('member.purchase.item_serial', [ $purchase->id, $value->item_id]) }}" class="btn btn-primary btn-sm"> <i class="fa fa-plus-circle"></i> Add Serial</a> </td>
                                    </tr>
                                        @php
                                            $total += $value->total_price;
                                        @endphp
                                    @endforeach
                                </tbody>

                            </table>
                            <table class="margin-top-30" style="width: 700px; float: left;">
                                <tr>
                                    <th> Notes: </th>
                                </tr>
                                <tr>
                                    <td>
                                        @php print_r($purchase->notation) @endphp
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                       @if($purchase->file)
                                            <a href="{{ $purchase->attach_file_path }}" target="_blank" class="text-bold"> Check your Attached File</a>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            <table class=" margin-top-30 pull-right" width="400px">

                                <tr>
                                    <th class="text-right" colspan="5"> Sub Total</th>
                                    <th class="text-right" > {{ create_money_format($total) }} </th>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">Transport Cost</td>
                                    <td class="text-right" > {{ create_money_format($purchase->transport_cost) }} </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">Unload Cost</td>
                                    <td class="text-right" > {{ create_money_format($purchase->unload_cost) }} </td>
                                </tr>
                                <tr>
                                    <th class="text-right" colspan="5">Grand Total</th>
                                    <th class="text-right" > {{ create_money_format($purchase->total_amount) }} </th>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">Discount ({{ $purchase->discount_type == "Percentage" ? $purchase->discount."%" : $purchase->discount_type }})</td>
                                    <td class="text-right" > {{ $purchase->total_discount > 0 ? "- (".create_money_format($purchase->total_discount).")" : create_money_format(0.00) }} </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">Amount To Pay</td>
                                    <td class="text-right" > {{ create_money_format($purchase->amt_to_pay) }} </td>
                                </tr>
                                <tr>
                                    <th class="text-right" colspan="5">Paid Amount</th>
                                    <th class="text-right" > {{ create_money_format($purchase->paid_amount) }} </th>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">Due Amount</td>
                                    <td class="text-right" > {{ create_money_format($purchase->due_amount) }} </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
