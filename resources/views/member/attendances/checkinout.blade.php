<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => route('admin.dashboard'),
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Attendance Checkinout',
//        'href' => route('member.attendances.index'),
    ]
];

$data['data'] = [
    'name' => 'Attendance Checkinout',
    'title'=>'Attendance Checkinout',
    'heading' =>trans('common.attendance_check_in_out'),
];

?>



@extends('layouts.back-end.master', $data)


@push('styles')

    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">

@endpush

@section('contents')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">

        @include('common._alert')

        @include('common._error')
        <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Attendance Checkinout</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'member.checkinout-attendances','method' => 'get', 'role'=>'form' ]) !!}

                <div class="box-body">


                    <div class="col-md-3">

                        {{--<div class="form-group">--}}
                            {{--<label for="status"> Employee  <span class="text-red"> * </span> </label>--}}
                            {{--<select class="form-control select2" required name="employee_id" id="employee_id" >--}}
                                {{--<option value=""> Select Employee</option>--}}
                                {{--@foreach($employees as $value)--}}
                                    {{--<option value="{{$value->id}}"  > {{ $value->employeeID." - ".$value->first_name." ".$value->last_name }}</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                        {{--</div>--}}

                        <div class="form-group">
                            <label for="name"> Date <span class="text-red"> * </span> </label>
                            {!! Form::text('attend_date', null,['id'=>'start_date','class'=>'form-control ','placeholder'=>'Enter date', 'required', 'autocomplete'=>"off"]); !!}
                        </div>

                        {{--<div class="form-group">--}}
                            {{--<label for="display_name">Time In<span class="text-red"> * </span> </label>--}}
                            {{--{!! Form::time('time_in',null,['id'=>'time_in','class'=>'form-control','placeholder'=>'Enter time in']); !!}--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="display_name">Time Out<span class="text-red"> * </span> </label>--}}
                            {{--{!! Form::time('time_out',null,['id'=>'time_out','class'=>'form-control','placeholder'=>'Enter time out']); !!}--}}
                        {{--</div>--}}

                    </div>
                    <div class="col-md-2 pt-3">
                        <button type="submit" class="btn btn-primary mt-3">View Report</button>
                    </div>

                </div>
                @if(count($attendances)>0)
                    <div class="box-header with-border">
                        <h3 class="box-title">{!! $report_title !!}  </h3>

                        <a href="{{  $full_url }}type=print" class="btn btn-sm btn-primary pull-right" id="btn-print"> <i class="fa fa-print"></i> Print </a>
                        <a href="{{  $full_url }}type=download" class="btn btn-sm btn-success pull-right mx-3"> <i class="fa fa-download"></i> Download </a>
                    </div>

                @endif

                <div class="box-body">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive">
                            <table class="table table-responsive" id="myTable">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Shift</th>
                                        <th>In Time</th>
                                        <th>Out Time</th>
                                        <th>Late Time</th>
                                        <th>Attend Status</th>
                                        <th>Late Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendances as $key => $value)

                                        @php

                 /*   if($value->attend_status == 'P' && $value->shift_type == 0)
                    {
                    }else if($value->attend_status == 'P' && $value->shift_type == 1)
                    {
                    }else if($value->attend_status == 'P' && $value->shift_type_adv == 1)
                    {
                    }else if($value->attend_status == 'P' && $value->shift_type_adv == 0)
                    {
                    } */


                    if ($value->late == 'N'){
                        $value->late = '';
                    }
                    else if ($value->attend_status == 'P') {
                        //$value->late <= '00:00:00'
                        // $value->late = '';
                        $value->attend_status = 'Present';
                    }
                    else if ($value->late <= '00:00:00') {

                        $value->late = '';
                    }

                    if ($value->attend_status == 'W') {
                      $value->intime = 'Weekend';
                      $value->outtime = 'Weekend';
                      $value->late = '';
                    } else if ($value->attend_status == 'A') {
                      $value->intime = 'Absent';
                      $value->outtime = 'Absent';
                      $value->late = 'Absent';
                    }else if ($value->attend_status == 'L') {
                      $value->intime = 'Leave';
                      $value->outtime = 'Leave';
                      $value->late = 'Leave';
                    }else if ($value->attend_status == 'H') {
                      $value->intime = 'Holiday';
                      $value->outtime = 'Holiday';
                      $value->late = 'Holiday';
                    }

                @endphp

                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->employeeID }}</td>
                    <td>{{ $value->UNAME }}</td>
                    <td>{{ date('M d, Y', strtotime($value->attend_date ))}}</td>
                    <td>{{ $value->shift }}</td>
                    <td>{{ $value->intime }}</td>
                    <td>{{ $value->outtime }}</td>
                    <td>{{ $value->late }}</td>
                    <td>{{ $value->attend_status }}</td>
                    <td>{{ $value->late_status }}</td>
                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->

            {!! Form::close() !!}
            <!-- /.box -->
            </div>
        </div>
    </div>
@endsection


@push('scripts')

    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- CK Editor -->
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>

    <!-- Date range picker -->
    <script type="text/javascript">

        $(function () {

            $('#start_date').datepicker({
                "format": 'yyyy-mm-dd',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });

            $('.select2').select2();

            $('#myTable').DataTable();
        });

    </script>

@endpush

