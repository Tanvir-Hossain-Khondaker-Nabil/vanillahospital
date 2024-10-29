<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.opd.discount.summary.list']) ? route('member.opd.discount.summary.list') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Opd Individual Billing Details',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Opd Individual Billing Details',
    'title' => 'Opd Individual Billing Details',
    'heading' => 'Opd Individual Billing Details',
];

?>

@extends('layouts.back-end.master', $data)

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush

@section('contents')

                <div class="container">
                    <div class="card " style="background-color: white">
                        <div class="card-body">
                            <div class=" white shadow px-4">
                                <div class="row pl-5 pr-5 mt-5">
                                    <div class="col-md-3">
                                        @if (@$company_logo)
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

                                            {{ $company_phone }}
                                        </p>
                                    </div>


                                    <div class="col-md-12" style="border-bottom:#000 solid 1px">
                                    </div>


                                </div>
                                <!-- Table row -->
                                <div class="row my-3">
                                    <div class="">
                                        <div class="card  no-b">
                                            <div class="card-body px-4">

                                                    <div class=" white shadow">
                                                        <div class="row ">

                                                            <div class="col-md-1"></div>
                                                            <div class="col-md-6">
                                                                <table class="test_table_report">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>Patient Name: </td>
                                                                            <td>{{$datas->patient_name}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Age: </td>
                                                                            <td>{{$datas->age}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Gender: </td>
                                                                            <td>{{$datas->gender}}</td>
                                                                        </tr>
                                                                        <tr>

                                                                            <td>Date Of Birth: </td>
                                                                            <td>{{ date('j F, Y', strtotime($dob))}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Mobile No: </td>
                                                                            <td>{{$datas->phone}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Address: </td>
                                                                            <td>{{$datas->address}}</td>
                                                                        </tr>

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <table class="test_table_report">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>Booked By: </td>
                                                                            <td>{{$datas->user->full_name}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Doctor Name: </td>


                                                                            <td>{{$datas->doctor->name}}</td>


                                                                        </tr>

                                                                        <tr>
                                                                            <td>Ref Doctor Name: </td>


                                                                            <td>{{$datas->refDoctor->name}}</td>


                                                                        </tr>

                                                                        <tr>
                                                                            <td>Ordered Date: </td>
                                                                            <td>{{ date('j F, Y', strtotime($datas->date_of_service))}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Delivary Date: </td>
                                                                            <td></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>

                                                            </div>

                                                        </div>
                                                        <!-- Table row -->
                                                        <div class="row pl-5 pr-5 my-3">
                                                            <div class="col-12 table-responsive">
                                                                <form action="admin/opd_update_payment_each_bill/9379/9621"
                                                                    method="POST">
                                                                    <table
                                                                        class="table table-bordered table-striped test_table_report">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>SL</th>
                                                                                <th>Test Name</th>
                                                                                <th>Price</th>
                                                                                <!-- <th>Vat</th>
                                                                  <th>Discount</th> -->

                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($datas->outdoorPatientTest as $key=> $value)
                                                                            <tr>
                                                                                <td align="right">{{++$key}}</td>
                                                                                <td align="right">{{$value->subTestGroup->title}}</td>
                                                                                <td align="right">{{$value->price}} ৳</td>

                                                                            </tr>
                                                                            @endforeach

                                                                            <tr>
                                                                                <td colspan="2" align="right"><b>Total</b></td>
                                                                                <td align="right"><b> {{$datas->total_amount}} ৳</b></td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td colspan="2" align="right"><b>VAT (+)</b> </td>
                                                                                <td align="right"> <b>0</b></td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td colspan="2" align="right"><b>Total Discount (-)</b>
                                                                                </td>
                                                                                <td align="right"><b>{{$datas->discount}} ৳</b>  </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td colspan="2" align="right"> <b>Net Total</b></td>
                                                                                <td align="right"><b>{{$datas->net_amount}} ৳</b>  </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td colspan="2" align="right"><b>  Total Paid </b></td>
                                                                                <td align="right"><b> {{$datas->total_paid}} ৳</b>  </td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td colspan="2" align="right"><b> Due </b> </td>
                                                                                <td align="right"><b> {{$datas->due}}</b> </td>
                                                                            </tr>

                                                                        </tbody>
                                                                    </table>

                                                                </form>
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

                </div>

                    @endsection


                    @push('scripts')
                        <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

                        <script>
                            $(function() {
                                $('.select2').select2();

                            });

                            $('#from_date').datepicker({

                                // "format": 'yyyy-mm-dd',
                                "format": 'mm/dd/yyyy',
                                "showTime": true,
                                // "startDate": "+0d",
                            });

                            $('#to_date').datepicker({

                                // "format": 'yyyy-mm-dd',
                                "format": 'mm/dd/yyyy',
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
