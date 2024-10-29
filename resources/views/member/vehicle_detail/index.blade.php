<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.vehicle_detail.index']) ? route('member.vehicle_detail.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Vehicle Detail',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Vehicle Detail',
    'title' => 'List Of Vehicle Detail',
    'heading' => 'List Of Vehicle Detail',
];

?>

@extends('layouts.back-end.master', $data)


@section('contents')
<style>
    /* width */
    ::-webkit-scrollbar {
        height: 6px !important;
        width: 0px !important;

    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #dbdbdb !important;
        border-radius: 10px !important;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #6AA0BF !important;
        border-radius: 10px !important;
        padding: 0 10px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #9a70e5b8 !important;
    }

</style>
<div class="row">
    <div class="col-xs-12">

        @include('common._alert')

        <div class="row">
            <div class="col-md-12">

                <div class="box">
                    <div class="box-header">
                        <div class="box-header">
                            @if (\Auth::user()->can(['member.doctors.create']))
                            <a href="{{ route('member.vehicle_detail.create') }}" class="btn btn-info"> <i class="fa fa-plus">
                                </i> Add Vehicle Detail</a>
                            @endif

                        </div>
                    </div>
                    <!-- /.box-header -->
                    <h4 class="w-100 py-3 pl-4 text-white" style="background:#367fa9bd">General Patients</h4>
                    <div class="box-body table-responsive">

                        <table class="table text-nowrap table-bordered table-hover vanilla-table1 scrolling">
                            <thead>
                                <tr>
                                    <th>Booking No</th>
                                    <th>Driver Name</th>
                                    <th>Ambulance Name</th>
                                    <th>Patient Name</th>
                                    <th>Patient Email</th>
                                    <th>Schedule</th>
                                    <th>Price</th>
                                    <th>First Mobile Number</th>
                                    <th>Second Mobile Number</th>
                                    <th>Start Date And Time</th>
                                    <th>Patient Address</th>


                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($Vehicle_details as $key => $list)
                                @if($list->ipd_patient_info_registration_id == null && $list->outdoor_registration_id == null)
                                <tr>
                                    <td>#INV{{ $list->invoice_number }}</td>
                                    <td>{{ $list->driver->name }}</td>
                                    <td>{{ $list->vehicleInfo->model_no }}</td>
                                    <td>{{ $list->patient_name }}</td>
                                    <td>{{ $list->patient_email }}</td>
                                    <td>({{ @$list->vehicleSchedule->start_time }} - {{ @$list->vehicleSchedule->end_time }})</td>
                                    <td>{{ $list->vehicleSchedule->price }}</td>
                                    <td>{{ $list->patient_phone_one }}</td>
                                    <td>{{ $list->patient_phone_two }}</td>
                                    <td>{{ $list->start_date_and_time }}</td>
                                    <td>{{ $list->patient_address }}</td>




                                </tr>
                                @endif

                                @endforeach

                            </tbody>

                        </table>

                    </div>
                    <h4 class="mt-5 w-100  py-3 pl-4 text-white" style="background:#367fa9bd">IPD Patients</h4>
                    <div class="box-body table-responsive">

                        <table class="table text-nowrap table-bordered table-hover vanilla-table1 scrolling">
                            <thead>
                                <tr>
                                    <th>Booking No</th>
                                    <th>Driver Name</th>
                                    <th>Ambulance Name</th>
                                    <th>Patient Name</th>
                                    <th>Patient Email</th>
                                    <th>Schedule</th>
                                    <th>Price</th>
                                    <th>First Mobile Number</th>
                                    <th>Gender</th>
                                    <th>Age</th>
                                    <th>Start Date And Time</th>
                                    <th>End Date And Time</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($Vehicle_details as $key => $list)
                                @if($list->ipd_patient_info_registration_id !== null )<tr>
                                    <td>#INV{{ $list->invoice_number }}</td>
                                    <td>{{ $list->driver->name }}</td>
                                    <td>{{ $list->vehicleInfo->model_no }}</td>
                                    <td>{{ $list->patient_name }}</td>
                                    <td>{{ $list->patient_email }}</td>
                                    <td>({{ @$list->vehicleSchedule->start_time }} - {{ @$list->vehicleSchedule->end_time }})</td>
                                    <td>{{ @$list->vehicleSchedule->price }}</td>
                                    <td>{{ $list->patient_phone_one }}</td>
                                    <td>{{ $list->gender }}</td>
                                    <td>{{ $list->age }}</td>
                                    <td>{{ $list->start_date_and_time }}</td>
                                    <td>{{ $list->end_date_and_time }}</td>

                                </tr>
                                @endif

                                @endforeach

                            </tbody>

                        </table>
                    </div>
                    <h4 class="mt-5 w-100  py-3 pl-4 text-white" style="background:#367fa9bd">Outdoor Patients</h4>
                    <div class="box-body table-responsive">

                        <table class="table text-nowrap table-bordered table-hover vanilla-table1 scrolling">
                            <thead>
                                <tr>
                                    <th>Booking No</th>
                                    <th>Driver Name</th>
                                    <th>Ambulance Name</th>
                                    <th>Patient Name</th>
                                    <th>Schedule</th>
                                    <th>Price</th>
                                    <th>First Mobile Number</th>
                                    <th>Gender</th>
                                    <th>Age</th>
                                    <th>Start Date And Time</th>
                                    <th>End Date And Time</th>


                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($Vehicle_details as $key => $list)
                                @if($list->outdoor_registration_id !== null )
                                <tr>
                                    <td>#INV{{ $list->invoice_number }}</td>
                                    <td>{{ $list->driver->name }}</td>
                                    <td>{{ $list->vehicleInfo->model_no }}</td>
                                    <td>{{ $list->patient_name }}</td>
                                    <td>({{ @$list->vehicleSchedule->start_time }} - {{ @$list->vehicleSchedule->end_time }})</td>
                                    <td>{{ @$list->vehicleSchedule->price }}</td>
                                    <td>{{ $list->patient_phone_one }}</td>
                                    <td>{{ $list->gender }}</td>
                                    <td>{{ $list->age }}</td>
                                    <td>{{ $list->start_date_and_time }}</td>
                                    <td>{{ $list->end_date_and_time }}</td>


                                </tr>
                                @endif

                                @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection


@push('scripts')


<script>
    $(function() {
        $(".vanilla-table1").DataTable({
            // "lengthMenu":[ 3,4 ],
            "searching": true
        , });
        $("#vanilla-table2").DataTable({

            "searching": true
        , });

    });

</script>

@endpush
