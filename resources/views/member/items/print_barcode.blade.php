<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 8/29/2019
 * Time: 12:56 PM
 */
?>


    <!DOCTYPE html>
<html>
<head>
    <title>Product Barcode Print</title>
    <style type="text/css">

        .per-barcode{
            /* margin-right: 20px; */
            width: 150px;
            height: 105px;
            text-align: center;
            float: left;
            /* margin-bottom: 20px; */
            border: 1px dashed;
        }

        .per-barcode>div>div{
            text-align: center !important;
            margin: auto;
        }
        .per-barcode h4{
            margin: 5px 0;
            font-size: 15px;
        }
        .per-barcode p{
           text-align: center;
            margin: 5px 0;
            font-size: 12px;
        }
    </style>


</head>
<body>
<div id="invoice-POS">

    <div id="bot">
        @for($i=1; $i<=$print_qty; $i++)
            <div class="per-barcode">
{{--                <p>{{ $company_name }}</p>--}}
                <p>{{ $item->item_name }}</p>
                <div>@php print_r($barcode) @endphp</div>
                <p>{{ $item->productCode }}</p>
                <h4>{{ "TK ".$item->price."/=" }}</h4>
            </div>
        @endfor

    </div>
    <!--End InvoiceBot-->
</div>

<!--End Invoice-->
</body>
</html>

