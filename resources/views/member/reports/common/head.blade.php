<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>

    <title>{{ $report_title }}</title>
    <style>
        @page {
            /*size:8.5in 11in;*/
            /*margin-top: 2cm;*/
            /*margin-bottom: 2cm;*/
            /*margin-left: 2cm;*/
            /*margin-right: 2cm;*/
            margin: 30px 10px;
            size: letter;
        }

        @media print {
            .no-print, .no-print * {
                display: none !important;
            }
        }

        * {
            margin: 0;
            padding: 0;
        }

        html {
            margin: 0px
        }

        body {
            font: 12px Helvetica, Arial, sans-serif;
            margin: 0;
        }

        #page-wrap {
            width: 740px;
            margin: 0 auto;
        }

        table {
            display: table;
            border-collapse: collapse;
            border-spacing: 0;
            color: #0a0a0a !important;
            width: 100% !important;
        }

        a {
            color: #1d1d1d;
            text-decoration: none;
        }

        table tbody tr td, table tbody tr th, table thead tr th {
            border: 0.3px solid rgba(1, 1, 1, 0.32) !important;
            padding: 3px;
            font-size: 11px;
        }
        .table{
            border: 0.3px solid rgba(1, 1, 1, 0.32) !important;
        }
        .table tbody tr td, .table tbody tr th, .table thead tr th {
            border: 0px solid #fff !important;
            padding: 6px;
            text-align: left;
            font-size: 11px;
        }

        .text-center {
            text-align: center !important;
        }

        .no-border {
            border: 0px;
        }

        .single-line {
            text-decoration-style: initial;
            text-decoration-line: underline;
            text-decoration-skip-ink: none;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .padding-right-120 {
            padding-right: 120px !important;
        }

        .padding-right-50 {
            padding-right: 50px !important;
        }

        .padding-left-40 {
            padding-left: 40px !important;
        }
        .padding-left-70 {
            padding-left: 70px !important;
        }
        .text-bold{
            font-weight: bold;
        }

        .report-head-tag {
            font-size: 17px;
            font-weight: bold;
            letter-spacing: 5px;
        }

        .width-100 {
            width: 100px;
        }

        .balance_sheet_ul {
            list-style-type: none;
            width: 100%;
            margin: 0;
            padding: 0;
            clear: both;
            margin-top: 15px;
        }

        .balance_sheet_ul li {
            float: left;
        }

        .balance_sheet_ul li:first-child {
            padding: 6px;
            font-weight: bold;
            width: 100% !important;
        }

        .balance_sheet_ul li:last-child {
            width: 100% !important;
        }

        table#dataTable .border-1 {
            border: 1px solid #eee !important;
        }
        .border-1 {
            border: 1px solid rgba(0, 0, 0, 0.94) !important;
        }

        .border-right-1 {
            border-right: 1px solid #ccc;
        }

        .table tbody tr th.border-top-1,
        .border-top-1{
            border-top: 1px solid rgba(0, 0, 0, 0.94) !important;
        }

        .border-dual {
            border-bottom-color: #0a0a0a;
            border-bottom-style: double;
            width: 200px;
        }

        .text-right {
            text-align: right !important;
        }

        .text-left {
            text-align: left !important;
        }

        #logo {
            text-align: right;
            width: 70px;
            height: 50px;
            overflow: hidden;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }

        .col-lg-6:first-child{
            border-right: 1px solid #eee;
            margin-right: 13px;

        }
        .col-lg-6{
            float: left;
            width: 49%;
        }

        .dual-line {
            text-decoration-style: double;
            text-decoration-line: underline;
            text-decoration-skip-ink: none;
        }
    </style>
</head>
