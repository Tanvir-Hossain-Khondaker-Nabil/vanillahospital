<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 8/8/2019
 * Time: 2:27 PM
 */

?>


    <!DOCTYPE html>
<html>
<head>
    <title>Pos Invoice</title>
    <style type="text/css">
        body{
            margin: 0 auto;
            padding: 0;
        }
        #invoice-POS {
            /*box-shadow: 0 0 1in -0.25in rgb(227, 215, 215);*/
            padding: 0 10px;
            margin: 0 auto;
            width: 250px;
            background: #fff;
            /*border: 1px solid #9c9c9c;*/
        }
        #invoice-POS ::selection {
            /*background: #f31544;*/
            color: #fff;
        }
        #invoice-POS :: moz-selection {
            /*background: #f31544;*/
            color: #fff;
        }
        #invoice-POS h1 {
            font-size: 1.5em;
            color: #000;
        }
        #invoice-POS h2 {
            font-size: 11px;
        }
        #invoice-POS h3 {
            font-size: 10em;
            font-weight: 300;
            line-height: 2em;
        }
        #invoice-POS p {
            font-size: 0.7em;
            color: #000;;
            line-height: 1.2em;
        }
        #invoice-POS #top,
        #invoice-POS #mid,
        #invoice-POS #bot {
            /* Targets all id with 'col-' */
            border-bottom: 1px solid #eee;
        }
        .table_info tr td h1,
        .table_info tr td h2,
        .table_info tr td h3,
        .table_info tr td h4,
        .table_info tr td h5,
        .table_info tr td strong,
        .table_info tr td p{
            font-size: 12px !important;
            margin: 2px auto;
            line-height: 15px;
        }

        #invoice-POS #top .logo {
            height: 60px;
            width: 60px;
            background: url({{$company_logo}}) no-repeat;
            background-size: 60px 60px;
            margin: 0 auto;
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
        #invoice-POS .tabletitle, #invoice-POS .tableitem {
            font-size: 9px;
        }
        #invoice-POS .tabletitle td{
            text-align: center;
            border-bottom: 1px solid #eee;
            border-top: 1px solid #eee;
            text-transform: capitalize;
        }
        #invoice-POS .service {
            border-bottom: 1px solid #eee;
        }

        #invoice-POS .item {

        }

        #invoice-POS .itemtext {
            font-size: 9px;
        }
        #invoice-POS #legalcopy {
            margin-top: 5mm;
        }

        .customer-info{
            font-size: 10px;
        }
        .text-bold{
            font-weight: bold;
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
            font-size: 9px;
            text-align: left;
            width: 18%!important;
            padding: 4px 2px;
        }
        .table_one tr td:nth-child(even){
            font-size: 9px;
            text-align: left;
            width: 32%!important;
            padding: 4px 2px;
        }
        .table_info {
            margin-top: 10px;
        }
        .table_info tr td{
            font-size: 10px;
            text-align: center;
            letter-spacing: 0.5px;
        }

        .table_info_last {
            margin-top: 10px;
        }
        .table_info_last tr td{
            font-size: 9px;
            text-align: center;
            letter-spacing: 0;
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

        .new_title td{
            font-size: 9px;
            text-align: right!important;
            color: #000;;
            padding-top: 5px;

        }

        .payment_table{
            width: 60%!important;
            text-align: center;
            margin: 0 auto;
        }
        .payment_table tr td:first-child{
            font-size: 9px;
            text-align: left!important;
            color: #000;;
            padding-top: 5px;

        }

        .payment_table tr td:last-child{
            font-size: 9px;
            text-align: right!important;
            color: #000;;
            padding-top: 5px;
        }
        #barcode{
            width: 100px;
        }
    </style>


</head>
<body>
<div id="invoice-POS">

    <div id="top" class="text-center">
        <div class="logo">

        </div>
        <h2 style="margin: 10px auto!important; font-size: 13px; text-align: center;">{{ $company_name }}</h2>
        <div class="info">
            <table class="table_info">
                <tr><td  colspan="3" style="font-size: 9px;">{{ $company_address.", ".$company_city.($company_country ? ", ".$company_country : "") }}</td></tr>
                <tr><td  colspan="3" style="font-size: 9px;">Phone : {{ $company_phone }}</td></tr>
                <tr><td  colspan="3"><b>Cash Memo</b></td></tr>

            </table>


        </div>
        <!--End Info-->
    </div>
    <!--End InvoiceTop-->

    <div id="mid">
        <div class="info">
            <table class="table_one">
                <tr>
                    <td style="font-size: 10px; width: 23% !important;"><b>Memo No</b></td>
                    <td style="font-size: 10px" colspan="3"><b>: INV\{{ $sales->sale_code }}</b></td>
                </tr>
                <tr>
                    <td>Bill Date</td>
                    <td>: {{ $sales->date_format }}</td>
                    <td>VAT Reg</td>
                    <td>: 001404504</td>
                </tr>
                <tr>
                    <td>Cashier</td>
                    <td>: {{ $sales->creator->full_name }}</td>
                    <td>Time</td>
                    <td>: {{ create_time_format($sales->created_at) }}</td>
                </tr>
            </table>
        </div>
        @if($sales->customer)
            <div style="border--left: 1px solid #d2d1d1; width: 100%; !important;"  >

                <table class="customer-info">
                    <tr>
                        <td colspan="2" class="text-left text-bold">Customer Info: </td>
                    </tr>
                    <tr >
                        <td>Name: </td>
                        <td> {{ $sales->customer->name }}</td>
                    </tr>
                    <tr >
                        <td>Address:</td>
                        <td>{{ $sales->customer->address }}</td>
                    </tr>
                    <tr >
                        <td>Phone:</td>
                        <td>{{ $sales->customer->phone }}</td>
                    </tr>
                </table>
            </div>
        @endif
    </div>
    <!--End Invoice Mid-->

    <div id="bot">

        <div id="table">
            <table class="item_table">
                <tr class="tabletitle">
                    <td class="item">
                        <h2>SL</h2>
                    </td>
                    <td class="Hours">
                        <h2>Description</h2>
                    </td>
                    <td class="Rate">
                        <h2>price</h2>
                    </td>
                    <td class="Rate">
                        <h2>Qty</h2>
                    </td>
                    <td class="Rate"  style="text-align: right !important;">
                        <h2>Amount</h2>
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
                            <p class="itemtext">{{ $sale->price }} x</p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext">{{ $sale->qty }}</p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext">{{ create_money_format($sale->qty*$sale->price) }}</p>
                        </td>
                    </tr>

                    @php
                        $total += $sale->qty*$sale->price;
                    @endphp
                @endforeach


                <tr class="service">
                    <td style="text-align: right;" colspan="4" class="tableitem">
                        <p class="itemtext">Sub Total</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">{{ create_money_format($total) }}</p>
                    </td>
                </tr>

                @if($sales->shipping_charge>0)

                <tr class="new_title">
                    <td colspan="4"  >Shipping Charge  :</td>
                    <td class="text-right">{{ create_money_format($sales->shipping_charge) }}</td>
                </tr>

                @endif

                <tr class="new_title">
                    <td colspan="4"  >Discount {{ $sales->discount_type=="fixed" ? "(Fixed)" : "(".$sales->discount."%)" }} :</td>
                    <td class="text-right">(-) {{ create_money_format($sales->total_discount) }}</td>
                </tr>

                <tr class="new_title">
                    <td  colspan="4" >
                        Net Amount
                    </td>
                    <td  >
                        {{ create_money_format($sales->grand_total) }}
                    </td>
                </tr>
                <tr class="new_title">
                    <td colspan="4"  > Amount to Pay:</td>
                    <td class="text-right">{{ create_money_format($sales->amount_to_pay) }}</td>
                </tr>
                <tr class="new_title">
                    <td  colspan="4" >
                        Paid Amount:
                    </td>
                    <td  >
                        {{ create_money_format($sales->paid_amount) }}
                    </td>
                </tr>


            </table>

            <div style="text-align: center;" class="pay_info">
                <h5 style="margin: 10px auto !important; font-size: 10px;">  Payment Details</h5>
                <table class="payment_table">
                    <tr> <td>DESCRIPTION  </td><td>AMOUNT </td></tr>
                    <tr> <td> {{ $sales->payment_method->name }} </td> <td> {{ create_money_format($sales->paid_amount) }}</td></tr>
                </table>
                <table class="table_info_last">
                    <tr><td  >You earn by this bill:0.00 points</td></tr>
                    <tr><td ><b>  Goods once sold can not be retured</b></td></tr>
                    <tr><td style="font-size: 12px"> {{ $sales->sale_code }} </td></tr>
                    <tr><td  >{{env('APP_NAME')}} A software develop by R-Creation</td></tr>
                    <tr><td  >Tel : +880-1813-316786,   +880-1722-964303</td></tr>
                    <tr><td>@php print_r($sale_barcode) @endphp</td></tr>
                    <tr><td style="font-size: 10px;"><b> Thank You For Shopping With Us</b></td></tr>


                </table>
            </div>
        </div>
        <!--End Table-->


    </div>
    <!--End InvoiceBot-->
</div>

<!--End Invoice-->
</body>
<script type="text/javascript">

    var pos_url = "{{ route('member.sales.pos_create') }}";
    window.print();
    window.onafterprint = back;

    function back() {
        // window.location.replace(pos_url);
    }
</script>

</html>
