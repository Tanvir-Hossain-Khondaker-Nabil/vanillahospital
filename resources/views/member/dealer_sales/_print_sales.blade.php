<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 8/8/2019
 * Time: 2:28 PM
 */

?>


    <!DOCTYPE html>
<html>
<head>
    <title>Pos Invoice</title>
    <style type="text/css">
        #invoice-POS {
            /*box-shadow: 0 0 1in -0.25in rgb(227, 215, 215);*/
            padding: 2mm;
            margin: 0 auto;
            width: 100%;
            background: #fff;
        }
        #invoice-POS ::selection {
            background: #f31544;
            color: #fff;
        }
        #invoice-POS ::moz-selection {
            background: #f31544;
            color: #fff;
        }
        #invoice-POS h1 {
            font-size: 1.5em;
            color: #000;
        }
        #invoice-POS h2 {
            font-size: 11px;
            text-align: center;
        }
        #invoice-POS h3 {
            font-size: 1.2em;
            font-weight: 300;
            line-height: 2em;
        }
        #invoice-POS p {
            font-size: 0.7em;
            color: #000;
            line-height: 1.2em;
        }
        #invoice-POS #top,
        #invoice-POS #mid,
        #invoice-POS #bot {
            /* Targets all id with 'col-' */
            border-bottom: 1px solid #eee;
        }
        #invoice-POS #top {
            min-height: 100px;
        }
        #invoice-POS #mid {
            min-height: 80px;
        }
        #invoice-POS #bot {
            min-height: auto !important;
            padding-bottom: 10px

        }
        #top h2{
            font-size: 24px;
        }
        #invoice-POS #top .logo {
            height: 60px;
            width: 60px;
            background: url({{$company_logo}}}) no-repeat;
            background-size: 60px 60px;
        }
        #invoice-POS .clientlogo {
            float: left;
            height: 60px;
            width: 60px;
            background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
            background-size: 60px 60px;
            border-radius: 50px;
        }
        #invoice-POS .info {
            display: block;
            margin-left: 0;
        }
        #invoice-POS .title {
            float: right;
        }
        #invoice-POS .title p {
            text-align: right;
        }
        #invoice-POS table {
            width: 100%;
            border-collapse: collapse;
        }
        #invoice-POS .tabletitle {
            font-size: 0.5em;
            background: #eee;
        }
        #invoice-POS .service {
            border-bottom: 1px solid #eee;
        }
        #invoice-POS .item {

        }
        #invoice-POS .itemtext {
            font-size:10px;
        }
        #invoice-POS #legalcopy {
            margin-top: 5mm;
        }

        .table_one{
            margin-top: 10px;
            width: 100%;
        }
        .table_one tr td{
            font-size: 12px;
            text-align: left;
            width: 25%;
        }
        .table_one tr td:nth-child(odd){
            font-size: 11px;
            text-align: left;
            width: 13%!important;
            padding: 4px;
        }
        .table_one tr td:nth-child(even){
            font-size: 11px;
            text-align: left;
            width: 20%!important;
            padding: 4px;
        }
        .table_info {
            margin-top: 10px;
        }
        .table_info tr td{
            font-size: 12px;
            text-align: center;
            letter-spacing: 0.5px;
        }

        .table_info_last {
            margin-top: 10px;
        }
        .table_info_last tr td{
            font-size: 11px;
            text-align: center;
            letter-spacing: 0px;
        }
        .item_table tr td:last-child{
            text-align: right;
            padding-right: 5px;
        }
        .item_table tr td:first-child{
            width: 5%;
            padding-left: 5px;
        }

        .item_table tr td{
            text-align: center;
        }
        .text-right{
            text-align: right !important;
        }
        .new_title td{
            font-size: 10px;
            text-align: right!important;
            color: #000;
            padding-top: 5px;

        }

        .payment_table{
            width: 60%!important;
            text-align: center;
            margin: 0 auto;
        }
        .payment_table tr td:first-child{
            font-size: 10px;
            text-align: left!important;
            color: #000;
            padding-top: 5px;

        }

        .payment_table tr td:last-child{
            font-size: 10px;
            text-align: right!important;
            color: #000;
            padding-top: 5px;

        }
    </style>


</head>
<body>
<div id="invoice-POS">

    <center id="top">
        {{--<div class="logo">--}}

        {{--</div>--}}
        <h2 style="margin: 10px auto!important">{{ $company_name }}</h2>
        <div class="info">
            <table class="table_info">
                <tr><td  colspan="3">{{ $company_address.", ".$company_city.($company_country ? ", ".$company_country : "") }}</td></tr>
                <tr><td  colspan="3">Phone : {{ $company_phone }}</td></tr>
                <tr><td  colspan="3"><b>{{ $company_name }} Cash Memo</b></td></tr>
            </table>
        </div>
        <!--End Info-->
    </center>
    <!--End InvoiceTop-->

    <div id="mid">
        <div class="info">
            <table class="table_one">
                <tr>
                    <td ><b>Sale Code</b></td>
                    <td colspan="3"><b>: INV\{{ $sales->sale_code }}</b></td>
                </tr>
                <tr>
                    <td>Memo No</td>
                    <td>: {{ $sales->memo_no }}</td>
                    <td>Bill Date</td>
                    <td>: {{ $sales->date_format }}</td>
                </tr>
                <tr>
                    <td>Time</td>
                    <td>: {{ $sales->created_at }}</td>
                    <td> Creator Name</td>
                    <td>: {{ $sales->creator->full_name }}</td>
                </tr>
            </table>
        </div>
    </div>
    <!--End Invoice Mid-->

    @if($sales->customer)
    <div id="mid">
        <div class="info">
            <table class="table_one">

                <tr>
                    <td>Customer Name</td>
                    <td>: {{ $sales->customer->name }}</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>: {{ $sales->customer->address }}</td>
                </tr>
                <tr>
                    <td> Phone</td>
                    <td>: {{ $sales->customer->phone }}</td>
                </tr>
            </table>
        </div>
    </div>
    @endif
    <!--End Invoice Mid-->

    <div id="bot">

        <div id="table">
            <table class="item_table">
                <tr class="tabletitle">
                    <td class="item">
                        <h2>SL</h2>
                    </td>
                    <td class="Hours">
                        <h2>Product Name</h2>
                    </td>
                    <td class="Rate">
                        <h2>Qty</h2>
                    </td>
                    {{--<td class="Rate">--}}
                        {{--<h2>Carton</h2>--}}
                    {{--</td>--}}
                    {{--<td class="Rate">--}}
                        {{--<h2>Free</h2>--}}
                    {{--</td>--}}
                    <td class="Rate">
                        <h2>Total Qty</h2>
                    </td>
                    <td class="Rate">
                        <h2> Price</h2>
                    </td>
                    {{--<td class="Rate">--}}
                        {{--<h2>Trade Price</h2>--}}
                    {{--</td>--}}
                    <td class="Rate"  style="text-align: right !important;">
                        <h2>Total Price</h2>
                    </td>

                </tr>
                @php
                    $total = 0;
                @endphp
                @foreach( $sales->sale_details as $key=>$sale)
                    <tr class="service">
                        <td class="tableitem">
                            <p class="itemtext">{{ $key+1 }}</p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext">{{ $sale->item->item_name }}</p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext">{{ $sale->qty}}</p>
                        </td>
                        {{--<td class="tableitem">--}}
                            {{--<p class="itemtext">{{ $sale->carton }}</p>--}}
                        {{--</td>--}}
                        {{--<td class="tableitem">--}}
                            {{--<p class="itemtext">{{ $sale->free }}</p>--}}
                        {{--</td>--}}
                        <td class="tableitem">
                            <p class="itemtext">{{ $sale->qty+$sale->free }}</p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext">{{ $sale->total_price>0 ? create_money_format($sale->total_price/$sale->qty, 3) : create_money_format($sale->price) }}</p>
                        </td>
                        {{--<td class="tableitem">--}}
                            {{--<p class="itemtext">{{ create_money_format($sale->trade_price) }}</p> </td>--}}
                        <td class="tableitem">
                            <p class="itemtext">{{ create_money_format($sale->qty*$sale->price) }}</p>
                        </td>
                    </tr>

                    @php
                        $total += $sale->qty*$sale->price;
                    @endphp
                @endforeach


                <tr class="service">

                    <td style="text-align: right;" colspan="5" class="tableitem">
                        <p class="itemtext">Sub Total</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">{{ create_money_format($total) }}</p>
                    </td>
                </tr>



                <tr class="new_title">

                    <td  colspan="5" >
                        Discount
                    </td>
                    <td  >
                        (-) {{ create_money_format($sales->total_discount) }}
                    </td>
                </tr>

                {{--                <tr class="new_title">--}}

                {{--                    <td  colspan="4" >--}}
                {{--                        VAT--}}
                {{--                    </td>--}}
                {{--                    <td  >--}}
                {{--                        0.00--}}
                {{--                    </td>--}}
                {{--                </tr>--}}
                <tr class="new_title">

                    <td  colspan="5" >
                        Net Amount
                    </td>
                    <td  >
                        {{ create_money_format($sales->grand_total) }}
                    </td>
                </tr>

                <tr class="new_title">

                    <td  colspan="5" >
                        Paid Amount
                    </td>
                    <td  >
                        {{ create_money_format($sales->paid_amount) }}
                    </td>
                </tr>
                <tr class="new_title">

                    <td  colspan="5" >
                        Due Amount
                    </td>
                    <td  >
                        {{ create_money_format($sales->grand_total-$sales->paid_amount) }}
                    </td>
                </tr>



            </table>

            <div style="text-align: center;" class="pay_info">
                <h5 style="margin: 10px auto !important;">  Payment Details</h5>
                <table class="payment_table">
                    <tr> <td>DESCRIPTION  </td><td>AMOUNT </td></tr>
                    <tr> <td> {{ $sales->payment_method->name }} </td> <td> {{ create_money_format($sales->paid_amount) }}</td></tr>
                </table>
                <table class="table_info_last">
                    <tr><td  >You earn by this bill:0.00 points</td></tr>
                    <tr><td ><b>  Goods once sold can not be retured</b></td></tr>
                    <tr><td style="font-size: 13px"> {{ $sales->sale_code }} </td></tr>
                    <tr><td  >Hisebi A software develop by R-Creation</td></tr>
                    <tr><td  >Tel : +880-1813-316786,   +880-1722-964303</td></tr>
                    <tr><td>@php print_r($sale_barcode) @endphp</td></tr>
                    <tr><td style="font-size: 13px"><b> Thank You For Shopping With Us</b></td></tr>


                </table>
            </div>
        </div>
        <!--End Table-->


    </div>
    <!--End InvoiceBot-->
</div>

<!--End Invoice-->
</body>
</html>

