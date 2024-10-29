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

        <table width="100%"  class="table_one" border="0">
            <tr>
                <td colspan="2" style="text-align:center; font-weight: bold; font-size: 16px;">Invoice Bill </td>
            </tr>
            @if($sales->quotation_id)
                <tr>
                    <td ><b> REF</b></td>
                    <td colspan="5"><b>: {{ $sales->quotation->ref }}</b></td>
                </tr>
            @endif
            <tr>
                <td ><b>Sale Code</b></td>
                <td colspan="3"><b>: {{ $sales->sale_code }}</b></td>
                <td ><b>Invoice No</b></td>
                <td ><b>: {{ "INV-".$sales->id }}</b></td>
            </tr>
            {{--                <tr>--}}
            {{--                    <td>Memo No</td>--}}
            {{--                    <td>: {{ $sales->memo_no }}</td>--}}
            {{--                    <td>Chalan No</td>--}}
            {{--                    <td>: {{ $sales->chalan_no }}</td>--}}
            {{--                    <td>Vehicle Number</td>--}}
            {{--                    <td>: {{ $sales->vehicle_number }}</td>--}}
            {{--                </tr>--}}
            <tr>
                <td>Bill Date</td>
                <td>: {{ $sales->date_format }}</td>
                <td>Time</td>
                <td>: {{ create_time_format($sales->created_at)   }}</td>
                <td> Creator Name</td>
                <td>: {{ $sales->creator->full_name }}</td>
            </tr>
        </table>

        @if($sales->customer)
        <table  width="100%"  border="0" style="margin-top: 15px;">
            <tr>
                <td colspan="2" class="text-left text-bold">Customer Info: </td>
            </tr>
            <tr >
                <td width="25%">Name: </td>
                <td colspan="3"> {{ $sales->customer->name }}</td>
            </tr>
            <tr >
                <td width="25%">Phone:</td>
                <td>{{ $sales->customer->phone }}</td>
            </tr>
            <tr >
                <td width="25%">Address:</td>
                <td >{{ $sales->customer->address }}</td>
            </tr>
        </table>
        @endif



        <table style="margin-top: 15px;" class="table" border="1" width="100%" cellpadding="0" cellspacing="0">
            <thead>
            <tr>
                <th>SL No</th>
                <th>Product Details</th>
                <th>Unit</th>
                <th>Qty</th>
                <th>Price/Qty <br/> (BDT)</th>
                <th>Total Price <br/> (BDT)</th>
            </tr>
            </thead>
            <tbody>
            @php $total = 0; @endphp
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
                $grand = $total-$sales->discount;
            @endphp

            @if($sales->discount>0)
                <tr>
                    <th colspan="{{$hasImages>0 ? 6 : 5}}" class="text-right" >Total Amount: </th>
                    <th class="text-right" >{{ create_money_format($total) }}</th>
                </tr>

                <tr>
                    <td colspan="{{$hasImages>0 ? 6 : 5}}" class="text-right" >Special Discount: </td>
                    <td class="text-right" >{{ create_money_format($sales->discount) }}</td>
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



        <table width="100%" style="margin-top: 100px;">
            <tr>
                <th  style="text-align: left; text-transform: uppercase; vertical-align: middle;">
                    <p style="text-align: left; text-transform: uppercase;  margin: 0; padding: 0">   (For {{ $company_name }}) </p>
                    <p style=" text-transform: uppercase; margin: 0; padding: 0; margin-left: 60px !important; ">Signature</p></th>
                <th   style="text-align: right;text-transform: uppercase;  vertical-align: middle;">Receiver's</th>
            </tr>
        </table>
        <br/>


    </div>

</main>
</body>
</html>
