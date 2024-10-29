<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/20/2019
 * Time: 4:10 PM
 */

?>

        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; }
        body {
            font: 14px/1.4 Helvetica, Arial, sans-serif;
        }
        #page-wrap { width: 720px; margin: 0 auto; }

        table { border-collapse: collapse; }
        table td, table th { border: 0.3px solid rgba(1, 1, 1, 0.74); padding: 5px 8px; }

        #header { height: 15px; width: 100%; margin: 20px 0; background: #222; text-align: center; color: white; font: bold 15px Helvetica, Sans-Serif; text-transform: Uppercase; letter-spacing: 20px; padding: 8px 0px; }

        #address { width: 250px; height: 150px; float: left; }


        #logo { text-align: right; width: 70px; height: 50px; verflow: hidden; }
        #customer-title { font-size: 20px; font-weight: bold; float: left; }

        #meta { margin-top: 1px; width: 100%; float: right; margin-bottom: 10px;}
        #meta td { text-align: right;  }
        #meta td.meta-head { text-align: left; background: #eee; }
        #meta td textarea { width: 100%; height: 20px; text-align: right; }

        #items { clear: both; width: 100%; margin: 30px 0 0 0; border: 0.3px solid rgba(0, 0, 0, 0.64); }
        #items th { background: #eee; padding: 5px 2px;}
        #items textarea { width: 80px; height: 50px; }
        #items tr.item-row td {  vertical-align: top; padding: 8px 5px;}
        #items td.description { width: 300px; }
        #items td.item-name { width: 175px; }
        #items td.description textarea, #items td.item-name textarea { width: 100%; }
        #items td.total-line { border-right: 0; text-align: right; }
        #items td.total-value { border-left: 0; padding: 10px 15px; }
        #items td.total-value textarea { height: 20px; background: none; }
        #items td.balance { background: #eee; }
        #items td.blank { border: 0; }

        #terms { text-align: center; margin: 20px 0 0 0; }
        #terms h5 { text-transform: uppercase; font: 13px Helvetica, Sans-Serif; letter-spacing: 10px; border-bottom: .3px solid rgba(0, 0, 0, 0.73); padding: 0 0 8px 0; margin: 0 0 8px 0; }
        #terms textarea { width: 100%; text-align: center;}


        .item-row td{
            text-align: center;
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
    <div style="width: 100%;">
        <table style="margin-bottom: 15px; width: 100%;  margin-top: 5mm;">
            <tr>
                <th style="text-align: right; border: 0 !important;" width="30%">
                    @if($company_logo)
                    <img src="{{ $company_logo }}" alt="{{ $company_name }}" id="logo"/>
                        @endif
                </th>
                <td style="text-align: left; padding-left: 70px; border: 0 !important;" width="70%" >
                    <h3 style="margin-bottom: 5px;"> {{ $company_name }} </h3>
                    <p> Address: {{ $company_address }}</p>
                    <p> {{ $company_city.', '.$company_country }}</p>
                    <p> Phone: {{ $company_phone }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div style="overflow: hidden; clear: both;">
        <table cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>#SL</th>
                <th>Transaction Date</th>
                <th>Transaction Code</th>
                <th>Transaction From</th>
                <th>Debit </th>
                <th>Credit </th>
            </tr>
            </thead>
            <tbody>
            <?php
                $total_debit = $total_credit = 0;
            ?>
            @forelse ($modal as $key => $value)
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
                ?>


            @empty

                <tr>
                    <td class="text-left" colspan="6">No List for {{ $title }}</td>
                </tr>


            @endforelse

            <tr>
                <th class="text-left" colspan="4"> Total: </th>
                <th class="text-right" colspan="1"> {{ create_money_format($total_debit) }}</th>
                <th class="text-right" colspan="1"> {{ create_money_format($total_credit) }}</th>
            </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

