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
    'heading' => 'REF: '.$quotation['ref'],
];

?>


@extends('layouts.back-end.master', $data)

@section('contents')

    <div class="row">

        <div class="col-md-12  text-right">
            <div class="box">
                <div class="box-body">

                    @if(\Illuminate\Support\Facades\Auth::user()->can(['member.quotations.create']))
                    <a href="{{ route('member.quotations.create') }}" class="btn btn-xs btn-warning"><i class="fa fa-plus"></i> Add Quotation</a>
                    @endif

                    {{--<a class="btn btn-xs btn-primary" href="{{ route('member.quotations.print', $quotation->id) }}?based=print"  id="btn-print"><i class="fa fa-print"></i> Print View</a>--}}

                    @if(\Illuminate\Support\Facades\Auth::user()->can(['member.quotations.profit']))
                    <a class="btn btn-xs btn-primary" href="{{ route('member.quotations.profit', $quotation->id) }}" ><i class="fa fa-eye"></i> Profit Quotation</a>
                    @endif


                    @if(\Illuminate\Support\Facades\Auth::user()->can(['member.quotations.print']))
                    <a class="btn btn-xs btn-primary" href="{{ route('member.quotations.print', $quotation->id) }}"><i class="fa fa-download"></i> Download</a>
                    <a class="btn btn-xs btn-primary" href="{{ route('member.quotations.print', $quotation->id) }}?based=without_pad"><i class="fa fa-download"></i> Download without Pad</a>
                    @endif

                    @if(\Illuminate\Support\Facades\Auth::user()->can(['member.quotations.edit']))

                    <a href="{{ route('member.quotations.edit',  $quotation->id) }}" class="btn btn-xs btn-success">
                        <i class="fa fa-pencil"></i> Edit
                    </a>

                    @endif

                    @if(\Illuminate\Support\Facades\Auth::user()->can(['member.quotations.destroy']))
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
                        <div class="col-md-12 text-right pr-5">
                            @php
                                $sales = explode(",",$quotation->sale_id);
                                $purchases = explode(",",$quotation->purchase_id);
                            @endphp

                            <b>Purchase:
                                @foreach($purchases as $value)
                                    <a target="_blank" class="ml-2"  href="{{ route('member.purchase.show', $value) }}">{{ $value }}</a>
                                @endforeach
                            </b> <br/>
                            <b>Sale:
                                @foreach($sales as $value)

                                    <a target="_blank" class="ml-2"  href="{{ route('member.sales.show', $value) }}">{{ $value }}</a>
                                @endforeach
                            </b>
                        </div>

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
                                </tbody>
                            </table>

                            <table border="0">
                                <tr>
                                    <td><strong>Date: {{ date_string_format($quotation->quote_date) }} </strong></td>
                                </tr>
                                <tr>
                                    <td class="pt-5">To</td>
                                </tr>
                                <tr>
                                    <td >The {{ $quotation->quoteCompany->designation }}</td>
                                </tr>
                                <tr>
                                    <td>{{ $quotation->quoteCompany->company_name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ $quotation->quoteCompany->address_1 }}</td>
                                </tr>
                                <tr>
                                    <td>{{ $quotation->quoteCompany->address_2 }}</td>
                                </tr>
                                @if($quotation->quote_attention_id>0)
                                <tr>
                                    <td style="padding:10px 0">Atten: {{ $quotation->quoteAttention->name }}, {{ $quotation->quoteAttention->designation }}, {{ $quotation->quoteAttention->department }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td  class="pt-2 pb-3"><strong>Subject: {{ $quotation->subject }}.</strong></td>
                                </tr>
                                <tr>
                                    <td>Dear Sir,</td>
                                </tr>
                                <tr>
                                    <td> @php print_r($quotation->starting_text) @endphp
                                    </td>
                                </tr>
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
                                        <th class="text-center">Product Image</th>
                                        <th class="text-center" >Unit</th>
                                        <th class="text-center" >Quantity</th>
                                        <th class="text-center" >Price/Per Unit (BDT)</th>
                                        <th class="text-center">Total Price (BDT)</th>
                                        <th class="text-center">Description</th>
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
                                            <td class="text-center"><img src="{{ $value->item->product_image ? $value->item->product_image_path : ''}}" style=" width:100px"></td>
                                            <td class="text-center"> {{ $value->unit }}</td>
                                            <td class="text-center"> {{ $value->qty }}</td>
                                            <td class="text-right" > {{ create_money_format($value->price) }}</td>
                                            <td class="text-right" >{{ create_money_format($value->total_price) }}</td>
                                            <td class="text-left"> {{ $value->description }}</td>
                                    </tr>
                                        @php
                                            $total += $value->total_price;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="6" class="text-right" >Total Amount: </td>
                                        <td class="text-right" >{{ create_money_format($total) }}</td>
                                        <td></td>
                                    </tr>
                                    @if($quotation->discount>0)
                                    <tr>
                                        <td colspan="6" class="text-right" >Special Discount: </td>
                                        <td class="text-right" >{{ create_money_format($quotation->discount) }}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-right" >Grand Total : </th>
                                        <th class="text-right" >{{ create_money_format($total-$quotation->discount) }}</th>
                                        <td></td>
                                    </tr>
                                        @endif
                                </tbody>

                            </table>
                            <p class="text-bold">In Word: {{ AmountInWords($total) }} </p>
                            <table border="0">

                                <tbody>
                                <tr>
                                    <td style="width: 120px; vertical-align: top;"> </td>
                                    <th class="py-3">  <u>TERMS & CONDITIONS</u> </th>
                                </tr>

                                @foreach($quotation->quoteTerms as $value)
                                    <tr>
                                        <td  style="width: 120px; vertical-align: top;"><strong>{{ $value->name }} </strong><span style="float:right;margin-right:10px">:</span></td>
                                        <td style="width: 500px; vertical-align: top;">
                                            {{ $quotation->quoteSubTerms($value->id, $quote_terms) ? $quotation->quoteSubTerms($value->id, $quote_terms)->name : ''}}
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                            <p style="margin-top:20px; text-align: left;"><strong>“Product price may change any time if there is any changes in Government policy.”</strong></p>
                            <p> @php print_r($quotation->ending_text) @endphp</p>

                            <p>For any further inquiries, please do not hesitate with <strong>{{ $quotation->quotingContactBy->name }}, {{ $quotation->quotingContactBy->designation }}, (Contact No: {{ $quotation->quotingContactBy->contact }}) </strong> of <strong> <span style="text-transform: uppercase !important;">{{ $quotation->company->company_name }}</span>.</strong>
                            </p>


                            <p>Thanking you in anticipation</p>
                            <p>Sincerely Yours <br>
                                On Behalf of {{ $quotation->company->company_name }}
                            </p>


                            @if($quotation->quotingBy->seal == "")

                                <div style="height: 100px; width: 70px;"></div>
                            @else
                                <div><img src="{{ public_path('storage/seal/'.$quotation->quotingBy->seal) }}" width="180px" style="margin-left: -20px; max-width:160px" alt=""></div>
                            @endif
                            <strong>{{ $quotation->quotingBy->name }}</strong>
                            <p>{{ $quotation->quotingBy->designation }} <br>
                                <span style="text-transform: uppercase !important;">{{ $quotation->company->company_name }} </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
