<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

 $route =  \Auth::user()->can(['member.vehicle_detail.index']) ? route('member.vehicle_detail.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Vehicle Schedule',
        'href' => $route,
    ],
    [
        'name' => (@$vehicleSchedule) ? 'Edit' : 'Create',
    ],
];

$data['data'] = [
    'name' => 'Birth Certificate',
    'title'=> (@$vehicleSchedule) ? 'Edit Vehicle Schedule' : 'Create Vehicle Schedule',
    'heading' => 'Vehicle Schedule',
];

?>



@extends('layouts.back-end.master', $data)

@section('contents')
<div class="row">
    <!-- left column -->
    <div class="col-md-12">

        @include('common._alert')

        @include('common._error')
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Create Vehicle Schedule</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            <div class="row ">
                <div class="col-md-6">
                    <form method="POST" action="{{(@$vehicleSchedule) ? route('member.vehicle_schedule.update',$vehicleSchedule->id) : route('member.vehicle_schedule.store')}}" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                        @csrf
                        @if(isset($vehicleSchedule))
                        @method('put')
                        @endif
                        <div class="box-body">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Ambulance Name<span class="text-red"> * </span> </label>
                                    <select id="name" class="form-control" required="" name="vehicle_info_id">
                                        @if(isset($vehicleSchedule))
                                        <option value="{{$vehicleSchedule->vehicle_info_id}}">{{$vehicleSchedule->vehicleInfo->model_no}}</option>
                                        @else
                                        <option>Select</option>
                                        @endif

                                        @foreach($vehicleInfos as $key=>$vehicle_info)
                                        <option value="{{$key}}">{{$vehicle_info}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name">Start Time <span class="text-red"> * </span> </label>
                                    <input class="form-control datePicker" placeholder="Enter Start Time" value="{{@$vehicleSchedule->start_time}}" required="" name="start_time" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="name">End Time <span class="text-red"> * </span> </label>
                                    <input class="form-control datePicker" placeholder="Enter End Time" value="{{@$vehicleSchedule->end_time}}" required="" name="end_time" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="name">Price <span class="text-red"> * </span> </label>
                                    <input id="name" class="form-control" placeholder="Enter Price" value="{{@$vehicleSchedule->price}}" required="" name="price" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="name">Driver Name<span class="text-red"> * </span> </label>
                                    <select id="name" class="form-control" required="" name="driver_id">
                                        @if(isset($vehicleSchedule))
                                        <option value="{{$vehicleSchedule->driver_id}}">{{$vehicleSchedule->driver->name}}</option>
                                        @else
                                        <option>Select</option>
                                        @endif

                                        @foreach($drivers as $key=>$driver)
                                        <option value="{{$key}}">{{$driver}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name">Status <span class="text-red"> * </span> </label>
                                    <select id="name" class="form-control" required="" name="status">
                                        @if(isset($vehicleSchedule))
                                        <option value="{{$vehicleSchedule->status}}">{{$vehicleSchedule->status == 1 ? 'Active' : 'Inactive'}}</option>
                                        @else
                                        <option value=" ">Select</option>
                                        @endif
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="box-footer">
                            <div class="col-md-12">
                                @if(isset($vehicleSchedule))
                                <button type="submit" class="btn btn-primary">Update</button>
                                @else
                                <button type="submit" class="btn btn-primary">Submit</button>
                                @endif

                            </div>
                        </div>
                        <!-- /.box-body -->

                    </form>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table id="vanilla-table1" class="table text-nowrap table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ambulance Name</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Price</th>
                                    <th>Driver Name</th>
                                    <th>Status</th>
                                    <th>Action</th>


                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($vehicle_schedules as $key => $list)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ @$list->vehicleInfo->model_no }}</td>
                                    <td>{{ $list->start_time }}</td>
                                    <td>{{ $list->end_time }}</td>
                                    <td>{{ $list->price }}</td>
                                    <td>{{ $list->driver->name }}</td>
                                    <td>
                                        <label class="label {{@$list->status == 1 ? 'label-primary' : 'label-warning'}}  ">{{@$list->status == 1 ? 'Active' : 'Inactive'}}</label>
                                    </td>
                                    <td>
                                        @if(\Auth::user()->can(['member.doctors.edit']))
                                        <a class="btn btn-xs btn-success" href="{{ route('member.vehicle_schedule.edit',@$list->id) }}"><i class="fa fa-edit" title='Edit'></i>
                                        </a>


                                        @endif


                                        {{-- @if(\Auth::user()->can(['member.doctor_comission.show']))
                                                <a class="btn btn-xs btn-success"
                                                    href="{{ route('member.driver.show',$list->id) }}"><i class="fa fa-eye" title='Comission Show'></i>
                                        </a>


                                        @endif --}}

                                        @if(\Auth::user()->can(['member.doctors.destroy']))

                                        <a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm" data-target="{{ route('member.vehicle_schedule.destroy', @$list->id) }}">
                                            <i class="fa fa-times"></i>
                                        </a>

                                        @endif
                                    </td>

                                </tr>
                                @endforeach

                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
</div>
@endsection
@push('scripts')
<!-- datetime picker -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/css/bootstrap-datetimepicker.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/js/bootstrap-datetimepicker.min.js"></script>


<script>
    $(document).ready(function() {
        $(".datePicker").datetimepicker({
            format: 'hh:mm A'
            , defaultDate: new Date()
            , showTimezone: true
                //locale: 'bn'
            , sideBySide: true
            , viewMode: 'time'
            , pickDate: false
            , pickTime: true
        , });
    })

</script>
<script>
    $(function() {
        $("#vanilla-table1").DataTable({
            // "lengthMenu":[ 3,4 ],
            "searching": true
        , });
        $("#vanilla-table2").DataTable({

            "searching": true
        , });

    });

</script>
@endpush
