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
    <title>{{__('common.invoice')}}</title>
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

        /** {*/
        /*    margin: 0;*/
        /*    padding: 0;*/
        /*}*/

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

        table.sales_table{
            margin-top: 15px;
        }
        table.sales_table tr th{
            padding-top: 8px;
            padding-bottom: 8px;
            text-align: center;
            border: 0.5px solid #858585;
        }

        table.sales_table tr td{
            padding-top: 5px;
            padding-bottom: 5px;
            border: 0.5px solid #858585;
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
        .text-center{
            text-align: center !important;
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
        <div>

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
        </div>
        <!--End InvoiceTop-->


        <div id="mid" >
            <div class="info" style="border-bottom: 1px solid #eee; padding-bottom: 5px;">
                <table class="table_one">
                    <tr>
                        <td ><b>Token</b></td>
                        <td colspan="3"><b>: {{ $orders->token }}</b></td>
                        <td ><b>Invoice No</b></td>
                        <td ><b>: {{ "INV-".$orders->id }}</b></td>
                    </tr>
                    <tr>
                        <td>Entry Date</td>
                        <td>: {{ $orders->entry_date }}</td>
                        <td>Time</td>
                        <td>: {{ create_time_format($orders->created_at)   }}</td>
                        <td> Creator Name</td>
                        <td>: {{ $orders->creator->full_name }}</td>
                    </tr>
                </table>
            </div>

            @if($orders->customer)
                <div style=" width: 100%; !important;"  >

                    <table class="customer-info">
                        <tr>
                            <td colspan="2" class="text-left text-bold">Customer Info: </td>
                        </tr>
                        <tr >
                            <td width="25%">Name: </td>
                            <td colspan="3"> {{ $orders->customer->name }}</td>
                        </tr>
                        <tr >
                            <td width="25%">Phone:</td>
                            <td>{{ $orders->customer->phone }}</td>
                        </tr>
                        <tr >
                            <td width="25%">Address:</td>
                            <td >{{ $orders->customer->address }}</td>
                        </tr>


                        <tr style="border-top: 1px solid #eee; margin-top: 10px;">
                            <th width="25%" style="text-align: left;">Product:</th>
                            <th  style="text-align: left;">: {{ $orders->product_name }}</th>
                        </tr>
                        <tr>
                            <td width="25%" style="text-align: left;">Defects:</td>
                            <th style="text-align: left;">:
                                @php
                                    foreach ($orders->defects() as $value)
                                        {
                                            echo $value->name.", ";
                                        }
                                @endphp
                            </th>
                        </tr>
                        <tr >
                            <td width="25%" style="text-align: left;">Defect Description</td>
                            <td  style="text-align: left;">; {{ $orders->defect_description }}</td>
                        </tr>
                        <tr >
                            <td width="25%" style="text-align: left;">Delivery</td>
                            <td  style="text-align: left;">: {{ $orders->estimate_delivery_date }}</td>
                        </tr>
                    </table>
                </div>
            @endif
        </div>
        <!--End Invoice Mid-->

        <div id="bot">

            <div id="table">

                    @php
                        $total = 0;
                    @endphp
                    @if($orders->repair_items()->where('item_type', 0)->count()>0)
                        <table style="width: 100%; text-align: left;" class="sales_table">
                            <thead>
                            <tr class=" bg-gray">
                                <th width="50px" class="text-center">SL</th>
                                <th>{{__('common.Parts')}} </th>
                                <th class="text-center"> {{ __('common.cover_by_warranty_gurrantee') }}</th>
                                <th class="text-center">{{__('common.qty')}}</th>
                                <th class="text-center">{{__('common.price')}}</th>
                                <th class="text-right">{{__('common.total_price')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $orders->repair_items()->where('item_type', 0)->get() as $key=>$order)
                                <tr>
                                    <td width="5%" class="text-center">{{ $key+1 }}</td>
                                    {{--<td>{{ $order->item }}</td>--}}

                                    <td width="35%">{{ $order->item->item_name }}</td>
                                    <td class="text-center">{{ $order->cover_by_warranty ? "YES" : "" }}</td>
                                    <td class="text-center">{{ $order->qty }}</td>
                                    <td class="text-center">{{ create_money_format($order->price) }}</td>
                                    <td class="text-right">{{ create_money_format($order->price*$order->qty) }}</td>
                                </tr>

                                @php
                                    $total += $order->total_price;
                                @endphp
                            @endforeach
                            <tr>
                                <th colspan="5" class="text-right" style="border-bottom: 0.5px solid #858585 !important;border-top: 0.5px solid #858585 !important; "> {{ __('common.total') }}</th>
                                <th class="text-right" style="border-bottom: 0.5px 0 solid #858585 !important;border-top: 0.5px solid #858585 !important; ">{{ create_money_format($orders->total_item_price) }}</th>
                            </tr>
                            </tbody>
                        </table>
                    @endif

                    @if($orders->repair_items()->where('item_type', 1)->count()>0)
                        <table style="width: 100%;  text-align: left;" class="sales_table mt-3">
                            <thead>
                            <tr class=" bg-gray">
                                <th width="5%" class="text-center">SL</th>
                                <th  width="35%">{{__('common.services')}} </th>
                                <th class="text-center"> {{ __('common.cover_by_warranty_gurrantee') }}</th>
                                <th class="text-center">{{__('common.qty')}}</th>
                                <th class="text-center">{{__('common.price')}}</th>
                                <th class="text-right">{{__('common.total_price')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $orders->repair_items()->where('item_type', 1)->get() as $key=>$order)
                                <tr>
                                    <td class="text-center">{{ $key+1 }}</td>
                                    {{--<td>{{ $order->item }}</td>--}}

                                    <td>{{ $order->service->title }}</td>
                                    <td class="text-center">{{ $order->cover_by_warranty ? "YES" : "" }}</td>
                                    <td class="text-center">{{ $order->qty }}</td>
                                    <td class="text-center">{{ create_money_format($order->price) }}</td>
                                    <td class="text-right">{{ create_money_format($order->price*$order->qty) }}</td>
                                </tr>

                                <tr>
                                    <th colspan="5" class="text-right" style="border-bottom: 0.5px solid #858585 !important;border-top: 0.5px solid #858585 !important; "> {{ __('common.total') }}</th>
                                    <th class="text-right" style="border-bottom: 0.5px solid #858585 !important;border-top: 0.5px solid #858585 !important; ">{{ create_money_format($orders->total_service_price) }}</th>
                                </tr>
                                @php
                                    $total += $order->total_price;
                                @endphp
                            @endforeach

                            </tbody>
                        </table>
                    @endif

                <table>
                    <tr class="service">

                        <td style="text-align: right; " colspan="6" class="tableitem no-border">
                            <p class="itemtext">Sub Total</p>
                        </td>
                        <td class="tableitem text-right no-border">
                            <p class="itemtext">{{ create_money_format($orders->sub_total) }}</p>
                        </td>
                    </tr>


                    <tr class="new_title">
                        <td colspan="5" style="text-align: left !important; " class="no-border" > </td>
                        <td colspan="1" class="no-border" >Discount:</td>
                        <td class="text-right no-border">
                            (-){{ create_money_format($orders->discount) }}
                        </td>
                    </tr>

                    <tr class="new_title">
                        <td colspan="6"  class="no-border"  > <b>Amount to Pay:</b></td>
                        <td class="text-right no-border">
                            <b>{{ create_money_format($orders->amount_to_pay) }}</b>
                        </td>
                    </tr>
                    <tr class="new_title">
                        <td  colspan="6"  class="no-border" style="border-bottom: 0.5px solid #858585 !important; ">
                            <b >  Paid Amount </b>
                        </td>
                        <td class="text-right no-border" style="border-bottom: 0.5px solid #858585 !important; ">
                            <b> {{ create_money_format($orders->paid_amount) }} </b>
                        </td>
                    </tr>
                    <tr class="new_title">
                        <td colspan="6" class="no-border" ><b>Due Amount: </b></td>
                        <td class="text-right no-border">
                            <b> {{ create_money_format($orders->due) }}</b>
                        </td>
                    </tr>


                    <tr>
                        <td colspan="9" style="text-align: left; padding: 15px;" class="no-border">
                            <span class="text-bold"> In Words: </span> {{ AmountInWords($orders->amount_to_pay) }}
                        </td>
                    </tr>

                </table>


            </div>
            <!--End Table-->

        </div>

        <div style="margin-top: 15px; text-align: center !important; ">
            <b> Thank You For Service With Us</b>
        </div>

        <!--End InvoiceBot-->
    </div>

    <div class=" footer" id="footer" >
        <table class="table_info_last">
            <tr><td  >{{ env('APP_NAME') }} A software develop by R-Creation</td></tr>
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

