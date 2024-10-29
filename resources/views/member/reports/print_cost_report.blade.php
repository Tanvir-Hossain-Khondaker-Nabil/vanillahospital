<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/29/2019
 * Time: 2:57 PM
 */
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

    <title>  {{ $cost_type == "unload" ? 'Unload' : 'Transport' }} Cost Report </title>
    <style>
        * { margin: 0; padding: 0; }

        html{margin:50px 50px}
        body {
            font: 12px/1.4 Helvetica, Arial, sans-serif;
        }
        #page-wrap { width: 720px; margin: 0 auto; }

        table {
            display: table;
            border-collapse: collapse;
            border-spacing: 0;
            color: #0a0a0a !important;
            width: 100% !important;
        }

        .table tbody tr td, .table thead tr th, .table tbody tr th, .table thead tr td {
            padding: 3px; !important;
            border: 0.3px solid rgba(1, 1, 1, 0.74) !important;
        }
        .table-border-padding{
            border: 0.3px solid rgba(1, 1, 1, 0.74) !important;
            padding: 3px; !important;
        }
        .text-center{
            text-align: center !important;
        }

        .text-right{
            text-align: right !important;
        }

        #logo { text-align: right; width: 70px; height: 50px; overflow: hidden; }


        @media print
        {
            .no-print, .no-print *
            {
                display: none !important;
            }
        }
    </style>
</head>
<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="overflow: hidden; clear: both;">
        <table cellspacing="0" width="100%" class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th> Memo No</th>
                <th> Chalan No</th>
                <th> {{ $cost_type == "unload" ? 'Unload' : 'Transport' }} Cost</th>
                <th> Date </th>
            </tr>
            </thead>
            <tbody>
            @forelse($modal as $key=>$value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->memo_no }}</td>
                    <td>{{ $value->chalan }}</td>
                    <td class="text-center">
                        {{ $cost_type == "unload" ? create_money_format($value->unload_cost) : create_money_format($value->transport_cost) }}
                    </td>
                    <td>{{ db_date_month_year_format($value->date) }}</td>
                </tr>
            @empty
                <tr>
                    <td class="text-left" colspan="5">No Report available</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
