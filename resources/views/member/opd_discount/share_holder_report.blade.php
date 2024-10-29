<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.opd.share_holder_report']) ? route('member.opd.share_holder_report') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'OPD Share Holder Report',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'OPD Share Holder Report',
    'title' => 'OPD Share Holder Report',
    'heading' => 'OPD Share Holder Report',
];

?>

@extends('layouts.back-end.master', $data)

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush

@section('contents')
    <div class="row">
        <div class="card my-3 no-b">
            <div class="card-body">
                <div class="container">
                    <div class="invoice white shadow">
                        <div class="row pl-5 pr-5">
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
                        <div class="row pl-5 pr-5 my-3">
                            <div class="col-12 table-responsive">
                                <p style="text-align:center;font-size:14px;color:#000;font-weight:bold;"> Report of OPD test
                                    discount for share holder between {{ $from_date }} to {{ $to_date }} </p>






                                <h2 align="center"> Share Holder: {{ $share_holder->name }} </h2>

                                <table id="test_table"
                                    class="table table-bordered table-hover test_table_report test_table_report">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Test Name</th>
                                            <th>Price</th>
                                            <th>Discount</th>
                                            <th>Net Amount</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                            $i = 0;
                                            $sum = 0;
                                        @endphp
                                        @foreach ($datas as $key => $per_data)
                                            {{-- {{ dd($list[0]->price)}} --}}
                                            {{-- {{ dd($list[0]->price)}} --}}

                                            {{-- @if (@$list[0]->price) --}}


                                            @foreach ($per_data->outdoorPatientTest as $key => $list)
                                                @php
                                                    $sum = $sum + $list->price;
                                                @endphp
                                                <tr>
                                                    <td align="left">{{ ++$i }}</td>
                                                    <td align="left">{{ $list->subTestGroup->title }}</td>
                                                    <td align="left">{{ $list->price }}</td>
                                                    <td align="left">{{ $list->discount }}</td>
                                                    <td align="left">{{ $list->net_amount }}</td>
                                                    {{-- <td align="left">{{ count($list) * @$list[0]->price }}</td> --}}
                                                </tr>
                                                {{-- @endif --}}
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td style="font-size:16px;font-weight:bold" colspan="2" align="right">Total:
                                            </td>
                                            <td style="font-weight:bold"> {{ $sum }}</td>

                                            {{-- <td style="font-size:16px;font-weight:bold"  align="right">Total:
                                            </td> --}}
                                            <td style="font-weight:bold"> {{ $total_discount }}</td>

                                        </tr>
                                    </tfoot>

                                </table>

                                <!-- <p style="font-weight:bold">Total Amount : 5650</p> -->





                                <table id="test_table"
                                    class="table table-bordered table-hover test_table_report test_table_report">
                                    <tbody>
                                        <tr>
                                            <td align="right" style="font-weight:bold;width: 77%;">
                                                <span style="font-size:16px">Total Revenue :</span>
                                            </td>

                                            <td>
                                                <p style="font-weight:bold;text-align:left">{{ $sum }}</p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td align="right" style="font-weight:bold;width: 77%;font-size:16px"><span
                                                    style="font-size:16px">Total Discount : </span></td>

                                            <td>
                                                <p style="font-weight:bold;text-align:left">{{ $total_discount }}</p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td align="right" style="font-weight:bold;width: 77%;font-size:16px"><span
                                                    style="font-size:16px">Total Gross :</span></td>

                                            <td>
                                                <p style="font-weight:bold;text-align:left">
                                                    {{ $sum - $total_discount }}</p>
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>






                            </div>
                            <!-- /.col -->
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

    <script>
        setTimeout(function() {
            window.print();

        }, 500);
    </script>
    </script>
@endpush
