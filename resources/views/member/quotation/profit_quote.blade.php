<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/13/2019
 * Time: 4:16 PM
 */

 $route =  \Auth::user()->can(['member.quotations.index']) ? route('member.quotations.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Quotation',
        'href' => $route,
    ],
    [
        'name' => 'show',
    ],
];

$data['data'] = [
    'name' => 'Quotation Memo no: '.$quotation['id'],
    'title'=> $quotation['ref'],
    'heading' => 'Quotation Profit',
];

?>


@extends('layouts.back-end.master', $data)

@section('contents')

    <div class="row">

        <div class="col-md-12  text-right">
            <div class="box">
                <div class="box-body">

                    <a href="{{ route('member.quotations.create') }}" class="btn btn-xs btn-warning"><i class="fa fa-plus"></i> Add Quotation</a>
                    {{--<a class="btn btn-xs btn-primary" href="{{ route('member.quotations.print', $quotation->id) }}?based=print"  id="btn-print"><i class="fa fa-print"></i> Print View</a>--}}
                    <a class="btn btn-xs btn-primary" href="{{ route('member.quotations.show', $quotation->id) }}" ><i class="fa fa-eye"></i> Show</a>

                    @if(\Illuminate\Support\Facades\Auth::user()->can(['super-admin', 'admin']))
                        <a href="{{ route('member.quotations.edit',  $quotation->id) }}" class="btn btn-xs btn-success">
                            <i class="fa fa-pencil"></i> Edit
                        </a>
                        <a href="javascript:void(0);"  class="btn btn-xs btn-danger delete-confirm" data-target="{{route('member.quotations.destroy', $quotation->id)}}">
                            <i class="fa fa-trash"></i> Delete
                        </a>
                    @endif

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


                        <div class="col-xs-4 p-5 text-left company-info">
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
                    <div style="" class="row invoice-info">

                        <div class="col-md-11 px-5">

                            <table border="0" width="100%" class="my-5">
                                <tbody>
                                <tr>
                                    <td><strong>REF: {{ $quotation->ref }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Date: {{ date_string_format($quotation->quote_date) }} </strong></td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <!-- /.row -->


                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive" id="custom-print">

                            <table border="1" class="table table-responsive table-bordered margin-top-30 float-right" >
                                <tbody>
                                <tr>
                                    <th class="text-center">SL No</th>
                                    <th class="text-center">Product Details</th>
                                    <th class="text-center" >Unit</th>
                                    <th class="text-center" >Quantity</th>
                                    <th class="text-center" >Price / Per Unit (BDT)</th>
                                    <th class="text-center">Total Price (BDT)</th>
                                </tr>
                                @php $total = 0; @endphp
                                @foreach($quotation->quotation_details as $key => $value)
                                    <tr>
                                        <td class="text-center">{{ sprintf("%02d", $key+1) }}</td>
                                        <td class="p-details">
                                            <strong>{{ $value->item->item_name }}</strong>
                                            @if($value->item->brand_id > 0)
                                                <br/>
                                                <strong>{{ "Brand- ".$value->item->brand->name}}</strong>
                                            @endif
                                            @if($value->item->description != null)
                                                <br/>
                                                {!! $value->item->description !!}
                                            @endif
                                        </td>

                                        <td class="text-center"> {{ $value->unit }}</td>
                                        <td class="text-center"> {{ $value->qty }}</td>
                                        <td class="text-right" > {{ create_money_format($value->price) }}</td>
                                        <td class="text-right" >{{ create_money_format($value->total_price) }}</td>

                                    </tr>
                                    @php
                                        $total += $value->total_price;
                                    @endphp
                                @endforeach
                                <tr>
                                    <td colspan="5" class="text-right" >Total Amount: </td>
                                    <td class="text-right" >{{ create_money_format($total) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-right" >Special Discount: </td>
                                    <td class="text-right" >{{ create_money_format($quotation->discount) }}</td>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right" >Grand Total : </th>
                                    <th class="text-right" >{{ create_money_format($total-$quotation->discount) }}</th>
                                </tr>

                                </tbody>
                            </table>

                            <div class="card-title col-md-12"> <h4>Quotation Transaction</h4> </div>

                            <table border="1" class="table table-responsive table-bordered margin-top-30 float-right" >
                                <tbody>

@php
       $saleID = explode(",",$quotation->sale_id);
       $purchaseID = explode(",",$quotation->purchase_id);

       $purchases = $quotation->purchases($purchaseID);
       $sales = $quotation->sales($saleID);

        $total = 0;
@endphp
                                @foreach($purchases as $key => $value)

                            <tr>
                                <th class="text-center" >Purchase </th>
                                <td  class="text-right" >{{$value->memo_no}} </td>
                                <td class="text-right" >
                                        (-)
                                        {{ create_money_format($value->amt_to_pay) }}
                                </td>

                            </tr>
                                    @php
                                        $total -= $value->amt_to_pay;
                                    @endphp
                                @endforeach
                                @foreach($sales as $key => $value)

                                    <tr>
                                        <th class="text-center" > Sales </th>
                                        <td class="text-right" >{{$value->sale_code}} </td>
                                        <td class="text-right" >
                                                (+)
                                                {{ create_money_format($value->amount_to_pay) }}
                                        </td>

                                    </tr>
                                    @php
                                        $total += $value->amount_to_pay;
                                    @endphp
                                @endforeach

                                @foreach($quotation->transactions as $key => $value)

                                    <tr>
                                        <td  class="text-center" > Transaction   <a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm" data-target="{{ route('member.quotations.delete-others-transaction', $value->id) }}">
                                                <i class="fa fa-times"></i> Delete Attach
                                            </a> </td>
                                        <td  class="text-right" >{{$value->transaction->transaction_code}} ({{$value->note}}) </td>
                                        <td class="text-right" >

                                            @if($value->transaction_type == "cr")
                                                (-)
                                                @php
                                                    $total -= $value->transaction->amount;
                                                @endphp
                                            @else

                                                (+)

                                                @php
                                                    $total += $value->transaction->amount;
                                                @endphp

                                            @endif

                                            {{ create_money_format($value->transaction->amount) }}
                                        </td>
                                    </tr>

                                @endforeach

                                <tr>
                                    <th colspan="2" class="text-center" >Quotation Profit </th>
                                    <td class="text-right" >
                                        {{ create_money_format($total) }}
                                    </td>
                                </tr>

                            </tbody>

                        </table>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
