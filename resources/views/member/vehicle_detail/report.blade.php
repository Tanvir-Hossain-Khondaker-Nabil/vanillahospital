<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.list.index']) ? route('member.list.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';


$data['data'] = [
    'name' => 'Ambulance Booking',
    'title' => 'Report Of Ambulance Booking',
];

?>

@extends('layouts.back-end.master', $data)
@section('contents')



<div class="container" style="display: flex; flex-direction:column;align-items:center">
    <form action="{{ url('/member/download_reserve_booking') }}" method="POST">
        @csrf
        <input type="hidden" name="vehicle_info_id" value="{{$vehicle_info_id}}">
        <input type="hidden" name="driver_id" value="{{$driver_id}}">
        <input type="hidden" name="end_date" value="{{ $end_date }}">
        <input type="hidden" name="start_date" value="{{ $start_date }}">
        <input type="hidden" name="type_of_patient" value="{{ $type_of_patient }}">
        <button type="submit" class="btn btn-primary download-buttom">Download</button>
    </form>
    
    <center id="top">

        <h1 style=" font-size: 30px; font-weight:700; margin: 10px auto!important">Vanillat Hunder</h1>
        <div class="info">
            <table class="table_info">
                {{-- <span> Pillkhana , Muradpur</span> <br>
                        <span> Chittagong , Bangladesh</span> <br>
                    <span>Phone : {{ $company_phone }}</span> --}}

                <h4 style="font-size: 20px; font-weight:500;text-decoration: underline;">Booking Report</h4>

            </table>
        </div>
        <!--End Info-->
    </center>


    <div>

        <div class="box-body">
            <center>
                <strong>From:</strong> {{ $start_date }}
                <strong>To:</strong> {{ $end_date }}
                

                @foreach ($vehicle_details as $key => $list)
                <table id="" style="margin-top: 50px" class="sub_test_table table table-bordered table-hover">
                    <tbody>

                        <tr>
                            <th colspan="2" style="text-align: center">Booking NO: {{ $list->invoice_number }} </th>

                        </tr>
                        <tr>
                            <th colspan="2" style="text-align: center">Type Of Patient:
                                @if (@$list->ipd_patient_info_registration_id)
                                IPD Patient
                                @elseif (@$list->outdoor_registration_id)
                                Outdoor Patient
                                @else
                                Normal Patient
                                @endif </th>
                        </tr>
                        <tr>
                            <td class="table-1-col-1">Driver Name </td>
                            <td>{{$list->driver->name}}</td>
                        </tr>
                        <tr>
                            <td class="table-1-col-1">Amdulance Name </td>
                            <td>{{$list->vehicleInfo->model_no}}</td>
                        </tr>
                        <tr>
                            <td class="table-1-col-1">Patient Name </td>
                            <td>{{$list->patient_name}}</td>
                        </tr>
                        <tr>
                            <td class="table-1-col-1">Mobile </td>
                            <td>{{$list->patient_phone_one}}</td>
                        </tr>
                        <tr>
                            <td class="table-1-col-1">Schedule </td>
                            <td>({{$list->vehicleSchedule->start_time}} - {{$list->vehicleSchedule->end_time}})</td>
                        </tr>
                        <tr>
                            <td class="table-1-col-1">Price </td>
                            <td>{{ @$list->vehicleSchedule->price }} tk</td>
                        </tr>
                        <tr>
                            <td class="table-1-col-1">Discount </td>
                            <td>{{ @$list->vehicleSchedule->price - $list->subtotal }} tk</td>
                        </tr>
                        <tr>
                            <td class="table-1-col-1">Subtotal </td>
                            <td>{{ $list->subtotal }} tk</td>
                        </tr>
                        <tr>
                            <td class="table-1-col-1">Paid </td>
                            <td>{{ $list->paid }} tk</td>
                        </tr>
                        <tr>
                            <td class="table-1-col-1">Due </td>
                            <td>{{ $list->due }} tk</td>
                        </tr>
                        <tr>
                            <td class="table-1-col-1">Date And Time </td>
                            <td>{{ $list->start_date_and_time }} </td>
                        </tr>
                        <tr>
                            <td class="table-1-col-1">Address </td>
                            <td>{{ $list->patient_address }} </td>
                        </tr>



                    </tbody>

                </table>
                @endforeach
            </center>

        </div>


    </div>
</div>



@endsection
@push('scripts')
<style type="text/css">
    body {
        font-family: Verdana !important;
    }

    .border-bottom {
        border-bottom: 2px solid !important;
    }

    .fw-500 {
        font-weight: 500 !important;
    }

    table {
        width: 650px !important;
    }

    table th {
        font-weight: 600
    }


    .table-academic {
        width: 100%;
        margin-bottom: 2px solid !important;
        background-color: transparent !important;
    }

    .table-1-col-1 {
        width: 33% !important;
        text-align: center !important;
    }

    .sub_test_table,
    th,
    td {
        border: 1px solid black !important;
        border-collapse: collapse !important;
        padding: 6px 30px !important;
    }

    .appointment_table {
        width: 100% !important;
        margin: 0 auto !important;
        margin-top: 10px !important;
        border-collapse: collapse !important;
        border: 1px dotted #111111 !important;
        font-size: 12px !important;
    }

    tr {
        line-height: 25px !important;
    }

    .download-buttom {
        position: absolute;
        top: 80px;
        right: 30px;
    }

</style>
@endpush
