<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 9/21/2019
 * Time: 12:57 PM
 */
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

    <title>{{ str_replace("<br/>", "", $report_title) }}</title>
    <style>
        * { margin: 0; }

        html{margin:20px 10px}
        body {
            font-family:  Helvetica, Arial, sans-serif;
            font-size: 10px;
        }
        #page-wrap { width: 740px; margin: 0 auto; }

        table {
            display: table;
            border-collapse: collapse;
            border-spacing: 0;
            color: #0a0a0a !important;
            width: 100% !important;

        }

        .table{
            border:  0.3px solid rgba(1, 1, 1, 0.32) !important;
        }
        .table tbody tr td, .table thead tr th, .table tbody tr th, .table thead tr td {
            padding: 3px !important;
            border: 0.3px solid rgba(1, 1, 1, 0.32) !important;
            text-align: center;
            font-size: 10px;
        }
        .table-border-padding{
            border:  0.3px solid rgba(1, 1, 1, 0.32) !important;
            padding: 3px !important;
        }
        .text-center{
            text-align: center !important;
        }

        .text-right{
            text-align: right !important;
        }

        .list-style-type{
            list-style-type: none;
        }

        .text-left{
            text-align: left !important;
        }

        .text-bold{
            font-weight: bold;
        }

        #logo { text-align: right; width: 70px; height: 50px; overflow: hidden; }
        /*@page {*/
        /*    margin: 0;*/
        /*}*/

        @media print
        {
            /**{*/
            /*    margin: 0;*/
            /*}*/

            .no-print, .no-print *
            {
                display: none !important;
            }
        }
    </style>
</head>
