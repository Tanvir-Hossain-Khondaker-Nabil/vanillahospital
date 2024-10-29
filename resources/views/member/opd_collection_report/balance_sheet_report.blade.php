<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.opd.balance_sheet.list']) ? route('member.opd.balance_sheet.list') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'OPD Balance Sheet',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'OPD Balance Sheet',
    'title' => 'OPD Balance Sheet',
    'heading' => 'OPD Balance Sheet',
];

?>

@extends('layouts.back-end.master', $data)

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush

@section('contents')
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="row">
                <div class="section-wrapper">
                    <div class="card my-3 no-b">
                        <div class="card-body">
                            <div class="container">
                                <div class="invoice white shadow">
                                    <div class="row">
                                        <div class="col-md-3">
                                            @if(@$company_logo)
                                            <img height="120px" width="120px" src="{{ $company_logo }}">
                                            @endif
                                        </div>
                                        <div class="col-md-9">

                                            <h1 style="text-align:center;font-size:26px;color:black;font-weight:bold;">
                                                {{ $company_name }}
                                            </h1>
                                            <h1 style="text-align:center;font-size:20px;color:black;">
                                                {{ $company_address }}</h1>
                                            <p style="text-align:center;font-size:18px;color:black;margin-top:-8px;">

                                               Mobile: {{ $company_phone }}
                                            </p>
                                        </div>


                                        <div class="col-md-12" style="border-bottom:#000 solid 1px">
                                        </div>


                                    </div>

                                    <div class="row pl-5 pr-5 my-3">
                                        <div class="col-12 table-responsive">

                                            <h1 align="center">Final Balance Sheet</h1><br>

                                            <h3 align="center">Appointment Balance Sheet For {{$from_date}} - {{$to_date}}</h3><br>

                                            <table class="table" id="customers">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <h5>SL</h5>
                                                        </th>
                                                        <th>
                                                            <h5>Head Name</h5>
                                                        </th>
                                                        <th>
                                                            <h5>Amount</h5>
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr>
                                                        <td align="left">1</td>
                                                        <td align="left">Total Appointment Amount</td>
                                                        <td align="right">{{$total_appoinment_amount}}</td>

                                                    </tr>

                                                    <tr>
                                                        <td align="left">2</td>
                                                        <td align="left">Appointment Discount (+)</td>
                                                        <td align="right">{{$total_appoinment_discount}}</td>

                                                    </tr>

                                                    <tr>
                                                        <td align="left"><span style="font-weight:bold;">3</span></td>
                                                        <td align="left"><span style="font-weight:bold;">Appointment Net
                                                                Cash In</span></td>
                                                        <td align="right"><span style="font-weight:bold;">{{$total_appoinment_amount - $total_appoinment_discount}}</span>
                                                        </td>

                                                    </tr>

                                                </tbody>
                                            </table>

                                            <h3 align="center">Outdoor Balance Sheet For {{$from_date}} - {{$to_date}}</h3><br>

                                            <table class="table" id="customers">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <h5>SL</h5>
                                                        </th>
                                                        <th>
                                                            <h5>Head Name</h5>
                                                        </th>
                                                        <th>
                                                            <h5>Amount</h5>
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr>
                                                        <td align="left">1</td>
                                                        <td align="left">Outdoor Total Test Price</td>
                                                        <td align="right">{{$total_test_price}}</td>

                                                    </tr>

                                                    <tr>
                                                        <td align="left">2</td>
                                                        <td align="left">Outdoor Total Vat (+)</td>
                                                        <td align="right">0.00</td>

                                                    </tr>


                                                    <tr>
                                                        <td align="left">3</td>
                                                        <td align="left">Outdoor Total Discount (-)</td>
                                                        <td align="right">{{$total_discount}}</td>

                                                    </tr>

                                                    <tr>
                                                        <td align="left"><b>4</b></td>
                                                        <td align="left"><b>Outdoor Payable Amount</b></td>
                                                        <td align="right"><b> {{$total_payable_amount}}</b></td>

                                                    </tr>

                                                    <tr>
                                                        <td align="left">4</td>
                                                        <td align="left">Outdoor Paid amount </td>
                                                        <td align="right" style="border-bottom: 3px solid #000;">
                                                            {{$total_paid_amount}}</td>

                                                    </tr>

                                                    <tr>
                                                        <td align="left">5</td>
                                                        <td align="left">Outdoor Due</td>
                                                        <td align="right">{{$total_payable_amount - $total_paid_amount}}</td>

                                                    </tr>

                                                    <tr>
                                                        <td align="left">6</td>
                                                        <td align="left">Outdoor Total Commission (-)</td>
                                                        <td align="right" style="border-bottom: 3px solid #000;">{{$total_commission}}
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td align="left">7</td>
                                                        <td align="left">Outdoor Old Due Collection</td>
                                                        <td align="right" style="border-bottom: 3px solid #000;">0.00
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td align="left"><b>8</b></td>
                                                        <td align="left"><b>Outdoor Total Net Cash In</b></td>
                                                        <td align="right"><b>{{$total_paid_amount - $total_commission}}</b></td>

                                                    </tr>


                                                </tbody>
                                            </table>


                                            <span
                                                style="text-align:center;color:#000;font-weight:bold;font-size:10px">Print
                                                Date and Time Monday 24th of June 2024 02:34:15 PM (admin)</span>



                                        </div>
                                        <!-- /.col -->
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script>
        $(function() {
            $('.select2').select2();

        });

        $('#from_date').datepicker({

            "format": 'yyyy-mm-dd',
            // "format": 'mm/dd/yyyy',

            "showTime": true,
            // "startDate": "+0d",
        });

        $('#to_date').datepicker({

            "format": 'yyyy-mm-dd',
            // "format": 'mm/dd/yyyy',
            "showTime": true,
            // "startDate": "+0d",
        });

        $(function() {
            $("#vanilla-table1").DataTable({
                // "lengthMenu":[ 3,4 ],
                "searching": true,
            });
            $("#vanilla-table2").DataTable({

                "searching": true,
            });
            $("#vanilla-table3").DataTable({

                "searching": true,
            });

        });
    </script>


    </script>
@endpush
