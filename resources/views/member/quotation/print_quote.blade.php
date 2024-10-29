<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $report_title }}</title>

    <style type="text/css">
        .text-center{
            text-align: center !important;
        }

        .text-right{
            text-align: right !important;
        }

        .text-left{
            text-align: left !important;
        }

        .text-bold{
            font-weight: bold;
        }
        .table{
            /*border:  1px solid #000 !important;*/
        }

        /*.dual-underline {*/
            /*text-decoration-line: underline;*/
            /*text-decoration-style: double;*/
        /*}*/

        .dual-underline {
            border-bottom: double 3px;
            display: inline;
            padding: 5px;
        }

        .table tbody tr td, .table thead tr th, .table tbody tr th, .table thead tr td {
            padding: 3px !important;
            /*border: 1px solid #000 !important;*/
        }
        .table tbody tr td.p-detail p{
            margin-bottom:3px !important;
            padding-bottom:3px !important;
        }
        
         table { page-break-inside:auto }
         tr    { page-break-inside:avoid; page-break-after:auto }
            

        @page {
            margin: {{$quotation->company->pad_header_height}}px 25px {{$quotation->company->pad_footer_height}}px;
        }

        header {
            position: fixed;
            top: -{{$quotation->company->pad_header_height}}px;
            left: 0;
            right: 0;
            height: {{$quotation->company->pad_header_height}}px;
        }
        footer {
            position: fixed;
            bottom: -{{$quotation->company->pad_footer_height}}px;
            left: 0;
            right: 0;
            height: {{$quotation->company->pad_footer_height}}px;
        }

    </style>
</head>
<body style=" font-size:14px;">

@php
    $headerImage = $quotation->company->pad_header_image != "" ? $quotation->company->pad_header_image_path : "";
    $footerImage = $quotation->company->pad_footer_image != "" ? $quotation->company->pad_footer_image_path : "";
@endphp

@if($based != "without_pad")

<header>
    <img src="{{ $headerImage }}" width="100%" style="max-height: {{$quotation->company->pad_header_height}}px;" alt="">
</header>

<footer>
    <img src="{{ $footerImage }}" width="100%" alt="">
</footer>

@endif

<main>
    <div style="margin-left: auto;margin-right: auto; margin-top:0;width: 90%;">

        <table border="0" width="100%">
            <tbody>
            <tr>
                <td><strong>REF: {{ $quotation->ref }}</strong></td>
                <td width="40%" style="text-align:right;"><strong>Date: {{ date_string_format($quotation->quote_date) }} </strong></td>
            </tr>
            </tbody>
        </table>
        <table border="0">
            <tr>
                <td>To</td>
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
                <td style="padding:5px 0">Atten: {{ $quotation->quoteAttention->name }}, {{ $quotation->quoteAttention->designation }}, {{ $quotation->quoteAttention->department }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding-bottom:6px;"><strong>Subject: {{ $quotation->subject }}.</strong></td>
            </tr>
            <tr>
                <td>Dear Sir,</td>
            </tr>
            <tr>
                <td style="margin-bottom: 10px;"> @php print_r($quotation->starting_text) @endphp
                </td>
            </tr>
        </table>
        <table  class="table" border="1" width="100%" cellpadding="0" cellspacing="0">
            <thead>
            <tr>
                <th>SL No</th>
                <th>Product Details</th>
                @if($hasImages>0)
                <th>Product Image</th>
                @endif
                <th>Unit</th>
                <th>Qty</th>
                <th>Price/Qty <br/> (BDT)</th>
                <th>Total Price <br/> (BDT)</th>
            </tr>
            </thead>
            <tbody>
            @php $total = 0; @endphp
            @foreach($quotation->quotation_details as $key => $value)
                <tr>
                    <td class="text-center"  width="40px">{{ sprintf("%02d", $key+1) }}</td>
                    <td class="p-detail">
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
                    @if($hasImages>0)
                    <td class="text-center">
                        @if($value->item->product_image != "")
                        <img src="{{$value->item->product_image_path}}" style="width:120px;">
                        @endif
                    </td>
                    @endif
                    <td class="text-center"  width="60px"> {{ $value->unit }}</td>
                    <td class="text-center" width="50px"> {{ $value->qty }}</td>
                    <td class="text-right" width="60px" > {{ create_money_format($value->price) }}</td>
                    <td class="text-right" width="80px" >{{ create_money_format($value->total_price) }}</td>
                </tr>
                @php
                    $total += $value->total_price;
                @endphp
            @endforeach

            @php
                $grand = $total-$quotation->discount;
            @endphp

            @if($quotation->discount>0)
                <tr>
                    <th colspan="{{$hasImages>0 ? 6 : 5}}" class="text-right" >Total Amount: </th>
                    <th class="text-right" >{{ create_money_format($total) }}</th>
                </tr>

                <tr>
                    <td colspan="{{$hasImages>0 ? 6 : 5}}" class="text-right" >Special Discount: </td>
                    <td class="text-right" >{{ create_money_format($quotation->discount) }}</td>
                </tr>
                <tr>
                    <th colspan="{{$hasImages>0 ? 6 : 5}}" class="text-right" >Grand Total : </th>
                    <th class="text-right " ><div class="dual-underline">{{ create_money_format($grand) }}</div></th>
                </tr>
            @else
                <tr>
                    <th colspan="{{$hasImages>0 ? 6 : 5}}" class="text-right" >Total Amount: </th>
                    <th class="text-right" ><div class="dual-underline">{{ create_money_format($total) }}</div></th>
                </tr>
            @endif
            </tbody>
        </table>
        <p class="text-bold">In Word: {{ AmountInWords($grand) }}</p>
        <table border="0">

            <tbody>
            <tr>
                <td style="width: 120px; vertical-align: top;"> </td>
                <td>  <caption><h3 style="margin: 1px 0;font-size:12px;"><u>TERMS & CONDITIONS:</u></h3></caption> </td>
            </tr>

            @foreach($quotation->quoteTerms as $value)
            <tr style="page-break-after: always;">
                <td  style="width: 120px; vertical-align: top;"><strong>{{ $value->name }} </strong><span style="float:right;margin-right:10px">:</span></td>
                <td style="padding-bottom: 5px; width: 500px; vertical-align: top;">
                    {{ $quotation->quoteSubTerms($value->id, $quote_terms) ? $quotation->quoteSubTerms($value->id, $quote_terms)->name : ''}}
                </td>
            </tr>
            @endforeach

            </tbody>
        </table>
        <p style="text-align: center;"><strong>“Product price may change any time if there is any changes in Government
                policy.”</strong></p>
        <p> @php print_r($quotation->ending_text) @endphp</p>

        <p>For any further inquiries, please do not hesitate with <strong>{{ $quotation->quotingContactBy->name }}, {{ $quotation->quotingContactBy->designation }}, (Contract No: {{ $quotation->quotingContactBy->contact }}) </strong> of <strong> <span style="text-transform: uppercase !important;">{{ $quotation->company->company_name }}</span>.</strong>
        </p>


        <p>Thanking you in anticipation</p>
        <p>Sincerely Yours <br>
            On Behalf of {{ $quotation->company->company_name }}
        </p>


        @if($quotation->quotingBy->seal == "" || $based == "without_pad")

        <div style="height: 100px; width: 70px;"></div>
        @else
         <div><img src="{{ asset('storage/app/public/seal/'.$quotation->quotingBy->seal) }}" width="70px" style="margin-left: -5px; max-width:70px" alt=""></div>
        @endif
        <strong>{{ $quotation->quotingBy->name }}</strong>
        <p>{{ $quotation->quotingBy->designation }} <br>
            <span style="text-transform: uppercase !important;">{{ $quotation->company->company_name }} </span>
        </p>
    </div>

</main>
</body>
</html>
