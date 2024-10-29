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
    <title>Invoice</title>
    <style type="text/css">
        @page {
            /*size:8.5in 11in;*/
            /*margin-top: 2cm;*/
            /*margin-bottom: 2cm;*/
            /*margin-left: 2cm;*/
            /*margin-right: 2cm;*/
            size: letter;
            margin: 40px 20px;
            /* @bottom { content: element(footer) } */
        }

        * {
            margin: 0;
            padding: 0;
        }

        html {
            margin: 0;
        }
        body{
            /* padding: 1mm; */
            margin: 0 auto;
            width: 100%;
            height: auto;
        }
        #page-wrap {
            width: 720px;
            margin: 0 auto;
            height: auto;
        }
        #invoice-POS h1 {
            font-size: 1.5em;
            color: #000;
        }
        #invoice-POS h2 {
            font-size: 14px;
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
            margin-bottom: 10px;
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
            margin-bottom: 10px;
        }
        #invoice-POS .title {
            float: right;
        }
        #invoice-POS .title p {
            text-align: right;
        }
        #invoice-POS table {
            width: 99%;
            border-collapse: collapse;
        }
        #invoice-POS .tabletitle {
            font-size: 0.5em;
        }
        #invoice-POS .tabletitle td{
            background-color: #737373;
        }
        
        #invoice-POS .tabletitle td>h2{
            margin: 4px 10px !important;
            color: #fff;
        }
        #invoice-POS .service {
            /*border-bottom: 1px solid #eee;*/
        }
        #invoice-POS .item {

        }
        #invoice-POS .itemtext {
            font-size:14px;
            margin: 5px 0;
        }
        #invoice-POS #legalcopy {
            margin-top: 5mm;
        }

        .table_one{
            margin-top: 10px;
        }

        .table_one tr td{
            font-size: 14px;
            text-align: left;
        }

        .table_one tr td:nth-child(odd){
            font-size: 14px;
            text-align: left;
            width: 13%!important;
            padding: 4px;
        }
        .table_one tr td:nth-child(even){
            font-size: 14px;
            text-align: left;
            width: 20%!important;
            padding: 4px;
        }
        .table_info {
            margin-top: 10px;
        }
        .table_info tr td{
            font-size: 14px;
            text-align: center;
            letter-spacing: 0.5px;
        }
        .table_info tr td h1,
        .table_info tr td h2,
        .table_info tr td h3,
        .table_info tr td h4,
        .table_info tr td h5,
        .table_info tr td strong,
        .table_info tr td p{
            font-size: 14px !important;
            margin: 2px auto;
            line-height: 15px;
        }

        table.item_table  {
            page-break-inside:auto;
        }
        table.item_table tr { page-break-inside:avoid; page-break-after:auto; }

        .table_info_last {
            width: 99%;
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
        .customer-info{
            font-size: 14px;
            margin: 5px;
        }
        .text-bold{
            font-weight: bold;
        }
        .item_table tr td{
            text-align: center;
            border: 0.5px solid #949494;
        }
        .text-right{
            text-align: right !important;
        }
        .new_title td{
            font-size: 14px;
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
            font-size: 14px;
            text-align: left!important;
            color: #000;
            padding-top: 5px;

        }

        .payment_table tr td:last-child{
            font-size: 14px;
            text-align: right!important;
            color: #000;
            padding-top: 5px;

        }
        .no-border{border:0!important}

        div.footer {
            display: inline-block;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
        }
        /* @page :last {
            @footer {
                position: fixed;
            }
        } */

        @media print {
            .no-print, .no-print * {
                display: none !important;
            }
        }

    

    </style>


</head>
<body>
<section id="page-wrap">
    <div id="invoice-POS">
        <center id="top">

                <h2 style=" font-size: 20px; margin: 10px auto!important">{{ $company_name }}</h2>
                <div class="info">
                    <table class="table_info">
                        @if(isset($report_head_sub_text))
                            <tr><td  colspan="3"> {!! $report_head_sub_text !!} </td></tr>
                        @else
                            <tr><td  colspan="3">{{ $company_address }}</td></tr>
                            <tr><td  colspan="3">{{ $company_city.($company_country ? ", ".$company_country : "") }}</td></tr>
                        @endif
                        <tr><td  colspan="3"> Phone : {{ $company_phone }}</td></tr>
                    </table>
                </div>

        <!--End Info-->
        </center>
        <!--End InvoiceTop-->


        <div id="mid" >
            <div class="info" style="border-bottom: 1px solid #eee; padding-bottom: 5px;">
                <table class="table_one">
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
            </div>

            @if($sales->customer)
                <div style=" width: 60%; !important;"  >

                    <table class="customer-info">
                        <tr>
                            <td colspan="4" class="text-left text-bold">Customer Info: </td>
                        </tr>
                        <tr >
                            <td>Name: </td>
                            <td colspan="3"> {{ $sales->customer->name }}</td>
                        </tr>
                        <tr >
                            <td width="25%">Phone:</td>
                            <td width="25%">{{ $sales->customer->phone }}</td>
                            <td  width="25%">Address:</td>
                            <td width="25%">{{ $sales->customer->address }}</td>
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
                            <h2 style="margin-left:5px; text-align: left !important;">Product Name</h2>
                        </td>
                        <td class="Rate">
                            <h2>Price</h2>
                        </td>
                        <td class="Rate">
                            <h2>Qty</h2>
                        </td>
                        <td class="Rate"  >
                            <h2 style="text-align: right !important;">Amount</h2>
                        </td>
                        <td class="Rate"  >
                            <h2 style="text-align: right !important;">Discount</h2>
                        </td>
                        <td class="Rate"  >
                            <h2 style="text-align: right !important;">Total Price</h2>
                        </td>

                    </tr>
                    @php
                        $total = $sale_count = 0;
                    @endphp
                    @foreach( $sales->sale_details as $key=>$sale)
                       {{--  @for($i=0; $i<50; $i++) --}}
                            <tr class="service">
                                <td class="tableitem">
                                    <p class="itemtext">{{ $key+1 }}</p>
                                </td>
                                <td class="tableitem">
                                    <p class="itemtext" style="margin-left:10px; text-align: left !important;">{{ $sale->item->item_name }}</p>
                                    <p class="itemtext" style="margin-left:10px; text-align: left !important;">{{ $sale->product_info }}</p>
                                    <p class="itemtext" style="margin-left:10px; text-align: left !important;">{{ $sale->item->brand_id > 0 ? "Brand: ".$sale->item->brand->name : ''}}</p>
                                    <p class="itemtext" style="margin-left:10px; text-align: left !important;">{{ $sale->item->category->display_name }}</p>
                                    <p class="itemtext" style="font-size: 9px; margin-left:10px; text-align: left !important;">{!! $sale->item->description !!} </p>
                                    @if($sale->warranty>0)
                                    <p class="itemtext text-bold" style="margin-left:10px; text-align: left !important;">Warranty: {{ $sale->warranty }} Months <br/>(Expired Date: {{ create_date_format($sale->warranty_end_date) }})</p>
                                        @endif
                                </td>
                                <td class="tableitem">
                                    <p class="itemtext">{{ $sale->price }} </p>
                                </td>
                                <td class="tableitem">
                                    <p class="itemtext">{{ $sale->qty }} {{ $sale->item->unit }}</p>
                                </td>
                                <td class="tableitem">
                                    <p class="itemtext">{{ create_money_format($sale->qty*$sale->price) }}</p>
                                </td>
                                <td class="tableitem">
                                    <p class="itemtext">{{ create_money_format($sale->discount) }}</p>
                                </td>
                                <td class="tableitem">
                                    <p class="itemtext">{{ create_money_format(($sale->qty*$sale->price)-$sale->discount) }}</p>
                                </td>
                            </tr>
                            {{--   @endfor  --}}
                        @php
                            $total += ($sale->qty*$sale->price);
                        @endphp
                    @endforeach


                    <tr class="service">

                        <td style="text-align: right; " colspan="6" class="tableitem no-border">
                            <p class="itemtext">Sub Total</p>
                        </td>
                        <td class="tableitem text-right no-border">
                            <p class="itemtext">{{ create_money_format($total) }}</p>
                        </td>
                    </tr>


                    <tr class="new_title">
                        <td colspan="6" class="no-border" >Discount {{ $sales->discount_type=="fixed" ? "(Fixed)" : "(".$sales->discount."%)" }} :</td>
                        <td class="text-right no-border">
                            (-){{ create_money_format($sales->total_discount) }}
                        </td>
                    </tr>
                    <!-- 
                    <tr class="new_title">
                        <td  colspan="4"  class="no-border" >
                            <b>Net Amount</b>
                        </td>
                        <td class="text-right no-border" >
                            <b>{{ create_money_format($sales->grand_total) }}</b>
                        </td>
                    </tr> -->
                    <tr class="new_title">
                        <td colspan="6"  class="no-border"  > <b>Amount to Pay:</b></td>
                        <td class="text-right no-border">
                            <b>{{ create_money_format($sales->amount_to_pay) }}</b>
                        </td>
                    </tr>
                    <tr class="new_title">
                        <td  colspan="6"  class="no-border" >
                            <b>  Paid Amount </b>
                        </td>
                        <td class="text-right no-border" >
                            <b> {{ create_money_format($sales->paid_amount) }} </b>
                        </td>
                    </tr>
                    <tr class="new_title">
                        <td colspan="6" class="no-border" ><b>Due: </b></td>
                        <td class="text-right no-border">
                            <b> {{ create_money_format($sales->due) }}</b>
                        </td>
                    </tr>


                </table>


            </div>
            <!--End Table-->

        </div>

        <div style="margin-top: 15px; text-align: center !important; ">
            <b> Thank You For Shopping With Us</b>
        </div>

        <!--End InvoiceBot-->
    </div>


    <div class=" footer" id="footer" >
        <table class="table_info_last">
            <tr><td  >{{env('APP_NAME')}} A software develop by R-Creation</td></tr>
            <tr><td  >Tel : +880-1813-316786,   +880-1722-964303</td></tr>
        </table>
    </div>
</section>
</body>

<script type="text/javascript">


    // var height = Math.floor(document.getElementById('page-wrap').offsetHeight*0.264583);
    // var footer_bottom =  Math.round( ((height/297)-1), 1) *297;
    // // alert(footer_bottom);
    // document.getElementById("footer").style.bottom = (-1)*footer_bottom+"mm";
</script>
</html>

