<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/27/2019
 * Time: 1:14 PM
 */
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

    <title>{{$title}}</title>
    <style>
        * { margin: 0; padding: 0; }
        body {
            font: 14px/1.4 Helvetica, Arial, sans-serif;
            margin: 5mm 25mm 25mm 25mm;
        }
        #page-wrap { width: 1000px; margin: 10px auto; margin-bottom: 20px; }

        table { border-collapse: collapse; }
        table td, table th { border: 0.3px solid rgba(1, 1, 1, 0.74); padding: 5px 8px;
        }

        .item-row td{
            text-align: center;
        }

        .text-right{
            text-align: right;
        }

        @media print
        {
            .no-print, .no-print *
            {
                display: none !important;
            }
        }

        #meta-table tr td {
            border: 0;
        }
    </style>
</head>
<body>
<div id="page-wrap">
    <div style=" overflow: hidden; clear: both; float: inherit; text-align: center!important;">
        <table cellspacing="0" style="width:99%;  padding: 1.5cm 2cm;" >
            <tr>
                <th  colspan="9" style="padding-bottom:20px; font-size: 20px; font-weight: 900; border: 0; text-align: center;">
                    <div style="width:270px; float: left; text-align: right;"><img src=" {{ $company_logo }}" alt="" width="100px"/></div>
                    <div style="width:500px; float: left; padding: 20px 0; "> {{ $company_name }} <br/>
                        {{ $title }} </div>
                </th>
            </tr>

            <tr>
                <th>#SL</th>
                <th>Transaction Date</th>
                <th>Transaction Code</th>
                <th>Transaction From</th>
                <th>Debit </th>
                <th>Credit </th>
            </tr>
            <tbody>
            <?php
            $total_debit = $total_credit = 0;
            foreach ($modal as $key => $value)
            {
            ?>
            <tr>
                <td><?=$key+1?></td>
                <td>{{ db_date_month_year_format($value->date) }}</td>
                <td>{{ $value->transaction_code }}</td>
                <td>{{ $value->account_type_name }}</td>
                <td class="text-right">{{ $value->transaction_type=='dr' ? create_money_format($value->amount) : create_money_format(0) }}</td>
                <td class="text-right">{{ $value->transaction_type=='cr' ? create_money_format($value->amount) : create_money_format(0)  }}</td>

            </tr>
            <?php

            if($value->transaction_type=='dr')
                $total_debit += $value->amount;

            if($value->transaction_type=='cr')
                $total_credit += $value->amount;
            }

            if(count($modal)==0){
            ?>

            <tr>
                <td class="text-left" colspan="6">No List for {{ $title }}</td>
            </tr>

            <?php }

            if(count($modal)>0){
            ?>
            <tr>
                <th class="text-left" colspan="4"> Total: </th>
                <td class="text-right" colspan="1"> {{ create_money_format($total_debit) }}</td>
                <td class="text-right" colspan="1"> {{ create_money_format($total_credit) }}</td>
            </tr>

            <?php } ?>

            </tbody>
        </table>
    </div>
</div>
</body>
</html>
