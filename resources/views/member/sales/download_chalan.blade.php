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

        @page {
            margin: {{$sales->company->pad_header_height}}px 25px {{$sales->company->pad_footer_height}}px;
        }

        header {
            position: fixed;
            top: -{{$sales->company->pad_header_height}}px;
            left: 0;
            right: 0;
            height: {{$sales->company->pad_header_height}}px;
        }
        footer {
            position: fixed;
            bottom: -{{$sales->company->pad_footer_height}}px;
            left: 0;
            right: 0;
            height: {{$sales->company->pad_footer_height}}px;
        }

    </style>
</head>
<body style=" font-size:14px;">

@php
    $headerImage = $sales->company->pad_header_image != "" ? $sales->company->pad_header_image_path : "";
    $footerImage = $sales->company->pad_footer_image != "" ? $sales->company->pad_footer_image_path : "";
@endphp

@if($pad_type == "with")

<header>
    <img src="{{ $headerImage }}" width="100%" style="max-height: {{$sales->company->pad_header_height}}px;" alt="">
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
                <td colspan="2" style="text-transform:uppercase; text-align:center; font-weight: bold; font-size: 20px; padding: 10px"> Challan </td>
            </tr>
            <tr>
                <td>REF: {{ $sales->quotation->ref }}</td>
                <td width="40%" style="text-align:right;">Date: {{ date_string_format($sales->date ) }} </td>
            </tr>
            </tbody>
        </table>
        <table border="0" style="margin-top: 15px;">
            <tr>
                <td>To</td>
            </tr>
            <tr>
                <td >The {{ $sales->quotation->quoteCompany->designation }}</td>
            </tr>
            <tr>
                <td>{{ $sales->quotation->quoteCompany->company_name }}</td>
            </tr>
            <tr>
                <td>{{ $sales->quotation->quoteCompany->address_1 }}</td>
            </tr>
            <tr>
                <td>{{ $sales->quotation->quoteCompany->address_2 }}</td>
            </tr>
            @if($sales->quotation->quote_attention_id>0)
                <tr>
                    <td style="padding:5px 0">Atten: {{ $sales->quotation->quoteAttention->name }}, {{ $sales->quotation->quoteAttention->designation }}, {{ $sales->quotation->quoteAttention->department }}</td>
                </tr>
            @endif

            <tr>
                <td>Dear Sir,</td>
            </tr>
            <tr>
                <td style="margin-bottom: 10px;">
                    With reference to the above, kindly acknowledge receipt of following products and return to us the duplicate copy of delivery Challan duly signed and sealed with date.
                </td>
            </tr>
        </table>
        @if(!empty($sales->memo_no) ||  !empty($sales->notation))
        <p style="text-align: center; margin: 0;">
            {{ $sales->memo_no ? "PO# ".$sales->memo_no : "" }}
            {{ (!empty($sales->memo_no) &&  !empty($sales->notation )) ? "," : "" }}
            {{ $sales->notation }}
        </p>
        @endif
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
            </tr>
            </thead>
            <tbody>
            @foreach($sales->sale_details as $key => $value)
                <tr>
                    <td class="text-center"  width="40px">{{ sprintf("%02d", $key+1) }}</td>
                    <td>
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
                </tr>

            @endforeach


            </tbody>
        </table>

        <table width="100%" style="margin-top: 100px;">
            <tr>
                <td  style="text-align: left; text-transform: uppercase; vertical-align: top;">

                    <p style="font-weight:bold; text-align: left; text-transform: uppercase;  margin: 0; padding: 0">   (For {{ $sales->quotation->company->company_name }}) </p>
                    <p style=" font-weight:bold;text-transform: uppercase; margin: 0; padding: 0; margin-left: 60px !important; ">Signature</p>

                    @if($sales->quotation->quotingBy->seal == "" || $pad_type == "without")

                        <div style="height: 100px; width: 70px;"></div>
                    @else
                        <div style="width: 100%;"><img src="{{ asset('storage/app/public/seal/'.$sales->quotation->quotingBy->seal) }}" width="70px" style="margin-left: 60px !important; max-width:70px" alt=""></div>
                    @endif


                    <p style="text-transform: capitalize; margin: 1px; padding-top: 0">Prepared By </p>
                    <p style="text-transform: capitalize; margin: 1px; padding-top: 0">{{ $sales->creator->full_name }} </p>
                    <p style="text-transform: capitalize; margin: 1px; padding-top: 0">
                        Accounts & Finance - Manager
                        <br>
                        <span style="font-weight:bold; text-transform: uppercase !important;">{{ $sales->company->company_name }} </span>


                </td>
                <th   style="text-align: right;text-transform: uppercase;  vertical-align: top;">
                    Receiver's
                </th>
            </tr>
        </table>
        <br/>

    </div>

</main>
</body>
</html>
