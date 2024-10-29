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
        'name' => 'Ambulance Booking',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Ambulance Booking',
    'title' => 'List Of Ambulance Booking',
    'heading' => 'List Of Ambulance Booking',
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
        background: #6AA0BF !important;
    }

</style>
<div class="row">
    <div class="col-xs-12">

        @include('common._alert')

        <div class="row">
            <div class="col-md-12">


                <div class="box">

                    <div class="box-header" style="display: flex;">
                        @if (\Auth::user()->can(['member.doctors.create']))
                        <a href="{{ route('member.vehicle_detail.create') }}" class="btn btn-info"> <i class="fa fa-plus">
                            </i> Add Ambulance Booking</a>
                        @endif
                    </div>
                    <div class="box-body">
                        <form method="POST" action="{{ url('/member/date_by_reserve_booking') }}">
                            @csrf


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Type Of Patient<span class="text-red"> * </span> </label>
                                    <select class="form-control" name="type_of_patient">
                                        <option>Select</option>
                                        <option value="ipd">IPD</option>
                                        <option value="outdoor">Outdoor</option>
                                        <option value="normal">Normal</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Start Date <span class="text-red"> * </span> </label>
                                    <input class="form-control datePicker" placeholder="Enter Start Date" required="" name="start_date" type="text">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">End Date <span class="text-red"> * </span> </label>
                                    <input class="form-control datePicker" placeholder="Enter End Date" required="" name="end_date" type="text">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Driver Name<span class="text-red"> * </span> </label>
                                    <select class="form-control" name="driver_id">
                                        <option>Select</option>
                                        @foreach($drivers as $key=>$driver)
                                        <option value="{{$key}}">{{$driver}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Ambulance Name<span class="text-red"> * </span> </label>
                                    <select class="form-control" name="vehicle_info_id">
                                        <option>Select</option>
                                        @foreach($vehicleInfos as $key=>$vehicle_info)
                                        <option value="{{$key}}">{{$vehicle_info}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-2">
                                <button style="margin-top: 23px !important;" type="submit" class="btn btn-primary">Search</button>
                            </div>                            
                        </form>

                        @if(@$start_date && @$end_date)
                            <div class="col-md-2">
                                <form action="{{ url('/member/reserve_booking_report') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="vehicle_info_id" value="{{@$vehicle_info_id}}">
                                    <input type="hidden" name="driver_id" value="{{@$driver_id}}">
                                    <input type="hidden" name="end_date" value="{{ @$end_date }}">
                                    <input type="hidden" name="start_date" value="{{ @$start_date }}">
                                    <input type="hidden" name="type_of_patient" value="{{ @$type_of_patient }}">

                                    <button style="margin-top: 23px !important;" type="submit" class="btn btn-primary download-buttom">Report</button>

                                </form>
                            </div>                        
                        @endif
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">

                        <div class="table-responsive text-nowrap">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
{{-- <div class="modal fade" id="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ending Reserve</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{url('/member/booking_vehicle')}}">
@csrf
<div class="modal-body">

    @method("PUT")
    <input type="hidden" id="vehicle_detail_id" name="vehicle_detail_id">
    <div class="form-group">
        <label for="name">End Date And Time <span class="text-red"> * </span> </label>
        <input id="datePicker" class="form-control" placeholder="Enter End Date And Time" required="" name="end_date_and_time" type="text">
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Save changes</button>
</div>
</form>
</div>
</div>
</div> --}}
@endsection


@push('scripts')
<style>
    :focus-visible {
        outline: 0;
        box-shadow: none;
    }

    .type-of-patient {
        padding: 0;
        background: #ffffff;
        border: none;
        color: black;
    }

    .type-of-patient option {
        border-bottom: 1px solid black
    }

</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- datetime picker -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/css/bootstrap-datetimepicker.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/js/bootstrap-datetimepicker.min.js"></script>
<script>
    $(document).ready(function() {
        $(".datePicker").datetimepicker({
            format: 'DD/MM/YYYY'
            , defaultDate: new Date()
            , showTimezone: true
        , });
    })

</script>
@endpush
@push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('public/vendor/datatables/buttons.server-side.js') }}"></script>
    {!! $dataTable->scripts() !!}
@endpush
